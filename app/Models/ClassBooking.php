<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassBooking extends Model
{
    use HasFactory;
    protected $table = 'class_bookings';
    protected $primaryKey = 'id_class_booking';
    protected $fillable = [
        'id_class_booking',
        'id_class_running_daily',
        'id_member',
        'datetime',
        'payment_type',
        'datetime_presensi'
    ];

    public function presensi_class_deposit(){
        return $this->belongsTo(PresensiClassDeposit::class, 'id_presensi_class_deposit');
    }

    public function class_running_daily(){
        return $this->belongsTo(ClassRunningDaily::class, 'id_class_running_daily');
    }

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function class_presensi(){
        return $this->belongsTo(ClassPresensi::class, 'id_class_presensi');
    }
}
