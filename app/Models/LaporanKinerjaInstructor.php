<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKinerjaInstructor extends Model
{
    use HasFactory;
    protected $table = 'laporan_kinerja_instructor';
    protected $primaryKey = 'id_laporan_kinerja_instructor';
    protected $keyType = 'integer';
    protected $fillable = [
        'bulan',
        'tahun',
    ];
}
