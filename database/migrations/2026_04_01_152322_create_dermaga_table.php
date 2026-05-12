<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dermaga', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dermaga', 50)->comment('Dermaga I - VII');
            $table->string('kode_dermaga', 10)->unique()->comment('D1-D7');
            $table->decimal('tarif_jsn_per_trip', 15, 2)->default(0)->comment('Jasa Sandar per trip');
            $table->decimal('tarif_engker_per_trip', 15, 2)->default(0);
            $table->integer('kapasitas_trip_per_hari')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('dermaga'); }
};
