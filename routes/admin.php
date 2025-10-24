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
use App\Http\Controllers\Admin\GeoController;
use App\Http\Controllers\Admin\PictureController;

Route::middleware(['auth', 'admin']) // 'admin' middlewareی خۆمان
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/provinces/{province}/universities', [DashboardController::class, 'universitiesByProvince']);

        Route::resource('systems', SystemController::class);

        Route::resource('provinces', ProvinceController::class);

        Route::resource('universities', UniversityController::class);

        Route::resource('colleges', CollegeController::class);

        Route::resource('departments', DepartmentController::class);
        // API‌های داخلی داشبۆرد (Cascading selects)
        Route::get('/api/universities', [DepartmentController::class, 'getUniversities'])->name('api.universities');
        Route::get('/api/colleges', [DepartmentController::class, 'getColleges'])->name('api.colleges');

        // users routes will be here
        Route::resource('users', UserProfileController::class)->names('users');

        // student routes will be here
        Route::resource('students', StudentController::class)->names('students');

        // results for all students
        Route::resource('results', ResultController::class)->names('results');

    });

Route::prefix('admin/geo')
    ->name('admin.geo.')
    ->group(function () {
        // Provinces (AREA = GeoJSON)
        Route::get('/provinces/{province}/edit-area', [GeoController::class, 'editProvinceArea'])->name('province.edit-area');
        Route::put('/provinces/{province}/area', [GeoController::class, 'updateProvinceArea'])->name('province.update-area');

        // Universities (AREA + POINT)
        Route::get('/universities/{university}/edit-geo', [GeoController::class, 'editUniversityGeo'])->name('university.edit-geo');
        Route::put('/universities/{university}/geo', [GeoController::class, 'updateUniversityGeo'])->name('university.update-geo');

        // Colleges (AREA + POINT)
        Route::get('/colleges/{college}/edit-geo', [GeoController::class, 'editCollegeGeo'])->name('college.edit-geo');
        Route::put('/colleges/{college}/geo', [GeoController::class, 'updateCollegeGeo'])->name('college.update-geo');

        // Departments (POINT only)
        Route::get('/departments/{department}/edit-point', [GeoController::class, 'editDepartmentPoint'])->name('department.edit-point');
        Route::put('/departments/{department}/point', [GeoController::class, 'updateDepartmentPoint'])->name('department.update-point');
    });
