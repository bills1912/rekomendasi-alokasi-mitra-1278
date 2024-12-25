<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlokasiMitraSurvei extends Model
{
    use HasFactory;

    protected $table = 'mitra_survei';

    protected $fillable = [
        'id',
        'id_desa',
        'nama_mitra',
        'alamat_mitra',
        'jarak_mitra',
        'status_mitra',
        'kegiatan_survei',
        'idbs_teralokasi',
    ];
}
