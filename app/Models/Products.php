<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $guarded = [];

    public function category(){
        return $this->belongsTo(Categorys::class, 'id_category', 'id');
    }
    public function order_items(){
        return $this->hasMany(Order_Items::class, 'id_product', 'id');
    }

    public function Product_logs(){
        return $this->hasMany(Product_Logs::class, 'id_product', 'id');
    }
}
