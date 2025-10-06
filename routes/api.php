<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Api\V1\AuthController;
//use App\Http\Controllers\Api\V1\CascadeController;
use App\Http\Controllers\Api\V1\LookupController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth (token issuance for Flutter/mobile)
//Route::post('/auth/login', [AuthController::class, 'login']); // دەگرێت token

// Public (اختیاری): throttleی تایبەتی
Route::middleware('throttle:60,1')->group(function () {
    // مثلاً لیستی سیستەمە چالاکەکان
    // Route::get('/systems', [PublicController::class, 'systems']);
});

// Protected by Sanctum
Route::middleware(['auth:sanctum', 'admin', 'throttle:api'])->group(function () {
    // Dependent lookups (ID-based)
    Route::get('/lookups/universities', [LookupController::class, 'universities']);
    Route::get('/lookups/colleges', [LookupController::class, 'colleges']);

    // routesی تر...
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
