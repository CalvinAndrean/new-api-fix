<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymSession extends Model
{
    use HasFactory;
    protected $table = 'gym_sessions';
    protected $primaryKey = 'id_gym_session';
    protected $fillable = [
        'id_gym_session',
        'date',
        'start_time',
        'end_time',
        'slot'
    ];

    public function gym_booking(){
        return $this->hasMany(GymBooking::class, 'id_gym_session', 'id_gym_session');
    }
}