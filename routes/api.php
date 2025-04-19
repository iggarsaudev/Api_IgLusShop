<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OutletController;

// Registro público
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Rutas protegidas
Route::middleware('auth:sanctum', IsAdmin::class)->group(function () {
    // Logout
    // Route::post('/logout', [AuthController::class, 'logout']);

    // Users
    Route::apiResource('users', UserController::class);

    // Obtener usuario autenticado
    Route::get('/user', [AuthController::class, 'index']);
});

Route::apiResource('products', ProductController::class)->only(['index','show']); // pública

Route::middleware('auth:sanctum',IsAdmin::class)->group(function () {
    Route::apiResource('products', ProductController::class)->except(['index','show']);
});

Route::apiResource('outlet', OutletController::class)->only(['index','show']); // pública

Route::middleware('auth:sanctum',IsAdmin::class)->group(function () {
    Route::apiResource('outlet', OutletController::class)->except(['index','show']);
});

