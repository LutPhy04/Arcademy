<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// Rute untuk otentikasi Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::post('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Grup rute yang menggunakan middleware CORS
Route::middleware(['cors'])->group(function () {
    Route::get('/test-cors', function () {
        return response()->json(['message' => 'CORS test successful']);
    });
});

// Log request data
Route::middleware(['cors'])->group(function () {
    Route::get('/test-cors', function (Request $request) {
        Log::info('CORS Middleware is processing request:', $request->all());
        return response()->json(['message' => 'CORS test successful']);
    });
});

// Rute untuk autentikasi API
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});
