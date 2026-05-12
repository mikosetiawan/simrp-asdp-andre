<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Regu};

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $regu = Regu::all()->keyBy('kode_regu');

        $users = [
            [
                'name' => 'Administrator SIMRP',
                'nik'  => '1000000001',
                'email' => 'admin@asdpmerak.co.id',
                'password' => Hash::make('Admin@1234'),
                'jabatan' => 'Administrator',
                'regu_id' => null,
                'role' => 'admin',
            ],
            [
                'name' => 'Budi Santoso',
                'nik'  => '2000000001',
                'email' => 'supervisi1@asdpmerak.co.id',
                'password' => Hash::make('Supervisi@1'),
                'jabatan' => 'Supervisi Usaha Regu I',
                'regu_id' => $regu['R1']->id ?? null,
                'role' => 'supervisi',
            ],
            [
                'name' => 'Siti Rahayu',
                'nik'  => '2000000002',
                'email' => 'supervisi2@asdpmerak.co.id',
                'password' => Hash::make('Supervisi@2'),
                'jabatan' => 'Supervisi Usaha Regu II',
                'regu_id' => $regu['R2']->id ?? null,
                'role' => 'supervisi',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'nik'  => '2000000003',
                'email' => 'supervisi3@asdpmerak.co.id',
                'password' => Hash::make('Supervisi@3'),
                'jabatan' => 'Supervisi Usaha Regu III',
                'regu_id' => $regu['R3']->id ?? null,
                'role' => 'supervisi',
            ],
            [
                'name' => 'Dewi Lestari',
                'nik'  => '3000000001',
                'email' => 'kolektor1@asdpmerak.co.id',
                'password' => Hash::make('Kolektor@1'),
                'jabatan' => 'Kolektor Tiket Regu I',
                'regu_id' => $regu['R1']->id ?? null,
                'role' => 'kolektor',
            ],
            [
                'name' => 'Eko Prasetyo',
                'nik'  => '3000000002',
                'email' => 'kolektor2@asdpmerak.co.id',
                'password' => Hash::make('Kolektor@2'),
                'jabatan' => 'Kolektor Tiket Regu II',
                'regu_id' => $regu['R2']->id ?? null,
                'role' => 'kolektor',
            ],
            [
                'name' => 'Fitri Wahyuni',
                'nik'  => '3000000003',
                'email' => 'kolektor3@asdpmerak.co.id',
                'password' => Hash::make('Kolektor@3'),
                'jabatan' => 'Kolektor Tiket Regu III',
                'regu_id' => $regu['R3']->id ?? null,
                'role' => 'kolektor',
            ],
            [
                'name' => 'Direktur ASDP Merak',
                'nik'  => '4000000001',
                'email' => 'eksekutif@asdpmerak.co.id',
                'password' => Hash::make('Eksekutif@1'),
                'jabatan' => 'Kepala Cabang',
                'regu_id' => null,
                'role' => 'eksekutif',
            ],
        ];

        foreach ($users as $u) {
            $role = $u['role'];
            unset($u['role']);
            $user = User::create($u);
            $user->assignRole($role);
        }
    }
}
