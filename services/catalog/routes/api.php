<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

// ROUTES ДЛЯ ТОВАРОВ
// Получение всех товаров
Route::get('/catalog/products', [ProductController::class, 'index']);
// Получение 1 товара
Route::get('/catalog/products/{product}', [ProductController::class, 'show']);
// Создание товара
Route::post('/catalog/products', [ProductController::class, 'store']);
// Обновление товара
Route::patch('/catalog/products/{product}', [ProductController::class, 'update']);
// Удаление товара
Route::delete('/catalog/products/{product}', [ProductController::class, 'destroy']);

// ROUTES ДЛЯ КАТЕГОРИЙ
// Получение всех категорий
Route::get('/catalog/categories', [CategoryController::class, 'index']);
// Получение 1 категории
Route::get('/catalog/categories/{category}', [CategoryController::class, 'show']);
// Создание категории
Route::post('/catalog/categories', [CategoryController::class, 'store']);
// Обновление категории
Route::patch('/catalog/categories/{category}', [CategoryController::class, 'update']);
// Удаление категории
Route::delete('/catalog/categories/{category}', [CategoryController::class, 'destroy']);

// ROUTES ДЛЯ АТРИБУТОВ
// Получение атрибутов для 1 категории
Route::get('/catalog/categories/{category}/attributes', [AttributeController::class, 'getForCategory']);
// Получение всех атрибутов
Route::get('/catalog/attributes', [AttributeController::class, 'index']);
// Привязка атрибутов к категории
Route::post('/catalog/categories/{category}/attributes', [AttributeController::class, 'syncAttributes']);
// Создание атрибута
Route::post('/catalog/attributes', [AttributeController::class, 'store']);
// Обновление атрибута
Route::patch('/catalog/attributes/{attribute}', [AttributeController::class, 'update']);
// Удаление атрибута
Route::delete('/catalog/attributes/{attribute}', [AttributeController::class, 'destroy']);

// ROUTES ДЛЯ ЗНАЧЕНИЙ АТРИБУТА
// Создание значения атрибута
Route::post('/catalog/attributes/{attribute}/values', [AttributeValueController::class, 'store']);
// Обновление значения атрибута
Route::patch('/catalog/attribute-values/{value}', [AttributeValueController::class, 'update']);
// Удаление значения атрибута
Route::delete('/catalog/attribute-values/{value}', [AttributeValueController::class, 'destroy']);
