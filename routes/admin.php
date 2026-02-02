<?php

use App\Http\Controllers\Admin\BackupController;
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
use App\Http\Controllers\Admin\MbtiAdminController;
use App\Http\Controllers\Admin\AIQuestionController;

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
        Route::get('/export', [DepartmentController::class, 'export'])->name('departments.export');
        Route::get('/download-template', [DepartmentController::class, 'downloadTemplate'])->name('departments.download-template');
        Route::post('/import', [DepartmentController::class, 'import'])->name('departments.import');
        // API‌های داخلی داشبۆرد (Cascading selects)
        // create and edit get data for select options
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

        Route::prefix('mbti')->name('mbti.')->group(function () {
            // پرسیارەکان
            Route::resource('questions', MbtiAdminController::class);

            // ئەنجامەکان
            Route::get('results', [MbtiAdminController::class, 'results'])->name('results.index');
            Route::get('/data', [MbtiAdminController::class, 'getResultsData'])->name('results.data');
            Route::get('results/filter', [MbtiAdminController::class, 'filterResults'])->name('results.filter');
            Route::get('results/user/{user}', [MbtiAdminController::class, 'showUserResult'])->name('results.show');
            Route::delete('results/user/{user}', [MbtiAdminController::class, 'deleteUserResult'])->name('results.delete');
            Route::get('statistics', [MbtiAdminController::class, 'statistics'])->name('statistics');
            Route::get('export', [MbtiAdminController::class, 'exportResults'])->name('export');
        });

        // AI Ranking Management
        Route::prefix('ai')->name('ai.')->group(function () {
            // پرسیارەکان - CRUD
            Route::resource('questions', AIQuestionController::class);

            // وەڵامەکانی قوتابیان
            Route::get('/results', [AIQuestionController::class, 'results'])->name('results');
            Route::get('/results/{studentId}', [AIQuestionController::class, 'showStudentAnswers'])->name('results.show');
            Route::delete('/results/{studentId}', [AIQuestionController::class, 'deleteStudentAnswers'])->name('results.delete');
        });

        // [file name]: web.php (لە بەشی Admin)
        Route::prefix('backups')->name('backups.')->group(function () {
            Route::get('/', [BackupController::class, 'index'])->name('index');
            Route::get('/create', [BackupController::class, 'create'])->name('create');
            Route::post('/', [BackupController::class, 'store'])->name('store');
            Route::get('/{backup}/restore', [BackupController::class, 'restore'])->name('restore');
            Route::post('/{backup}/restore', [BackupController::class, 'performRestore'])->name('perform-restore');
            Route::get('/{backup}/download', [BackupController::class, 'download'])->name('download');
            Route::delete('/{backup}', [BackupController::class, 'destroy'])->name('destroy');
        });
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

// routes/web.php
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // ... ڕووتەکانی تر

    // بەڕێوەبردنی داواکاریەکان
    Route::prefix('requests')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RequestManagementController::class, 'index'])
            ->name('admin.requests.index');

        Route::get('/{id}', [\App\Http\Controllers\Admin\RequestManagementController::class, 'show'])
            ->name('admin.requests.show');

        Route::post('/{id}/approve', [\App\Http\Controllers\Admin\RequestManagementController::class, 'approve'])
            ->name('admin.requests.approve');

        Route::post('/{id}/reject', [\App\Http\Controllers\Admin\RequestManagementController::class, 'reject'])
            ->name('admin.requests.reject');

        Route::delete('/{id}', [\App\Http\Controllers\Admin\RequestManagementController::class, 'destroy'])
            ->name('admin.requests.destroy');

        Route::get('/stats/data', [\App\Http\Controllers\Admin\RequestManagementController::class, 'stats'])
            ->name('admin.requests.stats');

        Route::get('/search', [\App\Http\Controllers\Admin\RequestManagementController::class, 'search'])
            ->name('admin.requests.search');
    });
});
