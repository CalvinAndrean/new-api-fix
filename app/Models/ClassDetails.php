<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassDetails extends Model
{
    use HasFactory;
    protected $table = 'class_details';
    protected $primaryKey = 'id_class';
    protected $keyType = 'integer';
    protected $fillable = [
        'id_class',
        'class_name',
        'price'
    ];

    public function class_running(){
        return $this->hasMany(ClassRunning::class, 'id_class', 'id_class');
    }

    public function class_promo(){
        return $this->hasMany(ClassPromo::class, 'id_class', 'id_class');
    }

    public function deposit_class(){
        return $this->hasMany(DepositClass::class, 'id_deposit_class');
    }
}
