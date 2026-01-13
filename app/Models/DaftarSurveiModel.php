<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarSurveiModel extends Model
{
    use HasFactory;

    protected $table = 'list_kegiatan_survei';

    protected $fillable = [
        'daftar_kegiatan_survei',
        'waktu_mulai',
        'waktu_berakhir',
        'jenis_kegiatan',
        'jenis_pembayaran',
        'jumlah_satuan',
        'nominal_per_satuan',
        'nominal_per_satuan_pml',
        'nominal_per_satuan_pengolahan',
        'total_anggaran',
        'jumlah_petugas_kegiatan',
        'periode_pencairan_honor',
        'kode_beban_anggaran',
        'sudah_dialokasikan_honor',
    ];
}
