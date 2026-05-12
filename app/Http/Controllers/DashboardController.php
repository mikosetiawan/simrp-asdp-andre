<?php
namespace App\Http\Controllers;

use App\Models\{ShiftOperasional, TripKapal, Kapal, Dermaga};
use App\Services\RekapitulasiService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private RekapitulasiService $rekap) {}

    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));

        $rekap = $this->rekap->rekapHarian($tanggal);

        // 7 hari terakhir untuk chart
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->format('Y-m-d');
            $r = $this->rekap->rekapHarian($tgl);
            $chartData[] = [
                'tanggal'    => now()->subDays($i)->format('d/m'),
                'pendapatan' => $r['total_pendapatan'],
                'trip'       => $r['total_trip'],
            ];
        }

        // Top kapal hari ini
        $topKapal = TripKapal::with('kapal')
            ->whereHas('shift', fn($q) => $q->whereDate('tanggal', $tanggal))
            ->selectRaw('kapal_id, SUM(jumlah_trip) as total_trip')
            ->groupBy('kapal_id')
            ->orderByDesc('total_trip')
            ->limit(5)
            ->get();

        // Shift hari ini
        $shiftsHariIni = ShiftOperasional::with(['regu','supervisi'])
            ->whereDate('tanggal', $tanggal)
            ->get();

        // Summary counts
        $totalKapal    = Kapal::where('aktif', true)->count();
        $totalDermaga  = Dermaga::where('aktif', true)->count();
        $shiftDraft    = ShiftOperasional::whereDate('tanggal', $tanggal)->where('status','draft')->count();
        $shiftSubmitted= ShiftOperasional::whereDate('tanggal', $tanggal)->where('status','submitted')->count();

        return view('dashboard.index', compact(
            'tanggal','rekap','chartData','topKapal','shiftsHariIni',
            'totalKapal','totalDermaga','shiftDraft','shiftSubmitted'
        ));
    }
}
