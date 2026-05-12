<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\{Dermaga, Kapal, Regu, ShiftOperasional};
use App\Services\LaporanPdfService;
use Illuminate\Http\Request;

class KlaimRoroController extends Controller
{
    public function __construct(private LaporanPdfService $pdf) {}

    public function index(Request $request)
    {
        $tanggal    = $request->get('tanggal', today()->format('Y-m-d'));
        $reguId     = $request->get('regu_id');
        $kapalId    = $request->filled('kapal_id') ? (int) $request->kapal_id : null;
        $dermagaId  = $request->filled('dermaga_id') ? (int) $request->dermaga_id : null;
        $nomorData  = $request->filled('nomor_data') ? max(1, (int) $request->nomor_data) : null;

        $shifts = ShiftOperasional::with([
            'regu',
            'tripKapal' => function ($q) use ($kapalId, $dermagaId) {
                $q->with(['kapal', 'dermaga', 'tagihPelayaran'])
                    ->when($kapalId, fn ($q) => $q->where('kapal_id', $kapalId))
                    ->when($dermagaId, fn ($q) => $q->where('dermaga_id', $dermagaId))
                    ->orderBy('id');
            },
        ])
            ->whereDate('tanggal', $tanggal)
            ->when($reguId, fn ($q) => $q->where('regu_id', $reguId))
            ->when($kapalId || $dermagaId, function ($q) use ($kapalId, $dermagaId) {
                $q->whereHas('tripKapal', function ($tq) use ($kapalId, $dermagaId) {
                    $tq->when($kapalId, fn ($q) => $q->where('kapal_id', $kapalId))
                        ->when($dermagaId, fn ($q) => $q->where('dermaga_id', $dermagaId));
                });
            })
            ->orderBy('regu_id')
            ->orderBy('id')
            ->get();

        $klaimRowNoBase = 0;
        if ($nomorData !== null) {
            $row = 0;
            $targetTripId = null;
            foreach ($shifts as $shift) {
                foreach ($shift->tripKapal as $trip) {
                    $row++;
                    if ($row === $nomorData) {
                        $targetTripId = $trip->id;
                        break 2;
                    }
                }
            }
            if ($targetTripId === null) {
                $shifts = collect();
            } else {
                $shifts = $shifts->map(function (ShiftOperasional $shift) use ($targetTripId) {
                    $filtered = $shift->tripKapal->filter(fn ($t) => (int) $t->id === (int) $targetTripId)->values();
                    $shift->setRelation('tripKapal', $filtered);

                    return $shift;
                })->filter(fn (ShiftOperasional $s) => $s->tripKapal->isNotEmpty())->values();
            }
            $klaimRowNoBase = $nomorData - 1;
        }

        $regu     = Regu::where('aktif', true)->orderBy('kode_regu')->get();
        $kapals   = Kapal::aktif()->orderBy('nama_kapal')->get();
        $dermagas = Dermaga::where('aktif', true)->orderBy('kode_dermaga')->get();

        return view('laporan.klaim-roro.index', compact(
            'shifts',
            'tanggal',
            'regu',
            'reguId',
            'nomorData',
            'klaimRowNoBase',
            'kapals',
            'dermagas',
            'kapalId',
            'dermagaId',
        ));
    }

    public function exportPdf(Request $request, ShiftOperasional $shift)
    {
        $kapalId   = $request->filled('kapal_id') ? (int) $request->kapal_id : null;
        $dermagaId = $request->filled('dermaga_id') ? (int) $request->dermaga_id : null;

        return $this->pdf->klaimRoro($shift, $kapalId, $dermagaId);
    }
}
