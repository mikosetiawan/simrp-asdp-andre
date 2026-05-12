<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shift_operasional', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('regu_id')->constrained('regu');
            $table->string('nama_shift', 30)->comment('Pagi/Siang/Malam');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->foreignId('supervisi_id')->constrained('users')->comment('Supervisi Usaha penanggung jawab');
            $table->foreignId('kolektor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_awal_dinas')->nullable();
            $table->date('tanggal_akhir_dinas')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');
            $table->text('catatan')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['tanggal', 'regu_id']);
            $table->index('status');
        });
    }
    public function down(): void { Schema::dropIfExists('shift_operasional'); }
};
