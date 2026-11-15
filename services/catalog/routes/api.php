<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::get('/catalog/products', [ProductController::class, 'index']);
Route::get('/catalog/products/{product}', [ProductController::class, 'show']);

// Админские маршруты
Route::post('/catalog/products', [ProductController::class, 'store']);
Route::delete('/catalog/products/{product}', [ProductController::class, 'destroy']);
Route::patch('/catalog/products/{product}', [ProductController::class, 'update']);
