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
        Schema::table('rate_honor_mitra', function (Blueprint $table) {
            $table->string('jenis_pembayaran_mitra')->nullable()->after('jenis_pekerjaan');
            $table->integer('volume_pembayaran_mitra')->nullable()->after('jenis_pembayaran_mitra');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rate_honor_mitra', function (Blueprint $table) {
            //
        });
    }
};
