<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategorysController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SalesController;

Route::middleware([EnsureFrontendRequestsAreStateful::class])->group(function () {
    // customer frontend
    // product
    Route::controller(ProductController::class)->group(function () {
        Route::get('product', 'getProduct_data');
        Route::get('product-detail/{slug}', 'getProduct_Detail_customer');
        Route::get('product-category/{slug}', 'getProduct_byCategory');
    });

    // category
    Route::controller(CategorysController::class)->group(function () {
        Route::get('category', 'getCategory');
    });

    // Sales

    Route::controller(SalesController::class)->group(function () {
        Route::post('order', 'order');
        Route::get('Detail-Orde/{code_orde}', 'detail_order');
    });


    // admin
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum', 'ensure.auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        // product
        Route::controller(ProductController::class)->group(function () {
            Route::get('data-product/admin', 'getProduct_data');
            Route::post('create-product', 'CreateDataProdut');
            Route::post('update-product/{slug}', 'UpdateDataProdut');
            Route::delete('delete-product/{slug}', 'deleteProduct');
        });
        // category

        Route::controller(CategorysController::class)->group(function () {
            Route::get('data-category', 'getCategory');
            Route::post('create-category', 'CreateDataCategory');
            Route::put('update-category/{slug}', 'UpdateDataCategory');
            Route::delete('delete-category/{slug}', 'DeleteDataCategory');
        });

        // Sales
        Route::controller(SalesController::class)->group(function () {
            Route::post('order/admin', 'order');
            Route::get('Detail-Orde/admin/{code_orde}', 'detail_order');
            Route::post('payment/admin/{code_orde}', 'payment_order');
            Route::post('modify/order', 'modify_order');
            Route::post('delete-items/order/admin', 'modify_delete_item');
        });

        //payment methode module
        Route::controller(PaymentController::class)->group(function () {
            Route::get('payment', 'getPayment');
            Route::post('payment/create', 'payment');
            Route::delete('payment/{id}', 'deletePayment');
            Route::put('payment/update/{id}', 'updatePayment');
        });
    });
});
