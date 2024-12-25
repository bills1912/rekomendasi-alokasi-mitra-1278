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
            $table->string('waktu_mulai')->nullable()->after('daftar_kegiatan_survei');
            $table->string('waktu_berakhir')->nullable()->after('waktu_mulai');
            $table->string('jenis_kegiatan')->nullable()->after('waktu_berakhir');
            $table->string('jenis_pembayaran')->nullable()->after('jenis_kegiatan');
            $table->string('jumlah_satuan')->nullable()->after('jenis_pembayaran');
            $table->string('nominal_per_satuan')->nullable()->after('jumlah_satuan');
            $table->string('total_honor')->nullable()->after('nominal_per_satuan');
            $table->string('periode_pencairan_honor')->nullable()->after('total_honor');
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
