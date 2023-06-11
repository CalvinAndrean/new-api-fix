<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymPresensi extends Model
{
    use HasFactory;
    protected $table = 'gym_presensis';
    protected $primaryKey = 'id_gym_presensi';
    protected $fillable = [
        'id_gym_presensi',
        'id_gym_booking',
        'datetime_presensi'
    ];

    public function gym_booking(){
        return $this->hasOne(GymPresensi::class, 'id_gym_presensi', 'id_gym_presensi');
    }
}
