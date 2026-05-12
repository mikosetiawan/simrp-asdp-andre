<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\ShiftOperasional;
use App\Services\LaporanPdfService;
use Illuminate\Http\Request;

class BapController extends Controller
{
    public function __construct(private LaporanPdfService $pdf) {}

    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));

        $shifts = ShiftOperasional::with([
            'regu',
            'supervisi',
            'kolektor',
            'tripKapal.kapal',
            'tripKapal.tagihPelayaran',
        ])
            ->whereDate('tanggal', $tanggal)
            ->get();

        return view('laporan.bap.index', compact('shifts', 'tanggal'));
    }

    public function exportPdf(ShiftOperasional $shift)
    {
        return $this->pdf->bap($shift);
    }
}
