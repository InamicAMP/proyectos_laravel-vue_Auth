<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Se agregan los Controladores al archivo 'routes'
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::prefix('v1')->group( function () {
    Route::prefix('auth')->group(function() {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware('auth:api',)->group( function () {
            Route::get('me', [AuthController:: class, 'me']);
            Route::get('refresh', [AuthController:: class, 'refresh']);
            Route::get('logout', [AuthController:: class, 'logout'])->name('logout');            
        });
    });
    Route::middleware('auth:api')->group(function () {
        Route::resource('users', UserController::class);
    });
});
Route::get('/', [AuthController::class, 'unauthorized'])->name('login');


// Código por default
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
