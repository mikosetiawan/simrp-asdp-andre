<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Services\{RekapitulasiService, LaporanPdfService, ExportExcelService};
use Illuminate\Http\Request;

class RekapTahunanController extends Controller
{
    public function __construct(
        private RekapitulasiService $rekap,
        private LaporanPdfService $pdf,
        private ExportExcelService $excel
    ) {}

    public function index(Request $request) {
        $tahun = (int)$request->get('tahun', now()->year);
        $regu_id = $request->get('regu_id');
        $data  = $this->rekap->rekapTahunan($tahun, $regu_id);
        $regus = \App\Models\Regu::all();
        return view('laporan.rekap-tahunan.index', compact('data','tahun','regus','regu_id'));
    }

    public function exportPdf(Request $request) {
        $tahun = (int)$request->get('tahun', now()->year);
        $regu_id = $request->get('regu_id');
        $data  = $this->rekap->rekapTahunan($tahun, $regu_id);
        return $this->pdf->rekapTahunan($data);
    }

    public function exportExcel(Request $request) {
        $tahun = (int)$request->get('tahun', now()->year);
        $regu_id = $request->get('regu_id');
        $data  = $this->rekap->rekapTahunan($tahun, $regu_id);
        return $this->excel->rekapTahunan($data);
    }
}
