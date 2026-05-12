<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Regu;
use Illuminate\Http\Request;

class ReguController extends Controller
{
    public function index() {
        $regu = Regu::withCount('shifts')->orderBy('kode_regu')->get();
        return view('master.regu.index', compact('regu'));
    }
    public function create() { return view('master.regu.form', ['regu' => new Regu(), 'mode' => 'create']); }
    public function store(Request $request) {
        $v = $request->validate(['nama_regu'=>'required|string|max:20','kode_regu'=>'required|string|max:5|unique:regu','keterangan'=>'nullable|string','aktif'=>'boolean']);
        Regu::create($v);
        return redirect()->route('master.regu.index')->with('success','Regu berhasil ditambahkan.');
    }
    public function edit(Regu $regu) { return view('master.regu.form',['regu'=>$regu,'mode'=>'edit']); }
    public function update(Request $request, Regu $regu) {
        $v = $request->validate(['nama_regu'=>'required|string|max:20','kode_regu'=>'required|string|max:5|unique:regu,kode_regu,'.$regu->id,'keterangan'=>'nullable|string','aktif'=>'boolean']);
        $regu->update($v);
        return redirect()->route('master.regu.index')->with('success','Regu berhasil diperbarui.');
    }
    public function destroy(Regu $regu) {
        if ($regu->shifts()->exists()) return back()->with('error','Regu tidak dapat dihapus, sudah memiliki data shift.');
        $regu->delete();
        return redirect()->route('master.regu.index')->with('success','Regu berhasil dihapus.');
    }
}
