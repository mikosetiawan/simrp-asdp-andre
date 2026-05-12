<?php
namespace App\Services;

use App\Models\ShiftOperasional;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPdfService
{
    public function rekapHarian(array $data): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView('laporan.pdf.rekap-harian', $data)
                  ->setPaper('a4', 'landscape');
        $filename = 'rekap-harian-' . $data['tanggal'] . '.pdf';
        return $pdf->download($filename);
    }

    public function rekapBulanan(array $data): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView('laporan.pdf.rekap-bulanan', $data)
                  ->setPaper('a4', 'landscape');
        $bulan = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);
        return $pdf->download("rekap-bulanan-{$bulan}-{$data['tahun']}.pdf");
    }

    public function rekapTahunan(array $data): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView('laporan.pdf.rekap-tahunan', $data)
                  ->setPaper('a4', 'landscape');
        return $pdf->download("rekap-tahunan-{$data['tahun']}.pdf");
    }

    public function klaimRoro(ShiftOperasional $shift): \Illuminate\Http\Response
    {
        $shift->load(['regu', 'supervisi', 'tripKapal.kapal', 'tripKapal.dermaga', 'tripKapal.tagihPelayaran.tarif']);
        $pdf = Pdf::loadView('laporan.pdf.klaim-roro', compact('shift'))
                  ->setPaper('legal', 'portrait');

        $reguName = $shift->regu ? str_replace(' ', '-', $shift->regu->nama_regu) : 'Regu';
        $shiftName = str_replace(' ', '-', $shift->nama_shift);
        $tgl = $shift->tanggal->format('Y-m-d');
        $filename = "Klaim-RoRo_{$reguName}_{$shiftName}_{$tgl}_#{$shift->id}.pdf";

        return $pdf->download($filename);
    }

    public function bap(ShiftOperasional $shift): \Illuminate\Http\Response
    {
        $shift->load(['regu', 'supervisi', 'kolektor', 'tripKapal.tagihPelayaran']);
        $pdf = Pdf::loadView('laporan.pdf.bap', compact('shift'))
                  ->setPaper('a4', 'portrait');
        return $pdf->download("bap-shift-{$shift->id}.pdf");
    }
}
