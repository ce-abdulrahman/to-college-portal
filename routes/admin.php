<?php

use App\Http\Controllers\Admin\CenterController;
use App\Http\Controllers\Admin\TeacherController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\CollegeController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ResultController;

Route::middleware(['auth', 'admin']) // 'admin' middlewareی خۆمان
    ->prefix('sadm')
    ->as('admin.')
    ->group(function () {
        Route::get('/dshbd', [DashboardController::class, 'index'])->name('dashboard');

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
        Route::post('/users/search-by-code', [UserProfileController::class, 'searchByCode'])->name('users.searchByCode');

        Route::get('/centers', [CenterController::class, 'index'])->name('centers.index');
        Route::get('/center/{id}/teachers', [CenterController::class, 'show'])->name('center.show');

        Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
        Route::get('/teacher/{id}/students', [TeacherController::class, 'show'])->name('teacher.show');

        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{id}/department', [StudentController::class, 'show'])->name('student.show');


        Route::resource('results', ResultController::class)->names('results');

    });


Route::middleware(['auth', 'admin'])->prefix('dashboard')->group(function () {
    // Provinces GeoJSON for the basemap
    Route::get('/provinces/geojson', [DashboardController::class, 'provincesGeoJSON'])
        ->name('provinces.geojson');

    // Drilldown APIs (DB → JSON → JS)
    Route::get('/provinces/{province}/universities', [DashboardController::class, 'universitiesByProvince'])
        ->name('provinces.universities');

    Route::get('/universities/{university}/colleges', [DashboardController::class, 'collegesByUniversity'])
        ->name('universities.colleges');

    Route::get('/colleges/{college}/departments', [DashboardController::class, 'departmentsByCollege'])
        ->name('colleges.departments');
});

