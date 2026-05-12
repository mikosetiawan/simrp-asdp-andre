<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tagih_pelayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trip_kapal')->cascadeOnDelete();
            $table->foreignId('tarif_id')->constrained('tarif');

            // Penumpang
            $table->integer('jml_pnp_ekb_d')->default(0)->comment('Ekonomi B Dewasa');
            $table->integer('jml_pnp_ekb_l')->default(0)->comment('Ekonomi B Lansia');
            $table->integer('jml_pnp_ekb_a')->default(0)->comment('Ekonomi B Anak');

            // Kendaraan per Golongan
            $table->integer('gol_i')->default(0);
            $table->integer('gol_ii')->default(0);
            $table->integer('gol_iii')->default(0);
            $table->integer('gol_iv_a')->default(0);
            $table->integer('gol_iv_b')->default(0);
            $table->integer('gol_v_a')->default(0);
            $table->integer('gol_v_b')->default(0);
            $table->integer('gol_vi_a')->default(0);
            $table->integer('gol_vi_b')->default(0);
            $table->integer('gol_vii')->default(0);
            $table->integer('gol_viii')->default(0);
            $table->integer('gol_ix')->default(0);

            // Totals (calculated)
            $table->integer('total_penumpang')
                ->storedAs('jml_pnp_ekb_d + jml_pnp_ekb_l + jml_pnp_ekb_a');

            $table->integer('total_kendaraan')
                ->storedAs('gol_i + gol_ii + gol_iii + gol_iv_a + gol_iv_b + gol_v_a + gol_v_b + gol_vi_a + gol_vi_b + gol_vii + gol_viii + gol_ix');

            // Pendapatan (Rp)
            $table->bigInteger('pendapatan_penumpang')->default(0);
            $table->bigInteger('pendapatan_kendaraan')->default(0);
            $table->bigInteger('total_pendapatan')->default(0);

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tagih_pelayaran');
    }
};
