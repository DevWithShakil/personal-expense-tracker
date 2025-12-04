<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('transactions', TransactionController::class)
        ->only(['index', 'store','update', 'destroy']);

    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // PDF Export Route
    Route::get('export-pdf', [DashboardController::class, 'exportPdf']);

});
