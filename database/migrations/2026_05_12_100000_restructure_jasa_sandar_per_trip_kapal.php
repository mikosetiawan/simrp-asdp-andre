<?php

use App\Models\JasaSandar;
use App\Models\TripKapal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jasa_sandar', function (Blueprint $table) {
            $table->foreignId('trip_kapal_id')
                ->nullable()
                ->after('id')
                ->constrained('trip_kapal')
                ->cascadeOnDelete();
        });

        foreach (JasaSandar::query()->cursor() as $js) {
            $trip = TripKapal::query()
                ->where('shift_id', $js->shift_id)
                ->where('dermaga_id', $js->dermaga_id)
                ->orderBy('id')
                ->first();
            if ($trip) {
                DB::table('jasa_sandar')->where('id', $js->id)->update(['trip_kapal_id' => $trip->id]);
            } else {
                DB::table('jasa_sandar')->where('id', $js->id)->delete();
            }
        }

        DB::table('jasa_sandar')->whereNull('trip_kapal_id')->delete();

        Schema::table('jasa_sandar', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropForeign(['dermaga_id']);
        });

        Schema::table('jasa_sandar', function (Blueprint $table) {
            $table->dropUnique(['shift_id', 'dermaga_id']);
            $table->dropColumn(['shift_id', 'dermaga_id']);
        });

        Schema::table('jasa_sandar', function (Blueprint $table) {
            $table->unique('trip_kapal_id');
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE jasa_sandar MODIFY trip_kapal_id BIGINT UNSIGNED NOT NULL');
        }
    }

    public function down(): void
    {
        throw new \RuntimeException('jasa_sandar per-trip migration cannot be reversed without data loss.');
    }
};
