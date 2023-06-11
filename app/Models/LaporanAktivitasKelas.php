<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanAktivitasKelas extends Model
{
    use HasFactory;
    protected $table = 'laporan_aktivitas_kelas';
    protected $primaryKey = 'id_laporan_aktivitas_kelas';
    protected $fillable = [
        'bulan',
        'tahun'
    ];
}
