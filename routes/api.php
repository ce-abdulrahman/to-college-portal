<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DepartmentController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/api/universities', [DepartmentController::class, 'getUniversities'])->name('admin.api.universities');
    Route::get('/admin/api/colleges', [DepartmentController::class, 'getColleges'])->name('admin.api.colleges');
});
