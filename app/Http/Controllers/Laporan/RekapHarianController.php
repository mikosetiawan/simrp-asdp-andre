<?php
namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\ShiftOperasional;
use App\Services\{RekapitulasiService, LaporanPdfService, ExportExcelService};
use Illuminate\Http\Request;

class RekapHarianController extends Controller
{
    public function __construct(
        private RekapitulasiService $rekap,
        private LaporanPdfService $pdf,
        private ExportExcelService $excel
    ) {}

    public function index(Request $request) {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));
        $regu_id = $request->get('regu_id');
        $data    = $this->rekap->rekapHarian($tanggal, $regu_id);
        $regus   = \App\Models\Regu::all();
        return view('laporan.rekap-harian.index', compact('data','tanggal','regus','regu_id'));
    }

    public function exportPdf(Request $request) {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));
        $regu_id = $request->get('regu_id');
        $data    = $this->rekap->rekapHarian($tanggal, $regu_id);
        return $this->pdf->rekapHarian($data);
    }

    public function exportExcel(Request $request) {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));
        $regu_id = $request->get('regu_id');
        $data    = $this->rekap->rekapHarian($tanggal, $regu_id);
        return $this->excel->rekapHarian($data);
    }
}
