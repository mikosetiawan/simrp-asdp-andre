<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Regu;
use App\Models\ShiftOperasional;
use App\Services\LaporanPdfService;
use Illuminate\Http\Request;

class LaporanLimpahanTiketController extends Controller
{
    /** @var list<string> */
    private const JENIS_TIKET = [
        'EKB-D', 'EKB-L', 'EKB-A',
        'GOL-I', 'GOL-II', 'GOL-III', 'GOL-IVA', 'GOL-IVB',
        'GOL-VA', 'GOL-VB', 'GOL-VIA', 'GOL-VIB',
        'GOL-VII', 'GOL-VIII', 'GOL-IX',
    ];

    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));
        $reguId  = $request->get('regu_id');

        $shifts = ShiftOperasional::query()
            ->with(['regu', 'limpahanTiket.dilimpahkanKeRegu'])
            ->whereDate('tanggal', $tanggal)
            ->when($reguId, fn ($q) => $q->where('regu_id', $reguId))
            ->orderBy('regu_id')
            ->orderBy('id')
            ->get();

        $regu        = Regu::where('aktif', true)->orderBy('kode_regu')->get();
        $jenisTiket  = self::JENIS_TIKET;

        return view('laporan.limpahan-tiket.index', compact(
            'shifts',
            'tanggal',
            'regu',
            'reguId',
            'jenisTiket',
        ));
    }

    public function exportPdf(Request $request, LaporanPdfService $pdf)
    {
        $tanggal    = $request->get('tanggal', today()->format('Y-m-d'));
        $reguId     = $request->get('regu_id');
        $jenisTiket = self::JENIS_TIKET;

        $shifts = ShiftOperasional::query()
            ->with(['regu', 'limpahanTiket.dilimpahkanKeRegu'])
            ->whereDate('tanggal', $tanggal)
            ->when($reguId, fn ($q) => $q->where('regu_id', $reguId))
            ->orderBy('regu_id')
            ->orderBy('id')
            ->get();

        $reguKeterangan = 'Semua regu';
        if ($reguId) {
            $rg = Regu::find($reguId);
            $reguKeterangan = $rg ? "{$rg->kode_regu} — {$rg->nama_regu}" : "Regu #{$reguId}";
        }

        return $pdf->limpahanTiketLaporan($shifts, $tanggal, $reguKeterangan, $jenisTiket);
    }
}
