<?php
namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use App\Models\{TripKapal, ShiftOperasional, Kapal, Dermaga, TagihPelayaran, Tarif, JasaSandar};
use App\Services\RekapitulasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripKapalController extends Controller
{
    public function create(ShiftOperasional $shift)
    {
        $kapal   = Kapal::aktif()->orderBy('nama_kapal')->get();
        $dermaga = Dermaga::where('aktif', true)->orderBy('kode_dermaga')->get();

        $tripCounts = TripKapal::where('shift_id', $shift->id)
            ->groupBy('kapal_id')
            ->selectRaw('kapal_id, count(*) as total_trip')
            ->pluck('total_trip', 'kapal_id')
            ->toArray();

        $tarif = Tarif::aktifPadaTanggal($shift->tanggal->format('Y-m-d'));
        if (!$tarif) return back()->with('error', 'Tarif aktif tidak ditemukan. Harap konfigurasi tarif terlebih dahulu.');

        $tagih = new TagihPelayaran();
        $jasaExisting = $shift->jasaSandar->keyBy('dermaga_id');

        return view('operasional.trip-kapal.form', compact('shift', 'kapal', 'dermaga', 'tripCounts', 'tarif', 'tagih', 'jasaExisting'));
    }

    public function store(Request $request, ShiftOperasional $shift)
    {
        $v = $request->validate([
            'kapal_id'            => 'required|exists:kapal,id',
            'kapal_pengganti_id'  => 'nullable|exists:kapal,id|different:kapal_id',
            'dermaga_id'          => 'required|exists:dermaga,id',
            'jumlah_trip'         => 'required|integer|min:1',
            'trip_ke'             => 'required|integer|min:1',
            'jam_tiba'            => 'nullable|date_format:H:i',
            'jam_berangkat'       => 'nullable|date_format:H:i',
            'keterangan'          => 'nullable|string',

            'tarif_id'            => 'required|exists:tarif,id',
            'jml_pnp_ekb_d'       => 'required|integer|min:0',
            'jml_pnp_ekb_l'       => 'required|integer|min:0',
            'jml_pnp_ekb_a'       => 'required|integer|min:0',
            'gol_i'               => 'required|integer|min:0',
            'gol_ii'              => 'required|integer|min:0',
            'gol_iii'             => 'required|integer|min:0',
            'gol_iv_a'            => 'required|integer|min:0',
            'gol_iv_b'            => 'required|integer|min:0',
            'gol_v_a'             => 'required|integer|min:0',
            'gol_v_b'             => 'required|integer|min:0',
            'gol_vi_a'            => 'required|integer|min:0',
            'gol_vi_b'            => 'required|integer|min:0',
            'gol_vii'             => 'required|integer|min:0',
            'gol_viii'            => 'required|integer|min:0',
            'gol_ix'              => 'required|integer|min:0',
            'data'                => 'present|array',
            'data.*'              => 'nullable|array',
            'data.*.call_sandar'   => 'nullable|integer|min:0',
            'data.*.jumlah_trip'  => 'nullable|integer|min:0',
            'data.*.keterangan'   => 'nullable|string',
        ]);

        $jasaInput = $v['data'] ?? [];
        unset($v['data']);

        $vTrip = [
            'shift_id'           => $shift->id,
            'kapal_id'           => $v['kapal_id'],
            'kapal_pengganti_id' => $v['kapal_pengganti_id'] ?? null,
            'dermaga_id'         => $v['dermaga_id'],
            'jumlah_trip'        => $v['jumlah_trip'],
            'trip_ke'            => $v['trip_ke'],
            'jam_tiba'           => $v['jam_tiba'] ?? null,
            'jam_berangkat'      => $v['jam_berangkat'] ?? null,
            'keterangan'         => $v['keterangan'] ?? null,
        ];

        $vTagih = [
            'tarif_id'      => $v['tarif_id'],
            'jml_pnp_ekb_d' => $v['jml_pnp_ekb_d'],
            'jml_pnp_ekb_l' => $v['jml_pnp_ekb_l'],
            'jml_pnp_ekb_a' => $v['jml_pnp_ekb_a'],
            'gol_i'         => $v['gol_i'],
            'gol_ii'        => $v['gol_ii'],
            'gol_iii'       => $v['gol_iii'],
            'gol_iv_a'      => $v['gol_iv_a'],
            'gol_iv_b'      => $v['gol_iv_b'],
            'gol_v_a'       => $v['gol_v_a'],
            'gol_v_b'       => $v['gol_v_b'],
            'gol_vi_a'      => $v['gol_vi_a'],
            'gol_vi_b'      => $v['gol_vi_b'],
            'gol_vii'       => $v['gol_vii'],
            'gol_viii'      => $v['gol_viii'],
            'gol_ix'        => $v['gol_ix'],
        ];

        $tarif = Tarif::findOrFail($v['tarif_id']);
        $kalkulasi = (new RekapitulasiService())->hitungPendapatanTagih($vTagih, $tarif);

        DB::transaction(function () use ($vTrip, $vTagih, $kalkulasi, $shift, $jasaInput) {
            $trip = TripKapal::create($vTrip);
            $tagihData = array_merge($vTagih, $kalkulasi, ['trip_id' => $trip->id]);
            TagihPelayaran::create($tagihData);
            $this->syncJasaSandarUntukShift($shift->id, $jasaInput);
        });

        return redirect()->route('operasional.shift.show', $shift->id)
            ->with('success', 'Data Trip Kapal, Penumpang, Kendaraan, dan Jasa Sandar berhasil disimpan secara langsung!');
    }

    public function edit(TripKapal $tripKapal)
    {
        $tripKapal->load(['shift.jasaSandar', 'tagihPelayaran']);
        $kapal   = Kapal::aktif()->orderBy('nama_kapal')->get();
        $dermaga = Dermaga::where('aktif', true)->orderBy('kode_dermaga')->get();
        $shift   = $tripKapal->shift;

        $tripCounts = TripKapal::where('shift_id', $shift->id)
            ->where('id', '!=', $tripKapal->id)
            ->groupBy('kapal_id')
            ->selectRaw('kapal_id, count(*) as total_trip')
            ->pluck('total_trip', 'kapal_id')
            ->toArray();

        $tarif = Tarif::aktifPadaTanggal($shift->tanggal->format('Y-m-d'));
        $tagih = $tripKapal->tagihPelayaran ?? new TagihPelayaran(['trip_id' => $tripKapal->id]);
        $jasaExisting = $shift->jasaSandar->keyBy('dermaga_id');

        return view('operasional.trip-kapal.form', compact('tripKapal', 'kapal', 'dermaga', 'shift', 'tripCounts', 'tarif', 'tagih', 'jasaExisting'));
    }

    public function update(Request $request, TripKapal $tripKapal)
    {
        $v = $request->validate([
            'kapal_id'            => 'required|exists:kapal,id',
            'kapal_pengganti_id'  => 'nullable|exists:kapal,id|different:kapal_id',
            'dermaga_id'          => 'required|exists:dermaga,id',
            'jumlah_trip'         => 'required|integer|min:1',
            'trip_ke'             => 'required|integer|min:1',
            'jam_tiba'            => 'nullable|date_format:H:i',
            'jam_berangkat'       => 'nullable|date_format:H:i',
            'keterangan'          => 'nullable|string',

            'tarif_id'            => 'required|exists:tarif,id',
            'jml_pnp_ekb_d'       => 'required|integer|min:0',
            'jml_pnp_ekb_l'       => 'required|integer|min:0',
            'jml_pnp_ekb_a'       => 'required|integer|min:0',
            'gol_i'               => 'required|integer|min:0',
            'gol_ii'              => 'required|integer|min:0',
            'gol_iii'             => 'required|integer|min:0',
            'gol_iv_a'            => 'required|integer|min:0',
            'gol_iv_b'            => 'required|integer|min:0',
            'gol_v_a'             => 'required|integer|min:0',
            'gol_v_b'             => 'required|integer|min:0',
            'gol_vi_a'            => 'required|integer|min:0',
            'gol_vi_b'            => 'required|integer|min:0',
            'gol_vii'             => 'required|integer|min:0',
            'gol_viii'            => 'required|integer|min:0',
            'gol_ix'              => 'required|integer|min:0',
            'data'                => 'present|array',
            'data.*'              => 'nullable|array',
            'data.*.call_sandar'   => 'nullable|integer|min:0',
            'data.*.jumlah_trip'  => 'nullable|integer|min:0',
            'data.*.keterangan'   => 'nullable|string',
        ]);

        $jasaInput = $v['data'] ?? [];
        unset($v['data']);

        $vTrip = [
            'kapal_id'           => $v['kapal_id'],
            'kapal_pengganti_id' => $v['kapal_pengganti_id'] ?? null,
            'dermaga_id'         => $v['dermaga_id'],
            'jumlah_trip'        => $v['jumlah_trip'],
            'trip_ke'            => $v['trip_ke'],
            'jam_tiba'           => $v['jam_tiba'] ?? null,
            'jam_berangkat'      => $v['jam_berangkat'] ?? null,
            'keterangan'         => $v['keterangan'] ?? null,
        ];

        $vTagih = [
            'tarif_id'      => $v['tarif_id'],
            'jml_pnp_ekb_d' => $v['jml_pnp_ekb_d'],
            'jml_pnp_ekb_l' => $v['jml_pnp_ekb_l'],
            'jml_pnp_ekb_a' => $v['jml_pnp_ekb_a'],
            'gol_i'         => $v['gol_i'],
            'gol_ii'        => $v['gol_ii'],
            'gol_iii'       => $v['gol_iii'],
            'gol_iv_a'      => $v['gol_iv_a'],
            'gol_iv_b'      => $v['gol_iv_b'],
            'gol_v_a'       => $v['gol_v_a'],
            'gol_v_b'       => $v['gol_v_b'],
            'gol_vi_a'      => $v['gol_vi_a'],
            'gol_vi_b'      => $v['gol_vi_b'],
            'gol_vii'       => $v['gol_vii'],
            'gol_viii'      => $v['gol_viii'],
            'gol_ix'        => $v['gol_ix'],
        ];

        $tarif = Tarif::findOrFail($v['tarif_id']);
        $kalkulasi = (new RekapitulasiService())->hitungPendapatanTagih($vTagih, $tarif);

        DB::transaction(function () use ($tripKapal, $vTrip, $vTagih, $kalkulasi, $jasaInput) {
            $tripKapal->update($vTrip);
            TagihPelayaran::updateOrCreate(
                ['trip_id' => $tripKapal->id],
                array_merge($vTagih, $kalkulasi, ['trip_id' => $tripKapal->id])
            );
            $this->syncJasaSandarUntukShift($tripKapal->shift_id, $jasaInput);
        });

        return redirect()->route('operasional.shift.show', $tripKapal->shift_id)
            ->with('success', 'Data Trip Kapal dan Tagih Pelayaran berhasil diperbarui.');
    }

    public function destroy(TripKapal $tripKapal)
    {
        $shiftId = $tripKapal->shift_id;
        $tripKapal->delete();

        return redirect()->route('operasional.shift.show', $shiftId)->with('success', 'Trip kapal berhasil dihapus.');
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
            $kalkulasi = (new RekapitulasiService())->hitungJasaSandar(
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
}
