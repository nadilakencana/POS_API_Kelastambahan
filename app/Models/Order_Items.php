<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Items extends Model
{
    use HasFactory;
    protected $table = 'order_items';
    protected $guarded = [];

    public function order(){
        return $this->belongsTo(Orders::class, 'id_order', 'id');
    }
    public function product(){
        return $this->belongsTo(Products::class, 'id_product', 'id');
    }
    public function users(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
