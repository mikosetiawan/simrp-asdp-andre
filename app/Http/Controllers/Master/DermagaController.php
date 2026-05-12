<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Dermaga;
use Illuminate\Http\Request;

class DermagaController extends Controller
{
    public function index() {
        $dermaga = Dermaga::orderBy('kode_dermaga')->paginate(20);
        return view('master.dermaga.index', compact('dermaga'));
    }
    public function create() { return view('master.dermaga.form', ['dermaga' => new Dermaga(), 'mode'=>'create']); }
    public function store(Request $request) {
        $v = $request->validate([
            'nama_dermaga' => 'required|string|max:50',
            'kode_dermaga' => 'required|string|max:10|unique:dermaga',
            'tarif_jsn_per_trip' => 'required|numeric|min:0',
            'tarif_engker_per_trip' => 'required|numeric|min:0',
            'kapasitas_trip_per_hari' => 'required|integer|min:0',
            'aktif' => 'boolean',
        ]);
        Dermaga::create($v);
        return redirect()->route('master.dermaga.index')->with('success','Dermaga berhasil ditambahkan.');
    }
    public function edit(Dermaga $dermaga) { return view('master.dermaga.form',['dermaga'=>$dermaga,'mode'=>'edit']); }
    public function update(Request $request, Dermaga $dermaga) {
        $v = $request->validate([
            'nama_dermaga' => 'required|string|max:50',
            'kode_dermaga' => 'required|string|max:10|unique:dermaga,kode_dermaga,'.$dermaga->id,
            'tarif_jsn_per_trip' => 'required|numeric|min:0',
            'tarif_engker_per_trip' => 'required|numeric|min:0',
            'kapasitas_trip_per_hari' => 'required|integer|min:0',
            'aktif' => 'boolean',
        ]);
        $dermaga->update($v);
        return redirect()->route('master.dermaga.index')->with('success','Dermaga berhasil diperbarui.');
    }
    public function destroy(Dermaga $dermaga) {
        if ($dermaga->tripKapal()->exists()) return back()->with('error','Dermaga sudah memiliki data trip, tidak dapat dihapus.');
        $dermaga->delete();
        return redirect()->route('master.dermaga.index')->with('success','Dermaga berhasil dihapus.');
    }
}
