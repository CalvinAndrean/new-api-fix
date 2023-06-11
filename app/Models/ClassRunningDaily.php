<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRunningDaily extends Model
{
    use HasFactory;
    protected $table = 'class_running_dailies';
    protected $primaryKey = 'id_class_running_daily';
    protected $keyType = 'integer';
    protected $fillable = [
        'id_class_running_daily',
        'id_class_running',
        'slot',
        'date',
        'status',
        'start_time',
        'end_time',
    ];

    public function instructor_absent(){
        return $this->hasMany(InstructorAbsent::class, 'id_class_running_daily', 'id_class_running_daily');
    }

    public function class_running(){
        return $this->belongsTo(ClassRunning::class, 'id_class_running');
    }

    public function class_booking(){
        return $this->hasMany(ClassBooking::class, 'id_class_booking', 'id_class_booking');
    }
}
