<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateHonor extends Model
{
    use HasFactory;

    protected $table = 'rate_honor_mitra';

    protected $fillable = [
        'nama_mitra',
        'alamat_mitra',
        'kegiatan',
        'jenis_pembayaran_mitra',
        'volume_pembayaran_mitra',
        'jenis_pekerjaan',
        'honor',
        'id_mitra',
    ];
}
