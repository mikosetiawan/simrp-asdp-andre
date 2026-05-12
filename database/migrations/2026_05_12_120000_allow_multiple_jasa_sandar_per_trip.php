<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jasa_sandar', function (Blueprint $table) {
            $table->dropForeign(['trip_kapal_id']);
        });

        Schema::table('jasa_sandar', function (Blueprint $table) {
            $table->dropUnique(['trip_kapal_id']);
        });

        Schema::table('jasa_sandar', function (Blueprint $table) {
            $table->foreign('trip_kapal_id')->references('id')->on('trip_kapal')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jasa_sandar', function (Blueprint $table) {
            $table->dropForeign(['trip_kapal_id']);
        });

        Schema::table('jasa_sandar', function (Blueprint $table) {
            $table->unique('trip_kapal_id');
        });

        Schema::table('jasa_sandar', function (Blueprint $table) {
            $table->foreign('trip_kapal_id')->references('id')->on('trip_kapal')->cascadeOnDelete();
        });
    }
};
