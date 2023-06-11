<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRunning extends Model
{
    use HasFactory;
    protected $table = 'class_runnings';
    protected $primaryKey = 'id_class_running';
    protected $keyType = 'integer';
    protected $fillable = [
        'id_class_running',
        'id_instructor',
        'id_class',
        'start_time',
        'end_time',
        'day',
        'slot'
    ];

    public function class_booking(){
        return $this->belongsTo(ClassBooking::class, 'id_class_booking');
    }

    public function class_running_daily(){
        return $this->hasMany(ClassRunningDaily::class, 'id_class_running', 'id_class_running');
    }

    public function instructor(){
        return $this->belongsTo(Instructor::class, 'id_instructor');
        // id_class_running switch ke id_instructor
    }

    public function class_details(){
        return $this->belongsTo(ClassDetails::class, 'id_class');
    }
}
