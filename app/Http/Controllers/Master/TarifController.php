<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Tarif;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    public function index() {
        $tarif = Tarif::orderByDesc('berlaku_mulai')->paginate(20);
        return view('master.tarif.index', compact('tarif'));
    }

    public function create() {
        return view('master.tarif.form', ['tarif' => new Tarif(), 'mode' => 'create']);
    }

    public function store(Request $request) {
        $v = $request->validate([
            'nama_tarif'      => 'required|string|max:100',
            'berlaku_mulai'   => 'required|date',
            'berlaku_sampai'  => 'nullable|date|after_or_equal:berlaku_mulai',
            'ekb_dewasa'      => 'required|integer|min:0',
            'ekb_lansia'      => 'required|integer|min:0',
            'ekb_anak'        => 'required|integer|min:0',
            'gol_i'           => 'required|integer|min:0',
            'gol_ii'          => 'required|integer|min:0',
            'gol_iii'         => 'required|integer|min:0',
            'gol_iv_a'        => 'required|integer|min:0',
            'gol_iv_b'        => 'required|integer|min:0',
            'gol_v_a'         => 'required|integer|min:0',
            'gol_v_b'         => 'required|integer|min:0',
            'gol_vi_a'        => 'required|integer|min:0',
            'gol_vi_b'        => 'required|integer|min:0',
            'gol_vii'         => 'required|integer|min:0',
            'gol_viii'        => 'required|integer|min:0',
            'gol_ix'          => 'required|integer|min:0',
            'asuransi_jr_pnp' => 'required|integer|min:0',
            'asuransi_jp_pnp' => 'required|integer|min:0',
            'aktif'           => 'boolean',
        ]);
        // Nonaktifkan tarif lama jika yang baru aktif
        if (!empty($v['aktif'])) {
            Tarif::where('aktif', true)->update(['aktif' => false]);
        }
        Tarif::create($v);
        return redirect()->route('master.tarif.index')->with('success', 'Tarif berhasil disimpan.');
    }

    public function edit(Tarif $tarif) {
        return view('master.tarif.form', ['tarif' => $tarif, 'mode' => 'edit']);
    }

    public function update(Request $request, Tarif $tarif) {
        $v = $request->validate([
            'nama_tarif'      => 'required|string|max:100',
            'berlaku_mulai'   => 'required|date',
            'berlaku_sampai'  => 'nullable|date|after_or_equal:berlaku_mulai',
            'ekb_dewasa'      => 'required|integer|min:0',
            'ekb_lansia'      => 'required|integer|min:0',
            'ekb_anak'        => 'required|integer|min:0',
            'gol_i'           => 'required|integer|min:0',
            'gol_ii'          => 'required|integer|min:0',
            'gol_iii'         => 'required|integer|min:0',
            'gol_iv_a'        => 'required|integer|min:0',
            'gol_iv_b'        => 'required|integer|min:0',
            'gol_v_a'         => 'required|integer|min:0',
            'gol_v_b'         => 'required|integer|min:0',
            'gol_vi_a'        => 'required|integer|min:0',
            'gol_vi_b'        => 'required|integer|min:0',
            'gol_vii'         => 'required|integer|min:0',
            'gol_viii'        => 'required|integer|min:0',
            'gol_ix'          => 'required|integer|min:0',
            'asuransi_jr_pnp' => 'required|integer|min:0',
            'asuransi_jp_pnp' => 'required|integer|min:0',
            'aktif'           => 'boolean',
        ]);
        $tarif->update($v);
        return redirect()->route('master.tarif.index')->with('success', 'Tarif berhasil diperbarui.');
    }

    public function destroy(Tarif $tarif) {
        $tarif->delete();
        return redirect()->route('master.tarif.index')->with('success', 'Tarif berhasil dihapus.');
    }
}
