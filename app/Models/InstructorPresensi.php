<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorPresensi extends Model
{
    use HasFactory;
    protected $table = 'instructor_presensis';
    protected $primaryKey = 'id_presensi_instructor';
    protected $fillable = [
        'id_presensi_instructor',
        'id_instructor',
        'start_class',
        'end_class',
        'id_class_running_daily',
        'is_presensi'
    ];

    public function instructor(){
        return $this->hasMany(InstructorPresensi::class, 'id_presensi_instructor', 'id_presensi_instructor');
    }
}
