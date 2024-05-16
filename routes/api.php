<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;


Route::middleware('api')->group(function () {
  Route::post('/payment', [PaymentController::class, 'processPayment']);
});
