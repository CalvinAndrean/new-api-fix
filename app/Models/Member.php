<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $table = 'members';
    protected $primaryKey = 'id_member';
    protected $keyType = 'string';
    protected $fillable = [
        'id_member',
        'email',
        'password',
        'birth_date',
        'address',
        'phone_number',
        'fullname',
        'cash_deposit',
        'expired_date'
    ];

    public function class_booking(){
        return $this->hasMany(ClassBooking::class, 'id_member', 'id_member');
    }

    public function deposit_class_report(){
        return $this->hasMany(DepositClassReport::class, 'id_member', 'id_member');
    }

    public function deposit_class(){
        return $this->hasMany(DepositClass::class, 'id_member', 'id_member');
    }

    public function gym_booking(){
        return $this->hasMany(GymBooking::class, 'id_member', 'id_member');
    }

    public function activation_report(){
        return $this->hasMany(ActivationReport::class, 'report_number_activation', 'id_member');
    }

    public function deposit_cash_report(){
        return $this->hasMany(DepositCashReport::class, 'id_member', 'id_member');
    }
}
