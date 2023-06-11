<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPendapatanTahunan extends Model
{
    use HasFactory;
    protected $table = 'laporan_pendapatan_tahunan';
    protected $primaryKey = 'id_laporan_pendapatan_tahunan';
    protected $fillable = [
        'tahun'
    ];
}
