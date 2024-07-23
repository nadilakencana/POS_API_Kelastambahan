<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorys extends Model
{
    use HasFactory;

    protected $table = 'categorys';
    protected $guarded = [];

    public function product(){
        return $this->hasMany(Products::class, 'id_category', 'id');
    }
}
