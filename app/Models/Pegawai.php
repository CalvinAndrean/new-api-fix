<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawais';
    protected $primaryKey = 'id_pegawai';
    protected $keyType = 'string';
    protected $fillable = [
        'id_pegawai',
        'email',
        'password',
        'fullname',
        'address',
        'phone_number',
        'role'
    ];

    public function deposit_class_report(){
        return $this->hasMany(DepositClassReport::class, 'id_pegawai', 'id_pegawai');
    }

    public function activation_report(){
        return $this->hasMany(ActivationReport::class, 'report_number_activation', 'id_pegawai');
    }

    public function deposit_cash_report(){
        return $this->hasMany(DepositCashReport::class, 'id_pegawai', 'id_pegawai');
    }
}