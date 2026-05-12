<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\{ShiftOperasional, Regu};
use App\Services\LaporanPdfService;
use Illuminate\Http\Request;

class KlaimRoroController extends Controller
{
    public function __construct(private LaporanPdfService $pdf) {}

    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));
        $reguId  = $request->get('regu_id');

        $shifts = ShiftOperasional::with([
            'regu',
            'tripKapal.kapal',
            'tripKapal.dermaga',
            'tripKapal.tagihPelayaran',
        ])
            ->whereDate('tanggal', $tanggal)
            ->when($reguId, fn($q) => $q->where('regu_id', $reguId))
            ->get();

        $regu = Regu::where('aktif', true)->orderBy('kode_regu')->get();

        return view('laporan.klaim-roro.index', compact('shifts', 'tanggal', 'regu', 'reguId'));
    }

    public function exportPdf(ShiftOperasional $shift)
    {
        return $this->pdf->klaimRoro($shift);
    }
}
