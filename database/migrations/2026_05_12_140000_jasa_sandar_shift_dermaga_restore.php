<?php

use App\Models\Dermaga;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('jasa_sandar', 'shift_id')) {
            return;
        }

        Schema::table('jasa_sandar', function (Blueprint $table) {
            $table->foreignId('shift_id')->nullable()->after('id')->constrained('shift_operasional')->cascadeOnDelete();
            $table->foreignId('dermaga_id')->nullable()->after('shift_id')->constrained('dermaga');
        });

        if (Schema::hasColumn('jasa_sandar', 'trip_kapal_id')) {
            DB::statement('
                UPDATE jasa_sandar js
                INNER JOIN trip_kapal t ON t.id = js.trip_kapal_id
                SET js.shift_id = t.shift_id, js.dermaga_id = t.dermaga_id
            ');
        }

        DB::table('jasa_sandar')->whereNull('shift_id')->delete();

        $grouped = DB::table('jasa_sandar')
            ->selectRaw('shift_id, dermaga_id, SUM(call_sandar) as sum_call, SUM(jumlah_trip) as sum_jml')
            ->groupBy('shift_id', 'dermaga_id')
            ->get();

        DB::table('jasa_sandar')->delete();

        $now = now();
        foreach ($grouped as $g) {
            $dermaga = Dermaga::find($g->dermaga_id);
            if (! $dermaga) {
                continue;
            }
            $call = (int) $g->sum_call;
            $jml  = (int) $g->sum_jml;
            if ($call === 0 && $jml === 0) {
                continue;
            }
            $jsn    = (int) round($jml * (float) $dermaga->tarif_jsn_per_trip);
            $engker = (int) round($call * (float) $dermaga->tarif_engker_per_trip);

            DB::table('jasa_sandar')->insert([
                'shift_id'              => $g->shift_id,
                'dermaga_id'            => $g->dermaga_id,
                'call_sandar'           => $call,
                'jumlah_trip'           => $jml,
                'tarif_jsn_per_trip'    => $dermaga->tarif_jsn_per_trip,
                'tarif_engker_per_trip' => $dermaga->tarif_engker_per_trip,
                'pendapatan_jsn'        => $jsn,
                'pendapatan_engker'     => $engker,
                'total_jasa_dermaga'    => $jsn + $engker,
                'keterangan'            => null,
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);
        }

        if (Schema::hasColumn('jasa_sandar', 'trip_kapal_id')) {
            Schema::table('jasa_sandar', function (Blueprint $table) {
                $table->dropForeign(['trip_kapal_id']);
            });
            Schema::table('jasa_sandar', function (Blueprint $table) {
                $table->dropColumn('trip_kapal_id');
            });
        }

        Schema::table('jasa_sandar', function (Blueprint $table) {
            $table->unique(['shift_id', 'dermaga_id']);
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE jasa_sandar MODIFY shift_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE jasa_sandar MODIFY dermaga_id BIGINT UNSIGNED NOT NULL');
        }
    }

    public function down(): void
    {
        throw new \RuntimeException('Restore jasa_sandar shift+dermaga migration cannot be reversed safely.');
    }
};
