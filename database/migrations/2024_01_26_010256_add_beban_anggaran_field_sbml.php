<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('list_kegiatan_survei', function (Blueprint $table) {
            $table->string('kode_beban_anggaran')->nullable()->after('periode_pencairan_honor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_kegiatan_survei', function (Blueprint $table) {
            //
        });
    }
};
