<?php

use App\Http\Controllers\Center\CenterProfileController;
use App\Http\Controllers\Center\DashboardCenterController;
use App\Http\Controllers\Center\StudentInCenterController;
use App\Http\Controllers\Center\TeacherInCenterController;
use App\Http\Controllers\Shared\QueueHandDepartmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('center')->name('center.')->middleware(['auth', 'center'])
    ->group(function () {
        Route::get('/dashboard', [DashboardCenterController::class, 'index'])->name('dashboard');

        Route::get('/departments', [DashboardCenterController::class, 'departments'])->name('departments.index');
        Route::get('/departments/compare-descriptions', [DashboardCenterController::class, 'compareDescriptions'])
            ->name('departments.compare-descriptions');
        Route::get('/department/{id}', [DashboardCenterController::class, 'show'])->name('departments.show');

        Route::get('/queue-hand-departments', [QueueHandDepartmentController::class, 'index'])
            ->name('queue-hand-departments.index');
        Route::get('/queue-hand-departments/data', [QueueHandDepartmentController::class, 'data'])
            ->name('queue-hand-departments.data');
        Route::post('/queue-hand-departments/students', [QueueHandDepartmentController::class, 'storeStudent'])
            ->name('queue-hand-departments.students.store');
        Route::get('/queue-hand-departments/student-selection', [QueueHandDepartmentController::class, 'studentSelection'])
            ->name('queue-hand-departments.student-selection');
        Route::post('/queue-hand-departments/save-result-deps', [QueueHandDepartmentController::class, 'saveResultDeps'])
            ->name('queue-hand-departments.save-result-deps');

        Route::resource('teachers', TeacherInCenterController::class);
        Route::resource('students', StudentInCenterController::class);
        Route::post('/teachers/{teacher}/activate', [TeacherInCenterController::class, 'activate'])->name('teachers.activate');
        Route::post('/students/{student}/activate', [StudentInCenterController::class, 'activate'])->name('students.activate');

        Route::get('/profile/edit/{id}', [CenterProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/{id}', [CenterProfileController::class, 'update'])->name('profile.update');

        Route::get('/features/request', [\App\Http\Controllers\Center\FeatureRequestController::class, 'showRequestForm'])
            ->name('features.request');
        Route::post('/features/submit-request', [\App\Http\Controllers\Center\FeatureRequestController::class, 'submitRequest'])
            ->name('features.submit-request');
        Route::delete('/features/cancel-request/{id}', [\App\Http\Controllers\Center\FeatureRequestController::class, 'cancelRequest'])
            ->name('features.cancel-request');
        Route::get('/features/request-history', [\App\Http\Controllers\Center\FeatureRequestController::class, 'requestHistory'])
            ->name('features.request-history');
    });
