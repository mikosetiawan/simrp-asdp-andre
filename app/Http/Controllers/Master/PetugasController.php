<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\{User, Regu};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PetugasController extends Controller
{
    public function index() {
        $petugas = User::with(['regu', 'roles'])->orderBy('name')->paginate(20);
        return view('master.petugas.index', compact('petugas'));
    }

    public function create() {
        $regu  = Regu::where('aktif', true)->orderBy('kode_regu')->get();
        $roles = Role::orderBy('name')->get();
        return view('master.petugas.form', ['petugas' => new User(), 'regu' => $regu, 'roles' => $roles, 'mode' => 'create']);
    }

    public function store(Request $request) {
        $v = $request->validate([
            'name'     => 'required|string|max:100',
            'nik'      => 'nullable|string|max:20|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'regu_id'  => 'nullable|exists:regu,id',
            'jabatan'  => 'nullable|string|max:50',
            'role'     => 'required|string|exists:roles,name',
            'aktif'    => 'boolean',
        ]);

        $role = $v['role'];
        unset($v['role'], $v['password_confirmation']);
        $v['password'] = Hash::make($v['password']);

        $user = User::create($v);
        $user->assignRole($role);

        return redirect()->route('master.petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit(User $petugas) {
        $regu  = Regu::where('aktif', true)->orderBy('kode_regu')->get();
        $roles = Role::orderBy('name')->get();
        return view('master.petugas.form', compact('petugas', 'regu', 'roles') + ['mode' => 'edit']);
    }

    public function update(Request $request, User $petugas) {
        $v = $request->validate([
            'name'     => 'required|string|max:100',
            'nik'      => 'nullable|string|max:20|unique:users,nik,'.$petugas->id,
            'email'    => 'required|email|unique:users,email,'.$petugas->id,
            'password' => 'nullable|string|min:8|confirmed',
            'regu_id'  => 'nullable|exists:regu,id',
            'jabatan'  => 'nullable|string|max:50',
            'role'     => 'required|string|exists:roles,name',
            'aktif'    => 'boolean',
        ]);

        $role = $v['role'];
        unset($v['role'], $v['password_confirmation']);
        if (empty($v['password'])) unset($v['password']);
        else $v['password'] = Hash::make($v['password']);

        $petugas->update($v);
        $petugas->syncRoles([$role]);

        return redirect()->route('master.petugas.index')->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy(User $petugas) {
        if ($petugas->id === auth()->id()) return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        $petugas->delete();
        return redirect()->route('master.petugas.index')->with('success', 'Petugas berhasil dihapus.');
    }
}
