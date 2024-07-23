<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
     protected $table = 'orders';
    protected $guarded = [];

    public function order_items(){
        return $this->hasMany(Order_Items::class, 'id_order', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function payment_metode(){
        return $this->belongsTo(Payments::class, 'id_payment_metode', 'id');
    }

    public function product_logs(){
        return $this->hasMany(Product_Logs::class, 'id_order', 'id');
    }
}
