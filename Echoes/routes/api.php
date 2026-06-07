<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::post('/cart/tickets', [BookingController::class, 'addToCart']);
Route::get('/cart/{customerId}', [BookingController::class, 'cart'])->whereNumber('customerId');

Route::post('/orders', [BookingController::class, 'createOrder']);
Route::get('/orders/history/{customerId}', [BookingController::class, 'history'])->whereNumber('customerId');
Route::post('/orders/{orderId}/cancel', [BookingController::class, 'cancelOrder'])->whereNumber('orderId');
Route::patch('/orders/{orderId}/cancel', [BookingController::class, 'cancelOrder'])->whereNumber('orderId');
Route::post('/orders/{orderId}/status', [BookingController::class, 'updateStatus'])->whereNumber('orderId');
Route::patch('/orders/{orderId}/status', [BookingController::class, 'updateStatus'])->whereNumber('orderId');
