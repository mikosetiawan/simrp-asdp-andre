<?php
namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use App\Models\{PenjualanTiket, ShiftOperasional, Tarif};
use App\Services\RekapitulasiService;
use Illuminate\Http\Request;

class PenjualanTiketController extends Controller
{
    public function __construct(private RekapitulasiService $rekap) {}

    public function create(ShiftOperasional $shift) {
        $posList  = ['Koordinator','Ruang Tunggu','Toll Gate I','Toll Gate II','Shelter'];
        $existing = $shift->penjualanTiket->keyBy('pos_penjualan');
        $tarif    = Tarif::aktifPadaTanggal($shift->tanggal->format('Y-m-d'));
        return view('operasional.penjualan-tiket.form', compact('shift','posList','existing','tarif'));
    }

    public function store(Request $request, ShiftOperasional $shift) {
        $request->validate(['data' => 'required|array']);
        $tarif = Tarif::aktifPadaTanggal($shift->tanggal->format('Y-m-d'));

        foreach ($request->data as $pos => $row) {
            $pnpData = [
                'jml_pnp_ekb_d' => $row['pnp_ekb_d'] ?? 0,
                'jml_pnp_ekb_l' => $row['pnp_ekb_l'] ?? 0,
                'jml_pnp_ekb_a' => $row['pnp_ekb_a'] ?? 0,
                'gol_i' => $row['knd_gol_i'] ?? 0, 'gol_ii' => $row['knd_gol_ii'] ?? 0,
                'gol_iii' => $row['knd_gol_iii'] ?? 0, 'gol_iv_a' => $row['knd_gol_iv_a'] ?? 0,
                'gol_iv_b' => $row['knd_gol_iv_b'] ?? 0, 'gol_v_a' => $row['knd_gol_v_a'] ?? 0,
                'gol_v_b' => $row['knd_gol_v_b'] ?? 0, 'gol_vi_a' => $row['knd_gol_vi_a'] ?? 0,
                'gol_vi_b' => $row['knd_gol_vi_b'] ?? 0, 'gol_vii' => $row['knd_gol_vii'] ?? 0,
                'gol_viii' => $row['knd_gol_viii'] ?? 0, 'gol_ix' => $row['knd_gol_ix'] ?? 0,
            ];
            $kalkulasi = $tarif ? $this->rekap->hitungPendapatanTagih($pnpData, $tarif) : ['total_pendapatan' => 0];
            PenjualanTiket::updateOrCreate(
                ['shift_id' => $shift->id, 'pos_penjualan' => $pos],
                array_merge([
                    'pnp_ekb_d' => $row['pnp_ekb_d'] ?? 0, 'pnp_ekb_l' => $row['pnp_ekb_l'] ?? 0,
                    'pnp_ekb_a' => $row['pnp_ekb_a'] ?? 0, 'knd_gol_i' => $row['knd_gol_i'] ?? 0,
                    'knd_gol_ii' => $row['knd_gol_ii'] ?? 0, 'knd_gol_iii' => $row['knd_gol_iii'] ?? 0,
                    'knd_gol_iv_a' => $row['knd_gol_iv_a'] ?? 0, 'knd_gol_iv_b' => $row['knd_gol_iv_b'] ?? 0,
                    'knd_gol_v_a' => $row['knd_gol_v_a'] ?? 0, 'knd_gol_v_b' => $row['knd_gol_v_b'] ?? 0,
                    'knd_gol_vi_a' => $row['knd_gol_vi_a'] ?? 0, 'knd_gol_vi_b' => $row['knd_gol_vi_b'] ?? 0,
                    'knd_gol_vii' => $row['knd_gol_vii'] ?? 0, 'knd_gol_viii' => $row['knd_gol_viii'] ?? 0,
                    'knd_gol_ix' => $row['knd_gol_ix'] ?? 0,
                    'total_pendapatan_penjualan' => $kalkulasi['total_pendapatan'],
                    'keterangan' => $row['keterangan'] ?? null,
                ], [])
            );
        }
        return redirect()->route('operasional.shift.show', $shift)->with('success', 'Data penjualan tiket berhasil disimpan.');
    }
}
