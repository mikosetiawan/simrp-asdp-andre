<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tarif', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tarif', 100)->comment('Nama periode tarif');
            $table->date('berlaku_mulai')->comment('Tanggal mulai berlaku');
            $table->date('berlaku_sampai')->nullable()->comment('Tanggal berakhir, null = masih berlaku');

            // Tarif Penumpang (Rp per orang)
            $table->integer('ekb_dewasa')->default(84800)->comment('Ekonomi B Dewasa');
            $table->integer('ekb_lansia')->default(42400)->comment('Ekonomi B Lansia (50%)');
            $table->integer('ekb_anak')->default(0)->comment('Ekonomi B Anak (gratis di bawah 5th)');

            // Tarif Kendaraan (Rp per unit) - sesuai Permenhub
            $table->integer('gol_i')->default(29600)->comment('Sepeda');
            $table->integer('gol_ii')->default(53000)->comment('Sepeda Motor < 500cc');
            $table->integer('gol_iii')->default(147200)->comment('Sepeda Motor >= 500cc');
            $table->integer('gol_iv_a')->default(749128)->comment('Gol IV Penumpang');
            $table->integer('gol_iv_b')->default(763628)->comment('Gol IV Barang');
            $table->integer('gol_v_a')->default(1033048)->comment('Gol V Penumpang');
            $table->integer('gol_v_b')->default(1057048)->comment('Gol V Barang');
            $table->integer('gol_vi_a')->default(1454728)->comment('Gol VI Penumpang');
            $table->integer('gol_vi_b')->default(1479928)->comment('Gol VI Barang');
            $table->integer('gol_vii')->default(2009748)->comment('Gol VII');
            $table->integer('gol_viii')->default(2735748)->comment('Gol VIII');
            $table->integer('gol_ix')->default(3632148)->comment('Gol IX');

            // Tarif Asuransi (Jasa Raharja & Jasa Penumpang)
            $table->integer('asuransi_jr_pnp')->default(3600);
            $table->integer('asuransi_jp_pnp')->default(1400);

            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tarif'); }
};
