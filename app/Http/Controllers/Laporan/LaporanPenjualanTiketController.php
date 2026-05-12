<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Regu;
use App\Models\ShiftOperasional;
use App\Services\LaporanPdfService;
use Illuminate\Http\Request;

class LaporanPenjualanTiketController extends Controller
{
    private const POS_LIST = ['Koordinator', 'Ruang Tunggu', 'Toll Gate I', 'Toll Gate II', 'Shelter'];

    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));
        $reguId  = $request->get('regu_id');

        $posList = self::POS_LIST;

        $shifts = $this->queryShifts($tanggal, $reguId);

        $regu = Regu::where('aktif', true)->orderBy('kode_regu')->get();

        return view('laporan.penjualan-tiket.index', compact(
            'shifts',
            'tanggal',
            'regu',
            'reguId',
            'posList',
        ));
    }

    public function exportPdf(Request $request, LaporanPdfService $pdf)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));
        $reguId  = $request->get('regu_id');

        $shifts = $this->queryShifts($tanggal, $reguId);

        $reguKeterangan = 'Semua regu';
        if ($reguId) {
            $rg = Regu::find($reguId);
            $reguKeterangan = $rg ? "{$rg->kode_regu} — {$rg->nama_regu}" : "Regu #{$reguId}";
        }

        return $pdf->penjualanTiketLaporan($shifts, $tanggal, $reguKeterangan, self::POS_LIST);
    }

    private function queryShifts(string $tanggal, $reguId): \Illuminate\Support\Collection
    {
        return ShiftOperasional::query()
            ->with(['regu', 'penjualanTiket'])
            ->whereDate('tanggal', $tanggal)
            ->when($reguId, fn ($q) => $q->where('regu_id', $reguId))
            ->orderBy('regu_id')
            ->orderBy('id')
            ->get();
    }
}
