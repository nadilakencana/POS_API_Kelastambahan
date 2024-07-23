<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product__logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_order_item');
            $table->unsignedBigInteger('id_product');
            $table->unsignedBigInteger('id_order');
            $table->foreign('id_order_item')->references('id')->on('order__items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_product')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_order')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product__logs');
    }
};
