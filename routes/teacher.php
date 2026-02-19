<?php

use App\Http\Controllers\Shared\QueueHandDepartmentController;
use App\Http\Controllers\Teacher\TeacherByStudentController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\Teacher\TeacherProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'teacher'])
    ->prefix('teacher')
    ->as('teacher.')
    ->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

        Route::get('/departments', [TeacherDashboardController::class, 'departments'])->name('departments.index');
        Route::get('/departments/compare-descriptions', [TeacherDashboardController::class, 'compareDescriptions'])
            ->name('departments.compare-descriptions');
        Route::get('/department/{id}', [TeacherDashboardController::class, 'show'])->name('departments.show');

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

        Route::resource('/students', TeacherByStudentController::class);
        Route::post('/students/{student}/activate', [TeacherByStudentController::class, 'activate'])->name('students.activate');

        Route::get('/profile/edit/{id}', [TeacherProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/{id}', [TeacherProfileController::class, 'update'])->name('profile.update');

        Route::get('/features/request', [\App\Http\Controllers\Teacher\FeatureRequestController::class, 'showRequestForm'])
            ->name('features.request');
        Route::post('/features/submit-request', [\App\Http\Controllers\Teacher\FeatureRequestController::class, 'submitRequest'])
            ->name('features.submit-request');
        Route::delete('/features/cancel-request/{id}', [\App\Http\Controllers\Teacher\FeatureRequestController::class, 'cancelRequest'])
            ->name('features.cancel-request');
        Route::get('/features/request-history', [\App\Http\Controllers\Teacher\FeatureRequestController::class, 'requestHistory'])
            ->name('features.request-history');
    });
