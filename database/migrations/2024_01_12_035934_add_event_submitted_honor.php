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
            $table->boolean('sudah_dialokasikan_honor')->default(false)->after('daftar_kegiatan_survei');
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
