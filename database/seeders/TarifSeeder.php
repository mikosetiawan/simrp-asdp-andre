<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tarif;

class TarifSeeder extends Seeder
{
    public function run(): void
    {
        // Tarif Permenhub berlaku mulai 1 Februari 2024
        Tarif::create([
            'nama_tarif'      => 'Tarif Permenhub 2024 (Berlaku Feb 2024)',
            'berlaku_mulai'   => '2024-02-01',
            'berlaku_sampai'  => null,
            'aktif'           => true,
            // Penumpang
            'ekb_dewasa'      => 84800,
            'ekb_lansia'      => 42400,
            'ekb_anak'        => 0,
            // Kendaraan
            'gol_i'           => 29600,
            'gol_ii'          => 53000,
            'gol_iii'         => 147200,
            'gol_iv_a'        => 749128,
            'gol_iv_b'        => 763628,
            'gol_v_a'         => 1033048,
            'gol_v_b'         => 1057048,
            'gol_vi_a'        => 1454728,
            'gol_vi_b'        => 1479928,
            'gol_vii'         => 2009748,
            'gol_viii'        => 2735748,
            'gol_ix'          => 3632148,
            // Asuransi
            'asuransi_jr_pnp' => 3600,
            'asuransi_jp_pnp' => 1400,
        ]);
    }
}
