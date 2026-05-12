<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dermaga;

class DermagaSeeder extends Seeder
{
    public function run(): void
    {
        $dermaga = [
            ['nama_dermaga' => 'Dermaga I',   'kode_dermaga' => 'D1', 'tarif_jsn_per_trip' => 5200000, 'tarif_engker_per_trip' => 1200000, 'kapasitas_trip_per_hari' => 20],
            ['nama_dermaga' => 'Dermaga II',  'kode_dermaga' => 'D2', 'tarif_jsn_per_trip' => 5200000, 'tarif_engker_per_trip' => 1200000, 'kapasitas_trip_per_hari' => 20],
            ['nama_dermaga' => 'Dermaga III', 'kode_dermaga' => 'D3', 'tarif_jsn_per_trip' => 5200000, 'tarif_engker_per_trip' => 1200000, 'kapasitas_trip_per_hari' => 18],
            ['nama_dermaga' => 'Dermaga IV',  'kode_dermaga' => 'D4', 'tarif_jsn_per_trip' => 5200000, 'tarif_engker_per_trip' => 1200000, 'kapasitas_trip_per_hari' => 18],
            ['nama_dermaga' => 'Dermaga V',   'kode_dermaga' => 'D5', 'tarif_jsn_per_trip' => 5800000, 'tarif_engker_per_trip' => 1400000, 'kapasitas_trip_per_hari' => 16],
            ['nama_dermaga' => 'Dermaga VI',  'kode_dermaga' => 'D6', 'tarif_jsn_per_trip' => 5800000, 'tarif_engker_per_trip' => 1400000, 'kapasitas_trip_per_hari' => 16],
            ['nama_dermaga' => 'Dermaga VII', 'kode_dermaga' => 'D7', 'tarif_jsn_per_trip' => 6200000, 'tarif_engker_per_trip' => 1600000, 'kapasitas_trip_per_hari' => 14],
        ];
        foreach ($dermaga as $d) Dermaga::create($d);
    }
}
