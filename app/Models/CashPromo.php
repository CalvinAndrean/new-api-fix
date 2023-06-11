<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashPromo extends Model
{
    use HasFactory;
    protected $table = 'cash_promos';
    protected $primaryKey = 'id_cash_promo';
    protected $fillable = [
        'id_cash_promo',
        'min_topup',
        'min_deposit',
        'bonus'
    ];

    public function deposit_cash_report(){
        return $this->hasMany(DepositCashReport::class, 'id_cash_promo', 'id_cash_promo');
    }
}
