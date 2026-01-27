<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Backend API is Running Successfully!',
        'status' => 200
    ]);
});
require __DIR__.'/auth.php';
