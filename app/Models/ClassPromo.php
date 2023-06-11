<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassPromo extends Model
{
    use HasFactory;
    protected $table = 'class_promos';
    protected $primaryKey = 'id_class_promo';
    protected $fillable = [
        'id_class_promo',
	    'id_class',
        'total_price',
        'amount_deposit',
        'duration',
        'bonus'
    ];

    public function class_details(){
        return $this->belongsTo(ClassDetails::class, 'id_class');
    }

    public function deposit_class_report(){
        return $this->hasMany(DepositClassReport::class, 'id_class_promo', 'id_class_promo');
    }
}