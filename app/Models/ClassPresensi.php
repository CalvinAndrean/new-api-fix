<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassPresensi extends Model
{
    use HasFactory;
    protected $table = 'class_presensis';
    protected $primaryKey = 'id_class_presensi';
    protected $fillable = [
        'id_class_presensi',
        'id_class_booking',
        'datetime_presensi',
        'remaining_deposit'
    ];

    public function class_booking(){
        return $this->hasOne(ClassPresensi::class, 'id_class_presensi', 'id_class_presensi');
    }
}
