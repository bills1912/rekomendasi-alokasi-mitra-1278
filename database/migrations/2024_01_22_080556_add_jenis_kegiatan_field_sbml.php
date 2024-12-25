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
        Schema::table('daftar_sbml_kegiatan', function (Blueprint $table) {
            $table->string('jenis_pembayaran_kegiatan')->nullable()->after('jenis_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_sbml_kegiatan', function (Blueprint $table) {
            //
        });
    }
};
