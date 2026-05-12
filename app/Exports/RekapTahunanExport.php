<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapTahunanExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(private array $data) {}

    public function title(): string
    {
        return 'Rekap Tahunan ' . $this->data['tahun'];
    }

    public function headings(): array
    {
        return ['Bulan', 'Total Trip', 'Total Penumpang', 'Total Pendapatan (Rp)'];
    }

    public function array(): array
    {
        $rows = [];
        $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        foreach ($this->data['per_bulan'] as $bln => $dataBulan) {
            $rows[] = [
                $namaBulan[$bln],
                $dataBulan['trip'],
                $dataBulan['penumpang'],
                $dataBulan['pendapatan'],
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
