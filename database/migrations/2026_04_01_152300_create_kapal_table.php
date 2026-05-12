<?php
// ================================================================
// SIMRP ASDP Merak - Database Migrations
// File: database/migrations/
// ================================================================

// ---- 001_create_kapal_table.php ----
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kapal', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kapal', 100)->unique();
            $table->integer('grt')->comment('Gross Registered Tonnage');
            $table->enum('jenis', ['roro', 'lct'])->default('roro');
            $table->string('kode_kapal', 20)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kapal'); }
};
