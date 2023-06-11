<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositCashReport extends Model
{
    use HasFactory;
    protected $table = 'deposit_cash_reports';
    protected $primaryKey = 'report_number_deposit_cash';
    protected $keyType = 'string';
    protected $fillable = [
        'report_number_deposit_cash',
        'id_member',
        'id_pegawai',
        'id_cash_promo',
        'date_deposit',
        'amount_deposit',
        'bonus_deposit',
        'remaining_deposit',
        'total_deposit'
    ];


    
    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function cash_promo(){
        return $this->belongsTo(CashPromo::class, 'id_cash_promo');
    }
}
