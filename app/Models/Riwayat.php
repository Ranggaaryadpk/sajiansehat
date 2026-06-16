<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model {
    protected $table = 'riwayat_rekomendasi';
    protected $fillable = ['user_id', 'tipe_masakan', 'kondisi_tubuh', 'nama_makanan', 'resep_modifikasi'];
}