<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route::apiResource("/users",UserController::class)->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [ProfileController::class, 'show']);

});
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/users', [UserController::class, 'index']);

    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::put('/users/{id}', [UserController::class, 'update']);

    Route::delete('/users/{id}', [UserController::class, 'destroy']);

});

// Route::apiResource("/products",ProductController::class);
Route::get('/products',[ProductController::class,'index']); //show all products without login or token
Route::get('/products/{id}',[ProductController::class,'show']); // show selected product
Route::post('/products',[ProductController::class,'store'])->middleware('auth:sanctum');    // create a new product
Route::put('/products/{id}',[ProductController::class,'update'])->middleware('auth:sanctum');    // update product
Route::delete('/products/{id}',[ProductController::class,'destroy'])->middleware('auth:sanctum');    // delete product

Route::apiResource("/cart",CartController::class)->middleware('auth:sanctum'); // manage cart items



Route::middleware('auth:sanctum')->group(function () {

    // User orders
    Route::get('/orders', [OrderController::class, 'userOrders']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

    // All users orders
    Route::get('/all-orders', [OrderController::class, 'allOrders']);

});
