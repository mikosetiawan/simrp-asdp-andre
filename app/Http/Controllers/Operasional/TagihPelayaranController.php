<?php
namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use App\Models\{TagihPelayaran, TripKapal, Tarif, JasaSandar, Dermaga};
use App\Services\RekapitulasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagihPelayaranController extends Controller
{
    public function __construct(private RekapitulasiService $rekap) {}

    public function create(TripKapal $tripKapal) {
        $tripKapal->load(['shift.jasaSandar']);
        $tarif = Tarif::aktifPadaTanggal($tripKapal->shift->tanggal->format('Y-m-d'));
        if (!$tarif) return back()->with('error', 'Tarif aktif tidak ditemukan. Harap konfigurasi tarif terlebih dahulu.');
        $tagih = $tripKapal->tagihPelayaran ?? new TagihPelayaran(['trip_id' => $tripKapal->id]);
        $dermaga = Dermaga::where('aktif', true)->orderBy('kode_dermaga')->get();
        $jasaExisting = $tripKapal->shift->jasaSandar->keyBy('dermaga_id');
        return view('operasional.tagih-pelayaran.form', compact('tripKapal','tarif','tagih','dermaga','jasaExisting'));
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
            'data'            => 'present|array',
            'data.*'          => 'nullable|array',
            'data.*.call_sandar' => 'nullable|integer|min:0',
            'data.*.jumlah_trip'  => 'nullable|integer|min:0',
            'data.*.keterangan'   => 'nullable|string',
        ]);
        $jasaInput = $v['data'] ?? [];
        unset($v['data']);

        $tarif   = Tarif::findOrFail($v['tarif_id']);
        $kalkulasi = $this->rekap->hitungPendapatanTagih($v, $tarif);

        DB::transaction(function () use ($v, $kalkulasi, $tripKapal, $jasaInput) {
            $data = array_merge($v, $kalkulasi, ['trip_id' => $tripKapal->id]);
            TagihPelayaran::updateOrCreate(['trip_id' => $tripKapal->id], $data);
            $this->syncJasaSandarUntukShift($tripKapal->shift_id, $jasaInput);
        });

        return redirect()->route('operasional.shift.show', $tripKapal->shift_id)
            ->with('success', 'Data tagih pelayaran (Tagih01) dan jasa sandar (TAGIH03) berhasil disimpan. Pendapatan tagih: Rp ' . number_format($kalkulasi['total_pendapatan'], 0, ',', '.'));
    }

    public function edit(TagihPelayaran $tagihPelayaran) {
        $tagihPelayaran->load(['trip.kapal','trip.dermaga','trip.shift.jasaSandar','tarif']);
        $tripKapal = $tagihPelayaran->trip;
        $tarif     = $tagihPelayaran->tarif;
        $dermaga = Dermaga::where('aktif', true)->orderBy('kode_dermaga')->get();
        $jasaExisting = $tripKapal->shift->jasaSandar->keyBy('dermaga_id');
        $tagih = $tagihPelayaran;
        return view('operasional.tagih-pelayaran.form', compact('tripKapal','tarif','tagih','dermaga','jasaExisting'));
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
            'data'            => 'present|array',
            'data.*'          => 'nullable|array',
            'data.*.call_sandar' => 'nullable|integer|min:0',
            'data.*.jumlah_trip'  => 'nullable|integer|min:0',
            'data.*.keterangan'   => 'nullable|string',
        ]);
        $jasaInput = $v['data'] ?? [];
        unset($v['data']);

        $tarif     = Tarif::findOrFail($v['tarif_id']);
        $kalkulasi = $this->rekap->hitungPendapatanTagih($v, $tarif);

        DB::transaction(function () use ($tagihPelayaran, $v, $kalkulasi, $jasaInput) {
            $tagihPelayaran->update(array_merge($v, $kalkulasi));
            $this->syncJasaSandarUntukShift($tagihPelayaran->trip->shift_id, $jasaInput);
        });

        return redirect()->route('operasional.shift.show', $tagihPelayaran->trip->shift_id)
            ->with('success', 'Data tagih pelayaran dan jasa sandar berhasil diperbarui.');
    }

    private function syncJasaSandarUntukShift(int $shiftId, array $dataInput): void
    {
        foreach ($dataInput as $dermagaId => $row) {
            if (! is_array($row)) {
                continue;
            }
            $call = (int) ($row['call_sandar'] ?? 0);
            $trip = (int) ($row['jumlah_trip'] ?? 0);
            if ($call === 0 && $trip === 0) {
                JasaSandar::where('shift_id', $shiftId)->where('dermaga_id', $dermagaId)->delete();
                continue;
            }
            $dermaga = Dermaga::find($dermagaId);
            if (! $dermaga) {
                continue;
            }
            $kalkulasi = $this->rekap->hitungJasaSandar(
                $trip,
                $call,
                (float) $dermaga->tarif_jsn_per_trip,
                (float) $dermaga->tarif_engker_per_trip
            );
            JasaSandar::updateOrCreate(
                ['shift_id' => $shiftId, 'dermaga_id' => $dermagaId],
                array_merge([
                    'call_sandar' => $call,
                    'jumlah_trip' => $trip,
                    'tarif_jsn_per_trip' => $dermaga->tarif_jsn_per_trip,
                    'tarif_engker_per_trip' => $dermaga->tarif_engker_per_trip,
                    'keterangan' => $row['keterangan'] ?? null,
                ], $kalkulasi)
            );
        }
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
