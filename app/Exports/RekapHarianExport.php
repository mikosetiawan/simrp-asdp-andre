<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapHarianExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function __construct(private array $data) {}

    public function title(): string
    {
        return 'Rekap Harian ' . $this->data['tanggal'];
    }

    public function headings(): array
    {
        return ['No', 'Regu', 'Nama Shift', 'Total Trip', 'Total Penumpang', 'Total Kendaraan', 'Total Pendapatan (Rp)'];
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->data['per_regu'] as $i => $item) {
            $shift = $item['shift'];
            $rows[] = [
                $i + 1,
                $shift->regu->nama_regu ?? '-',
                $shift->nama_shift,
                $item['trip'],
                $item['penumpang'],
                $item['kendaraan'],
                $item['pendapatan'],
            ];
        }
        // Total row
        $rows[] = [
            '', 'TOTAL', '',
            $this->data['total_trip'],
            $this->data['total_penumpang'],
            '',
            $this->data['total_pendapatan'],
        ];
        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '003087']], 'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]],
        ];
    }
}
