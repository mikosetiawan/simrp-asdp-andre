<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('trip_kapal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shift_operasional')->cascadeOnDelete();
            $table->foreignId('kapal_id')->constrained('kapal');
            $table->foreignId('kapal_pengganti_id')->nullable()->constrained('kapal')->nullOnDelete();
            $table->foreignId('dermaga_id')->constrained('dermaga');
            $table->integer('jumlah_trip')->default(0);
            $table->integer('trip_ke')->default(1)->comment('Nomor urut trip dalam shift');
            $table->time('jam_berangkat')->nullable();
            $table->time('jam_tiba')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['shift_id', 'dermaga_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('trip_kapal'); }
};
