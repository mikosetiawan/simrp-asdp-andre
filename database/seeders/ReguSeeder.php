<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Regu;

class ReguSeeder extends Seeder
{
    public function run(): void
    {
        $regu = [
            ['nama_regu' => 'Regu I',   'kode_regu' => 'R1', 'keterangan' => 'Regu shift pertama'],
            ['nama_regu' => 'Regu II',  'kode_regu' => 'R2', 'keterangan' => 'Regu shift kedua'],
            ['nama_regu' => 'Regu III', 'kode_regu' => 'R3', 'keterangan' => 'Regu shift ketiga'],
        ];
        foreach ($regu as $r) Regu::create($r);
    }
}
