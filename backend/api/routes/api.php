<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    Route::get('/orders/track/{trackingCode}', [OrderController::class, 'track']);

    Route::middleware('auth.api')->group(function (): void {
        Route::get('/auth/me', [AuthController::class, 'me']);

        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/my', [OrderController::class, 'myOrders']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders/{order}/payment-proof', [OrderController::class, 'uploadPaymentProof']);
        Route::get('/orders/{order}/receipt-view', [OrderController::class, 'receiptView']);
        Route::get('/orders/{order}/receipt', [OrderController::class, 'downloadReceipt']);

        Route::middleware('admin')->group(function (): void {
            Route::get('/admin/products', [ProductController::class, 'adminIndex']);
            Route::post('/products', [ProductController::class, 'store']);
            Route::put('/products/{product}', [ProductController::class, 'update']);
            Route::delete('/products/{product}', [ProductController::class, 'destroy']);

            Route::get('/admin/orders', [OrderController::class, 'index']);
            Route::get('/admin/orders/export', [OrderController::class, 'export']);
            Route::patch('/admin/orders/{order}/status', [OrderController::class, 'updateStatus']);
            Route::patch('/admin/orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus']);
            Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy']);

            Route::get('/admin/users', [AdminUserController::class, 'index']);
            Route::patch('/admin/users/{user}', [AdminUserController::class, 'update']);
            Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy']);
        });
    });
});
