<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapBulananExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(private array $data) {}

    public function title(): string
    {
        $bulanStr = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$this->data['bulan']];
        return 'Rekap Bulanan ' . $bulanStr . ' ' . $this->data['tahun'];
    }

    public function headings(): array
    {
        return ['Tanggal', 'Total Trip', 'Total Penumpang', 'Total Pendapatan (Rp)'];
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->data['per_hari'] as $tgl => $hari) {
            $rows[] = [
                \Carbon\Carbon::parse($tgl)->isoFormat('dddd, D MMMM Y'),
                $hari['trip'],
                $hari['penumpang'],
                $hari['pendapatan'],
            ];
        }
        $rows[] = [
            'TOTAL',
            $this->data['total_trip'],
            $this->data['total_penumpang'],
            $this->data['total_pendapatan'],
        ];
        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '003087']]],
        ];
    }
}
