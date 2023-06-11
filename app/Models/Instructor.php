<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;
    protected $table = 'instructors';
    protected $primaryKey = 'id_instructor';
    protected $keyType = 'string';
    protected $fillable = [
        'id_instructor',
        'email',
        'password',
        'fullname',
        'birth_date',
        'address',
        'phone_number',
        'total_late'
    ];

    public function class_running(){
        return $this->hasMany(ClassRunning::class, 'id_class_running', 'id_class_running');
    }

    // public function class_daily(){
    //     return $this->hasManyThrough('App\ClassRunningDaily', 'App\ClassRunning', 'id_instructor', 'id_class_running', 'id_instructor');
    // }

    public function instructor_presensi(){
        return $this->belongsTo(InstructorPresensi::class, 'id_instructor_presensi');
    }

    public function instructor_absent(){
        return $this->hasMany(InstructorAbsent::class, 'id_instructor', 'id_instructor');
    }

    public function substitute_instructor(){
        return $this->belongsTo(InstructorPresensi::class, 'id_substitute_instructor');
    }
}
