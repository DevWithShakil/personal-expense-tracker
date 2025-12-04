<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Route
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {

    // Auth & User
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Dashboard & Reports
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('export-pdf', [DashboardController::class, 'exportPdf']);

    // Core Features
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('transactions', TransactionController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // User Settings (Matched with Frontend)
    Route::post('/profile/update', [UserController::class, 'updateProfile']);
    Route::post('/profile/change-password', [UserController::class, 'changePassword']);

});
