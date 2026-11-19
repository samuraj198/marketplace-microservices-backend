<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::prefix('profile')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/auth', [UserController::class, 'auth']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [UserController::class, 'me']);
        Route::post('/logout', [UserController::class, 'logout']);
    });
});
