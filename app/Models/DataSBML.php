<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSBML extends Model
{
    use HasFactory;

    protected $table = 'daftar_sbml_kegiatan';

    protected $fillable = [
        'id_kegiatan',
        'nama_kegiatan',
        'kegiatan_dimulai',
        'kegiatan_berakhir',
        'jenis_kegiatan',
        'jenis_pembayaran_kegiatan',
        'nominal_sbml',
    ];
}
