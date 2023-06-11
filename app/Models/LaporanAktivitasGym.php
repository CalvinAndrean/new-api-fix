<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanAktivitasGym extends Model
{
    use HasFactory;
    protected $table = 'laporan_aktivitas_gym';
    protected $primaryKey = 'id_laporan_aktivitas_gym';
    protected $fillable = [
        'bulan',
        'tahun'
    ];
}
