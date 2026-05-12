<?php
namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use App\Models\{JasaSandar, ShiftOperasional, Dermaga};
use App\Services\RekapitulasiService;
use Illuminate\Http\Request;

class JasaSandarController extends Controller
{
    public function __construct(private RekapitulasiService $rekap) {}

    public function create(ShiftOperasional $shift) {
        $dermaga   = Dermaga::where('aktif', true)->orderBy('kode_dermaga')->get();
        $existing  = $shift->jasaSandar->keyBy('dermaga_id');
        return view('operasional.jasa-sandar.form', compact('shift','dermaga','existing'));
    }

    public function store(Request $request, ShiftOperasional $shift) {
        $request->validate(['data' => 'required|array']);
        foreach ($request->data as $dermagaId => $row) {
            if (empty($row['jumlah_trip'])) continue;
            $dermaga = Dermaga::find($dermagaId);
            $kalkulasi = $this->rekap->hitungJasaSandar(
                (int)$row['jumlah_trip'],
                (float)$dermaga->tarif_jsn_per_trip,
                (float)$dermaga->tarif_engker_per_trip
            );
            JasaSandar::updateOrCreate(
                ['shift_id' => $shift->id, 'dermaga_id' => $dermagaId],
                array_merge(['call_sandar' => $row['call_sandar'] ?? 0, 'jumlah_trip' => $row['jumlah_trip'], 'tarif_jsn_per_trip' => $dermaga->tarif_jsn_per_trip, 'tarif_engker_per_trip' => $dermaga->tarif_engker_per_trip, 'keterangan' => $row['keterangan'] ?? null], $kalkulasi)
            );
        }
        return redirect()->route('operasional.shift.show', $shift)->with('success', 'Data jasa sandar berhasil disimpan.');
    }
}
