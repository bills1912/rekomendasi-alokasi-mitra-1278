<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMStatus extends Model
{
    use HasFactory;

    protected $table = 'sm_status';

    protected $fillable = [
        'jenis_sm'
    ];
}
