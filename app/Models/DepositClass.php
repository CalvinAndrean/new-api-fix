<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositClass extends Model
{
    use HasFactory;
    protected $table = 'deposit_classes';
    protected $primaryKey = 'id_deposit_class';
    protected $fillable = [
        'id_deposit_class',
        'id_member',
        'id_class',
        'total_amount',
        'expired_date'
    ];

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function class_details(){
        return $this->belongsTo(ClassDetails::class, 'id_class');
    }
}
