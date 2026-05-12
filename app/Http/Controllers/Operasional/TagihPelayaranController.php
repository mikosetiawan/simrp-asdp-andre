<?php
namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use App\Models\{TagihPelayaran, TripKapal, Tarif};
use App\Services\RekapitulasiService;
use Illuminate\Http\Request;

class TagihPelayaranController extends Controller
{
    public function __construct(private RekapitulasiService $rekap) {}

    public function create(TripKapal $tripKapal) {
        $tripKapal->load(['shift','kapal','dermaga']);
        $tarif = Tarif::aktifPadaTanggal($tripKapal->shift->tanggal->format('Y-m-d'));
        if (!$tarif) return back()->with('error', 'Tarif aktif tidak ditemukan. Harap konfigurasi tarif terlebih dahulu.');
        $tagih = $tripKapal->tagihPelayaran ?? new TagihPelayaran(['trip_id' => $tripKapal->id]);
        return view('operasional.tagih-pelayaran.form', compact('tripKapal','tarif','tagih'));
    }

    public function store(Request $request, TripKapal $tripKapal) {
        $v = $request->validate([
            'tarif_id'        => 'required|exists:tarif,id',
            'jml_pnp_ekb_d'   => 'required|integer|min:0',
            'jml_pnp_ekb_l'   => 'required|integer|min:0',
            'jml_pnp_ekb_a'   => 'required|integer|min:0',
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
        ]);

        $tarif   = Tarif::findOrFail($v['tarif_id']);
        $kalkulasi = $this->rekap->hitungPendapatanTagih($v, $tarif);

        $data = array_merge($v, $kalkulasi, ['trip_id' => $tripKapal->id]);
        TagihPelayaran::updateOrCreate(['trip_id' => $tripKapal->id], $data);

        return redirect()->route('operasional.shift.show', $tripKapal->shift_id)
            ->with('success', 'Data tagih pelayaran berhasil disimpan. Pendapatan: Rp ' . number_format($kalkulasi['total_pendapatan'], 0, ',', '.'));
    }

    public function edit(TagihPelayaran $tagihPelayaran) {
        $tagihPelayaran->load(['trip.kapal','trip.dermaga','trip.shift','tarif']);
        $tripKapal = $tagihPelayaran->trip;
        $tarif     = $tagihPelayaran->tarif;
        return view('operasional.tagih-pelayaran.form', compact('tripKapal','tarif','tagihPelayaran') + ['tagih'=>$tagihPelayaran]);
    }

    public function update(Request $request, TagihPelayaran $tagihPelayaran) {
        $v = $request->validate([
            'tarif_id'        => 'required|exists:tarif,id',
            'jml_pnp_ekb_d'   => 'required|integer|min:0',
            'jml_pnp_ekb_l'   => 'required|integer|min:0',
            'jml_pnp_ekb_a'   => 'required|integer|min:0',
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
        ]);
        $tarif     = Tarif::findOrFail($v['tarif_id']);
        $kalkulasi = $this->rekap->hitungPendapatanTagih($v, $tarif);
        $tagihPelayaran->update(array_merge($v, $kalkulasi));

        return redirect()->route('operasional.shift.show', $tagihPelayaran->trip->shift_id)
            ->with('success', 'Data tagih pelayaran berhasil diperbarui.');
    }

    /** AJAX: hitung pendapatan real-time */
    public function hitung(Request $request) {
        $tarif = Tarif::findOrFail($request->tarif_id);
        $data  = $request->only(['jml_pnp_ekb_d','jml_pnp_ekb_l','jml_pnp_ekb_a','gol_i','gol_ii','gol_iii','gol_iv_a','gol_iv_b','gol_v_a','gol_v_b','gol_vi_a','gol_vi_b','gol_vii','gol_viii','gol_ix']);
        $data  = array_map('intval', $data);
        $result = (new RekapitulasiService())->hitungPendapatanTagih($data, $tarif);
        return response()->json($result);
    }
}
