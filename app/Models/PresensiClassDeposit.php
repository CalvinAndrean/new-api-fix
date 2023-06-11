<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiClassDeposit extends Model
{
    use HasFactory;
    protected $table = 'presensi_class_deposit';
    protected $primaryKey = 'id_presensi_class_deposit';
    protected $fillable = [
        'id_presensi_class_deposit',
        'id_class_booking',
        'datetime',
        'remaining_deposit',
        'expired_date'
    ];

    public function class_booking(){
        return $this->hasMany(PresensiClassDeposit::class, 'id_presensi_class_deposit', 'id_presensi_class_deposit');
    }
}
