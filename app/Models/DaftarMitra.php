<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarMitra extends Model
{
    use HasFactory;
    protected $table = 'daftar_mitra_final';

    protected $fillable = [
        'nama',
        'posisi', 
        'alamat_detail',
        'jenis_kelamin',
        'longitude',
        'latitude',
    ];
}
