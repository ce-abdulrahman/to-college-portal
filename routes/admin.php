<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\CollegeController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ResultController;

Route::middleware(['auth', 'admin']) // 'admin' middlewareی خۆمان
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('systems', SystemController::class);
        Route::resource('provinces', ProvinceController::class);
        Route::resource('universities', UniversityController::class);
        Route::resource('colleges', CollegeController::class);
        Route::resource('departments', DepartmentController::class);

        // users routes will be here
        Route::resource('users', UserProfileController::class)->names('users');

        // student routes will be here
        Route::resource('students', StudentController::class)->names('students');

        // results for all students
        Route::resource('results', ResultController::class)->names('results');

        // API‌های داخلی داشبۆرد (Cascading selects)
        Route::get('/api/universities', [DepartmentController::class, 'getUniversities'])->name('api.universities');
        Route::get('/api/colleges', [DepartmentController::class, 'getColleges'])->name('api.colleges');
    });
