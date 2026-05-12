<?php
namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use App\Models\{ManifestPenumpang, TripKapal, AsuransiShift, ShiftOperasional, Tarif};
use Illuminate\Http\Request;

class ManifestController extends Controller
{
    public function create(TripKapal $tripKapal) {
        $tripKapal->load(['kapal','dermaga','shift']);
        $manifest = $tripKapal->manifest ?? new ManifestPenumpang(['trip_id' => $tripKapal->id]);
        return view('operasional.manifest.form', compact('tripKapal','manifest'));
    }

    public function store(Request $request, TripKapal $tripKapal) {
        $v = $request->validate([
            'pnp_dalam_gol_iv_a'  => 'required|integer|min:0',
            'pnp_dalam_gol_iv_b'  => 'required|integer|min:0',
            'pnp_dalam_gol_v_a'   => 'required|integer|min:0',
            'pnp_dalam_gol_v_b'   => 'required|integer|min:0',
            'pnp_dalam_gol_vi_a'  => 'required|integer|min:0',
            'pnp_dalam_gol_vi_b'  => 'required|integer|min:0',
            'pnp_dalam_gol_vii'   => 'required|integer|min:0',
            'pnp_dalam_gol_viii'  => 'required|integer|min:0',
            'pnp_dalam_gol_ix'    => 'required|integer|min:0',
            'keterangan'          => 'nullable|string',
        ]);
        $total = array_sum(array_filter($v, 'is_numeric'));
        $v['total_pnp_manifest'] = $total;
        $v['trip_id'] = $tripKapal->id;
        ManifestPenumpang::updateOrCreate(['trip_id' => $tripKapal->id], $v);
        return redirect()->route('operasional.shift.show', $tripKapal->shift_id)->with('success', 'Manifest berhasil disimpan.');
    }
}

class AsuransiController extends Controller
{
    public function create(ShiftOperasional $shift) {
        $asuransi = $shift->asuransiShift ?? new AsuransiShift(['shift_id' => $shift->id]);
        $tarif    = Tarif::aktifPadaTanggal($shift->tanggal->format('Y-m-d'));
        return view('operasional.asuransi.form', compact('shift','asuransi','tarif'));
    }

    public function store(Request $request, ShiftOperasional $shift) {
        $v = $request->validate([
            'jr_pnp_dewasa'  => 'required|integer|min:0',
            'jr_pnp_lansia'  => 'required|integer|min:0',
            'jr_pnp_anak'    => 'required|integer|min:0',
            'jr_knd_gol_i'   => 'required|integer|min:0',
            'jr_knd_gol_ii'  => 'required|integer|min:0',
            'jr_knd_gol_iii' => 'required|integer|min:0',
            'jr_knd_gol_iv'  => 'required|integer|min:0',
            'jr_knd_gol_v'   => 'required|integer|min:0',
            'jr_knd_gol_vi'  => 'required|integer|min:0',
            'jr_knd_gol_vii' => 'required|integer|min:0',
            'jr_knd_gol_viii'=> 'required|integer|min:0',
            'jr_knd_gol_ix'  => 'required|integer|min:0',
            'jp_pnp_dewasa'  => 'required|integer|min:0',
            'jp_pnp_lansia'  => 'required|integer|min:0',
        ]);
        $tarif = Tarif::aktifPadaTanggal($shift->tanggal->format('Y-m-d'));
        $jr = array_sum(array_intersect_key($v, array_flip(array_filter(array_keys($v), fn($k) => str_starts_with($k, 'jr_')))));
        $jp = ($v['jp_pnp_dewasa'] + $v['jp_pnp_lansia']) * ($tarif?->asuransi_jp_pnp ?? 0);
        $v['total_jr'] = $jr; $v['total_jp'] = $jp; $v['total_asuransi'] = $jr + $jp;
        AsuransiShift::updateOrCreate(['shift_id' => $shift->id], array_merge($v, ['shift_id' => $shift->id]));
        return redirect()->route('operasional.shift.show', $shift)->with('success', 'Data asuransi berhasil disimpan.');
    }
}
