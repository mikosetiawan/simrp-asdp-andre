<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('regu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_regu', 20)->comment('Regu I, Regu II, Regu III');
            $table->string('kode_regu', 5)->unique()->comment('R1, R2, R3');
            $table->text('keterangan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('regu'); }
};
