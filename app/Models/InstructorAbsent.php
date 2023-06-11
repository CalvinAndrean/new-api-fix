<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorAbsent extends Model
{
    use HasFactory;
    protected $table = 'instructor_absents';
    protected $primaryKey = 'id_absent';
    protected $fillable = [
        'id_absent',
        'id_instructor',
        'id_class_running_daily',
        'reason',
        'id_substitute_instructor',
        'is_confirmed'
    ];

    public function instructor(){
        return $this->belongsTo(Instructor::class, 'id_instructor');
    }

    public function substitute_instructor(){
        return $this->belongsTo(Instructor::class, 'id_instructor');
    }

    public function class_running_daily(){
        return $this->belongsTo(ClassRunningDaily::class, 'id_class_running_daily');
    }
}
