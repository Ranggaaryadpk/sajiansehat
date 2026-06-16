<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekomendasiSimpan extends Model
{
    protected $table = 'rekomendasi_simpans';
    protected $fillable = ['user_id', 'durasi', 'analisis', 'resep'];

    protected $casts = [
        'analisis' => 'array',
        'resep' => 'array',
    ];
}