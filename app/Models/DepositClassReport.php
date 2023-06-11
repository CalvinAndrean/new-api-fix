<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositClassReport extends Model
{
    use HasFactory;
    protected $table = 'deposit_class_reports';
    protected $primaryKey = 'report_number_class_deposit';
    protected $keyType = 'string';
    protected $fillable = [
        'report_number_class_deposit',
        'id_pegawai',
        'id_member',
        'id_class_promo',
        'total_deposit',
        'total_price',
        'expired_date',
        'datetime'
    ];

    public function pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function class_promo(){
        return $this->belongsTo(ClassPromo::class, 'id_class_promo');
    }
}
