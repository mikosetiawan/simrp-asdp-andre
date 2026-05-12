<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\{KapalController, DermagaController, TarifController, ReguController, PetugasController};
use App\Http\Controllers\Operasional\{ShiftController, TripKapalController, TagihPelayaranController, PenjualanTiketController, LimpahanTiketController, ManifestController, AsuransiController};
use App\Http\Controllers\Laporan\{RekapHarianController, RekapBulananController, RekapTahunanController, KlaimRoroController, BapController};

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // MASTER DATA — dilindungi role:admin
    Route::prefix('master')->name('master.')->middleware('role:admin')->group(function () {
        Route::resource('kapal',   KapalController::class);
        Route::resource('dermaga', DermagaController::class);
        Route::resource('tarif',   TarifController::class);
        Route::resource('regu',    ReguController::class);
        Route::resource('petugas', PetugasController::class)->parameters(['petugas' => 'petugas']);
    });

    // OPERASIONAL — semua role authenticated bisa akses
    Route::prefix('operasional')->name('operasional.')->group(function () {
        Route::resource('shift', ShiftController::class);
        Route::post('shift/{shift}/submit',  [ShiftController::class, 'submit'])->name('shift.submit');
        Route::post('shift/{shift}/approve', [ShiftController::class, 'approve'])->name('shift.approve');

        Route::get( 'shift/{shift}/trip/create', [TripKapalController::class, 'create'])->name('trip-kapal.create');
        Route::post('shift/{shift}/trip',         [TripKapalController::class, 'store'])->name('trip-kapal.store');
        Route::get( 'trip/{tripKapal}/edit',      [TripKapalController::class, 'edit'])->name('trip-kapal.edit');
        Route::put( 'trip/{tripKapal}',           [TripKapalController::class, 'update'])->name('trip-kapal.update');
        Route::delete('trip/{tripKapal}',         [TripKapalController::class, 'destroy'])->name('trip-kapal.destroy');

        Route::get( 'trip/{tripKapal}/tagih/create', [TagihPelayaranController::class, 'create'])->name('tagih-pelayaran.create');
        Route::post('trip/{tripKapal}/tagih',         [TagihPelayaranController::class, 'store'])->name('tagih-pelayaran.store');
        Route::get( 'tagih/{tagihPelayaran}/edit',    [TagihPelayaranController::class, 'edit'])->name('tagih-pelayaran.edit');
        Route::put( 'tagih/{tagihPelayaran}',         [TagihPelayaranController::class, 'update'])->name('tagih-pelayaran.update');
        Route::post('tagih/hitung',                   [TagihPelayaranController::class, 'hitung'])->name('tagih-pelayaran.hitung');

        Route::get( 'shift/{shift}/penjualan', [PenjualanTiketController::class, 'create'])->name('penjualan-tiket.create');
        Route::post('shift/{shift}/penjualan', [PenjualanTiketController::class, 'store'])->name('penjualan-tiket.store');

        Route::get( 'shift/{shift}/limpahan', [LimpahanTiketController::class, 'create'])->name('limpahan-tiket.create');
        Route::post('shift/{shift}/limpahan', [LimpahanTiketController::class, 'store'])->name('limpahan-tiket.store');

        Route::get( 'trip/{tripKapal}/manifest', [ManifestController::class, 'create'])->name('manifest.create');
        Route::post('trip/{tripKapal}/manifest', [ManifestController::class, 'store'])->name('manifest.store');

        Route::get( 'shift/{shift}/asuransi', [AsuransiController::class, 'create'])->name('asuransi.create');
        Route::post('shift/{shift}/asuransi', [AsuransiController::class, 'store'])->name('asuransi.store');
    });

    // LAPORAN
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('rekap-harian',              [RekapHarianController::class,  'index'])->name('rekap-harian');
        Route::get('rekap-harian/export-pdf',   [RekapHarianController::class,  'exportPdf'])->name('rekap-harian.pdf');
        Route::get('rekap-harian/export-excel', [RekapHarianController::class,  'exportExcel'])->name('rekap-harian.excel');

        Route::get('rekap-bulanan',              [RekapBulananController::class, 'index'])->name('rekap-bulanan');
        Route::get('rekap-bulanan/export-pdf',   [RekapBulananController::class, 'exportPdf'])->name('rekap-bulanan.pdf');
        Route::get('rekap-bulanan/export-excel', [RekapBulananController::class, 'exportExcel'])->name('rekap-bulanan.excel');

        Route::get('rekap-tahunan',              [RekapTahunanController::class, 'index'])->name('rekap-tahunan');
        Route::get('rekap-tahunan/export-pdf',   [RekapTahunanController::class, 'exportPdf'])->name('rekap-tahunan.pdf');
        Route::get('rekap-tahunan/export-excel', [RekapTahunanController::class, 'exportExcel'])->name('rekap-tahunan.excel');

        Route::get('klaim-roro',            [KlaimRoroController::class, 'index'])->name('klaim-roro');
        Route::get('klaim-roro/{shift}/pdf',[KlaimRoroController::class, 'exportPdf'])->name('klaim-roro.pdf');

        Route::get('bap',             [BapController::class, 'index'])->name('bap');
        Route::get('bap/{shift}/pdf', [BapController::class, 'exportPdf'])->name('bap.pdf');
    });
});
