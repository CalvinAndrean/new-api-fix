<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymBooking extends Model
{
    use HasFactory;
    protected $table = 'gym_bookings';
    protected $primaryKey = 'id_gym_booking';
    protected $keyType = 'string';
    protected $fillable = [
        'id_gym_booking',
        'id_gym_session',
        'id_member',
        'datetime_booking',
        'datetime_presensi'
    ];

    public function gym_session(){
        return $this->belongsTo(GymSession::class, 'id_gym_session');
    }

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function gym_presensi(){
        return $this->belongsTo(GymPresensi::class, 'id_gym_presensi');
    }
}