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
        Schema::create('rate_honor_mitra', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_mitra');
            $table->string('nama_mitra');
            $table->string('alamat_mitra');
            $table->string('kegiatan');
            $table->double('honor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_honor_mitra');
    }
};
