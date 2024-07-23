<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;
     protected $table = 'payments';
    protected $guarded = [];

    public function order(){
        return $this->hasMany(Orders::class, 'id_payment_metode', 'id');
    }
}
