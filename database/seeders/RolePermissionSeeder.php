<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role, Permission};

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Master
            'master.view','master.create','master.edit','master.delete',
            // Operasional
            'shift.view','shift.create','shift.edit','shift.delete','shift.submit','shift.approve',
            'trip.view','trip.create','trip.edit','trip.delete',
            'tagih.view','tagih.create','tagih.edit',
            'jasa-sandar.view','jasa-sandar.create','jasa-sandar.edit',
            'penjualan.view','penjualan.create','penjualan.edit',
            'limpahan.view','limpahan.create','limpahan.edit',
            'manifest.view','manifest.create','manifest.edit',
            'asuransi.view','asuransi.create','asuransi.edit',
            // Laporan
            'laporan.view','laporan.export',
        ];

        foreach ($permissions as $p) Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);

        // Admin: semua akses
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Supervisi: operasional + laporan (tidak bisa master)
        $supervisi = Role::firstOrCreate(['name' => 'supervisi']);
        $supervisi->givePermissionTo([
            'shift.view','shift.create','shift.edit','shift.submit',
            'trip.view','trip.create','trip.edit','trip.delete',
            'tagih.view','tagih.create','tagih.edit',
            'jasa-sandar.view','jasa-sandar.create','jasa-sandar.edit',
            'penjualan.view','penjualan.create','penjualan.edit',
            'limpahan.view','limpahan.create','limpahan.edit',
            'manifest.view','manifest.create','manifest.edit',
            'asuransi.view','asuransi.create','asuransi.edit',
            'laporan.view','laporan.export',
        ]);

        // Kolektor: input operasional harian
        $kolektor = Role::firstOrCreate(['name' => 'kolektor']);
        $kolektor->givePermissionTo([
            'shift.view',
            'tagih.view','tagih.create','tagih.edit',
            'penjualan.view','penjualan.create','penjualan.edit',
            'limpahan.view','limpahan.create',
            'manifest.view','manifest.create',
            'laporan.view',
        ]);

        // Eksekutif: hanya view & laporan
        $eksekutif = Role::firstOrCreate(['name' => 'eksekutif']);
        $eksekutif->givePermissionTo(['laporan.view','laporan.export','shift.view','trip.view']);
    }
}
