<?php

namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use App\Models\{AsuransiShift, ShiftOperasional, Tarif};
use Illuminate\Http\Request;

class AsuransiController extends Controller
{
    public function create(ShiftOperasional $shift)
    {
        $asuransi = $shift->asuransiShift ?? new AsuransiShift(['shift_id' => $shift->id]);
        $tarif    = Tarif::aktifPadaTanggal($shift->tanggal->format('Y-m-d'));

        return view('operasional.asuransi.form', compact('shift', 'asuransi', 'tarif'));
    }

    public function store(Request $request, ShiftOperasional $shift)
    {
        $v = $request->validate([
            'jr_pnp_dewasa'   => 'required|integer|min:0',
            'jr_pnp_lansia'   => 'required|integer|min:0',
            'jr_pnp_anak'     => 'required|integer|min:0',
            'jr_knd_gol_i'    => 'required|integer|min:0',
            'jr_knd_gol_ii'   => 'required|integer|min:0',
            'jr_knd_gol_iii'  => 'required|integer|min:0',
            'jr_knd_gol_iv'   => 'required|integer|min:0',
            'jr_knd_gol_v'    => 'required|integer|min:0',
            'jr_knd_gol_vi'   => 'required|integer|min:0',
            'jr_knd_gol_vii'  => 'required|integer|min:0',
            'jr_knd_gol_viii' => 'required|integer|min:0',
            'jr_knd_gol_ix'   => 'required|integer|min:0',
            'jp_pnp_dewasa'   => 'required|integer|min:0',
            'jp_pnp_lansia'   => 'required|integer|min:0',
        ]);

        $tarif = Tarif::aktifPadaTanggal($shift->tanggal->format('Y-m-d'));

        // Hitung total JR: semua field jr_ * tarif JR per pnp
        $jrFields = array_filter($v, fn($val, $key) => str_starts_with($key, 'jr_'), ARRAY_FILTER_USE_BOTH);
        $totalJrUnit = array_sum($jrFields);
        $totalJr = $totalJrUnit * ($tarif?->asuransi_jr_pnp ?? 3600);

        // Hitung total JP
        $totalJp = ($v['jp_pnp_dewasa'] + $v['jp_pnp_lansia']) * ($tarif?->asuransi_jp_pnp ?? 1400);

        $v['total_jr']       = $totalJr;
        $v['total_jp']       = $totalJp;
        $v['total_asuransi'] = $totalJr + $totalJp;
        $v['shift_id']       = $shift->id;

        AsuransiShift::updateOrCreate(
            ['shift_id' => $shift->id],
            $v
        );

        return redirect()
            ->route('operasional.shift.show', $shift)
            ->with('success', 'Data asuransi berhasil disimpan. Total: Rp ' . number_format($v['total_asuransi'], 0, ',', '.'));
    }
}
