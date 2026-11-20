<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::prefix('catalog')->group(function () {
    // Публичные get запросы
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::get('/categories/{category}/attributes', [AttributeController::class, 'getForCategory']);
    Route::get('/attributes', [AttributeController::class, 'index']);

    // Админские маршруты
    Route::middleware(['jwt.verify','role:admin'])->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::patch('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
        Route::post('/products/{product}/image', [ProductController::class, 'uploadImage']);

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::patch('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
        Route::post('/categories/{category}/attributes', [AttributeController::class, 'syncAttributes']);
        Route::post('/categories/{category}/image', [CategoryController::class, 'uploadImage']);

        Route::post('/attributes', [AttributeController::class, 'store']);
        Route::patch('/attributes/{attribute}', [AttributeController::class, 'update']);
        Route::delete('/attributes/{attribute}', [AttributeController::class, 'destroy']);

        Route::post('/attributes/{attribute}/values', [AttributeValueController::class, 'store']);
        Route::patch('/attribute-values/{value}', [AttributeValueController::class, 'update']);
        Route::delete('/attribute-values/{value}', [AttributeValueController::class, 'destroy']);
    });
});
