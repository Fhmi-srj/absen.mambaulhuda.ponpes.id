<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SantriController;
use App\Http\Controllers\Api\AktivitasController;
use App\Http\Controllers\Api\RiwayatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/check', [AuthController::class, 'check'])->middleware('auth:sanctum');
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // Santri
    Route::get('/santri/search', [SantriController::class, 'search']);
    Route::get('/santri/{id}', [SantriController::class, 'show']);
    
    // Aktivitas
    Route::get('/aktivitas', [AktivitasController::class, 'index']);
    Route::post('/aktivitas', [AktivitasController::class, 'store']);
    Route::delete('/aktivitas/{id}', [AktivitasController::class, 'destroy']);
    Route::delete('/aktivitas/bulk-delete', [AktivitasController::class, 'bulkDelete']);
    
    // Riwayat
    Route::get('/riwayat', [RiwayatController::class, 'index']);
});
