<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;


Route::middleware('api')->group(function () {
  Route::post('/payment', [PaymentController::class, 'processPayment']);
});

Route::apiResource('orders', OrderController::class);