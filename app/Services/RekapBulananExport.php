<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapBulananExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize
{
    public function __construct(private array $data) {}

    public function title(): string
    {
        $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        return 'Rekap ' . $namaBulan[$this->data['bulan']] . ' ' . $this->data['tahun'];
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
                $tgl,
                $hari['trip'],
                $hari['penumpang'],
                $hari['pendapatan'],
            ];
        }
        $rows[] = ['TOTAL', $this->data['total_trip'], $this->data['total_penumpang'], $this->data['total_pendapatan']];
        return $rows;
    }
}
