<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Services\{RekapitulasiService, LaporanPdfService, ExportExcelService};
use Illuminate\Http\Request;

class RekapBulananController extends Controller
{
    public function __construct(
        private RekapitulasiService $rekap,
        private LaporanPdfService $pdf,
        private ExportExcelService $excel
    ) {}

    public function index(Request $request) {
        $bulan = (int)$request->get('bulan', now()->month);
        $tahun = (int)$request->get('tahun', now()->year);
        $regu_id = $request->get('regu_id');
        $data  = $this->rekap->rekapBulanan($bulan, $tahun, $regu_id);
        $regus = \App\Models\Regu::all();
        return view('laporan.rekap-bulanan.index', compact('data','bulan','tahun','regus','regu_id'));
    }

    public function exportPdf(Request $request) {
        $bulan = (int)$request->get('bulan', now()->month);
        $tahun = (int)$request->get('tahun', now()->year);
        $regu_id = $request->get('regu_id');
        $data  = $this->rekap->rekapBulanan($bulan, $tahun, $regu_id);
        return $this->pdf->rekapBulanan($data);
    }

    public function exportExcel(Request $request) {
        $bulan = (int)$request->get('bulan', now()->month);
        $tahun = (int)$request->get('tahun', now()->year);
        $regu_id = $request->get('regu_id');
        $data  = $this->rekap->rekapBulanan($bulan, $tahun, $regu_id);
        return $this->excel->rekapBulanan($data);
    }
}
