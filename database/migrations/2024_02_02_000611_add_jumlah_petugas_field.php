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
            $table->integer('jumlah_petugas_kegiatan')->nullable()->after('total_anggaran');
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
