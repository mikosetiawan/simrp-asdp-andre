<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jasa_sandar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shift_operasional')->cascadeOnDelete();
            $table->foreignId('dermaga_id')->constrained('dermaga');
            $table->integer('call_sandar')->default(0)->comment('Jumlah call sandar');
            $table->integer('jumlah_trip')->default(0);
            $table->decimal('tarif_jsn_per_trip', 15, 2)->default(0)->comment('Snapshot tarif saat input');
            $table->decimal('tarif_engker_per_trip', 15, 2)->default(0);
            $table->bigInteger('pendapatan_jsn')->default(0)->comment('Jasa Sandar');
            $table->bigInteger('pendapatan_engker')->default(0)->comment('Engker/Tunda');
            $table->bigInteger('total_jasa_dermaga')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['shift_id', 'dermaga_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('jasa_sandar'); }
};
