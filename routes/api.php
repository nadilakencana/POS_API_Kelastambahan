<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategorysController;

Route::middleware([EnsureFrontendRequestsAreStateful::class])->group(function () {
    // customer frontend
    // product
    Route::controller(ProductController::class)->group(function () {
        Route::get('product', 'getProductCustomer');
        Route::get('product-detail/{slug}', 'getProduct_Detail_customer');
        Route::get('product-category/{slug}', 'getProduct_byCategory');
    });

    // category
    Route::controller(CategorysController::class)->group(function () {
        Route::get('category', 'getCategory');
    });

    // admin
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum', 'ensure.auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        // product
        Route::controller(ProductController::class)->group(function () {
            Route::get('data-product', 'getProduct_data');
            Route::post('create-product', 'CreateDataProdut');
            Route::post('update-product/{slug}', 'UpdateDataProdut');
            Route::delete('delete-product/{slug}', 'deleteProduct');
        });
        // category

        Route::controller(CategorysController::class)->group(function () {
            Route::get('data-category', 'getCategory_admin');
            Route::post('create-category', 'CreateDataCategory');
            Route::post('update-category/{slug}', 'UpdateDataCategory');
            Route::delete('delete-category/{slug}', 'DeleteDataCategory');
        });
    });
});
