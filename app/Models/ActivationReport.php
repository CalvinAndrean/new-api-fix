<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivationReport extends Model
{
    use HasFactory;
    protected $table = 'activation_reports';
    protected $primaryKey = 'report_number_activation';
    protected $keyType = 'string';
    protected $fillable = [
        'report_number_activation',
        'id_member',
        'id_pegawai',
        'datetime',
        'expired_date'
    ];

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}