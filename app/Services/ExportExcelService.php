<?php
namespace App\Services;

use App\Exports\RekapHarianExport;
use App\Exports\RekapBulananExport;
use App\Exports\RekapTahunanExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcelService
{
    public function rekapHarian(array $data): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filename = 'rekap-harian-' . $data['tanggal'] . '.xlsx';
        return Excel::download(new RekapHarianExport($data), $filename);
    }

    public function rekapBulanan(array $data): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $bulan = str_pad($data['bulan'], 2, '0', STR_PAD_LEFT);
        $filename = "rekap-bulanan-{$bulan}-{$data['tahun']}.xlsx";
        return Excel::download(new RekapBulananExport($data), $filename);
    }

    public function rekapTahunan(array $data): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filename = "rekap-tahunan-{$data['tahun']}.xlsx";
        return Excel::download(new RekapTahunanExport($data), $filename);
    }
}
