<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/products', [ProductController::class, 'index']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Purchase a product
    Route::post('/products/{id}/purchase', [PurchaseController::class, 'store']);
    // Product Management
    Route::post('/products', [ProductController::class, 'store']); 
});