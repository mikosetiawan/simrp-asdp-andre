<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Stok Tiket
        Schema::create('stok_tiket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regu_id')->constrained('regu');
            $table->date('tanggal');
            $table->string('jenis_tiket', 50)->comment('EKB-D, EKB-L, GOL-I, GOL-II, dst');
            $table->integer('stok_awal')->default(0);
            $table->integer('terjual')->default(0);
            $table->integer('sisa_stok')->default(0);
            $table->integer('no_seri_awal')->nullable();
            $table->integer('no_seri_akhir')->nullable();
            $table->timestamps();

            $table->index(['regu_id', 'tanggal', 'jenis_tiket']);
        });

        // Penjualan Tiket
        Schema::create('penjualan_tiket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shift_operasional')->cascadeOnDelete();
            $table->string('pos_penjualan', 50)->comment('Koordinator/Ruang Tunggu/Toll Gate I/Toll Gate II/Shelter');

            // Per jenis tiket / golongan
            $table->integer('pnp_ekb_d')->default(0);
            $table->integer('pnp_ekb_l')->default(0);
            $table->integer('pnp_ekb_a')->default(0);
            $table->integer('knd_gol_i')->default(0);
            $table->integer('knd_gol_ii')->default(0);
            $table->integer('knd_gol_iii')->default(0);
            $table->integer('knd_gol_iv_a')->default(0);
            $table->integer('knd_gol_iv_b')->default(0);
            $table->integer('knd_gol_v_a')->default(0);
            $table->integer('knd_gol_v_b')->default(0);
            $table->integer('knd_gol_vi_a')->default(0);
            $table->integer('knd_gol_vi_b')->default(0);
            $table->integer('knd_gol_vii')->default(0);
            $table->integer('knd_gol_viii')->default(0);
            $table->integer('knd_gol_ix')->default(0);

            $table->bigInteger('total_pendapatan_penjualan')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Limpahan Tiket
        Schema::create('limpahan_tiket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shift_operasional')->cascadeOnDelete();
            $table->string('jenis_tiket', 30);
            $table->integer('terjual')->default(0);
            $table->integer('tertagih_regu1')->default(0);
            $table->integer('tertagih_regu2')->default(0);
            $table->integer('tertagih_regu3')->default(0);
            $table->integer('dilimpahkan')->default(0)->comment('terjual - tertagih semua regu');
            $table->foreignId('dilimpahkan_ke_regu_id')->nullable()->constrained('regu')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Manifest Penumpang
        Schema::create('manifest_penumpang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trip_kapal')->cascadeOnDelete();
            $table->integer('pnp_dalam_gol_iv_a')->default(0);
            $table->integer('pnp_dalam_gol_iv_b')->default(0);
            $table->integer('pnp_dalam_gol_v_a')->default(0);
            $table->integer('pnp_dalam_gol_v_b')->default(0);
            $table->integer('pnp_dalam_gol_vi_a')->default(0);
            $table->integer('pnp_dalam_gol_vi_b')->default(0);
            $table->integer('pnp_dalam_gol_vii')->default(0);
            $table->integer('pnp_dalam_gol_viii')->default(0);
            $table->integer('pnp_dalam_gol_ix')->default(0);
            $table->integer('total_pnp_manifest')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Asuransi Shift (Tagih06)
        Schema::create('asuransi_shift', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shift_operasional')->cascadeOnDelete();
            // Jasa Raharja (JR)
            $table->integer('jr_pnp_dewasa')->default(0);
            $table->integer('jr_pnp_lansia')->default(0);
            $table->integer('jr_pnp_anak')->default(0);
            $table->integer('jr_knd_gol_i')->default(0);
            $table->integer('jr_knd_gol_ii')->default(0);
            $table->integer('jr_knd_gol_iii')->default(0);
            $table->integer('jr_knd_gol_iv')->default(0);
            $table->integer('jr_knd_gol_v')->default(0);
            $table->integer('jr_knd_gol_vi')->default(0);
            $table->integer('jr_knd_gol_vii')->default(0);
            $table->integer('jr_knd_gol_viii')->default(0);
            $table->integer('jr_knd_gol_ix')->default(0);
            $table->bigInteger('total_jr')->default(0);
            // Jasa Penumpang (JP)
            $table->integer('jp_pnp_dewasa')->default(0);
            $table->integer('jp_pnp_lansia')->default(0);
            $table->bigInteger('total_jp')->default(0);
            $table->bigInteger('total_asuransi')->default(0);
            $table->timestamps();
        });

        // Rekap ADM (Tagih04, Tagih05)
        Schema::create('rekap_adm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shift_operasional')->cascadeOnDelete();
            $table->foreignId('kapal_id')->constrained('kapal');
            $table->bigInteger('setoran_penumpang')->default(0);
            $table->bigInteger('setoran_kendaraan')->default(0);
            $table->bigInteger('total_setoran')->default(0);
            $table->string('no_berita_acara', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rekap_adm');
        Schema::dropIfExists('asuransi_shift');
        Schema::dropIfExists('manifest_penumpang');
        Schema::dropIfExists('limpahan_tiket');
        Schema::dropIfExists('penjualan_tiket');
        Schema::dropIfExists('stok_tiket');
    }
};
