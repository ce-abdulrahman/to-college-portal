<?php

use App\Http\Controllers\Center\CenterProfileController;
use App\Http\Controllers\Center\DashboardCenterController;
use App\Http\Controllers\Center\StudentInCenterController;
use App\Http\Controllers\Center\TeacherInCenterController;
use App\Http\Controllers\Teacher\TeacherByStudentController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Teacher\TeacherProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Student\DashboardStudentController;
use App\Http\Controllers\Student\MbtiController;



Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';

Route::prefix('student')->name('student.')
    ->middleware(['auth','student'])
    ->group(function () {
        Route::get('/dashboard', [DashboardStudentController::class, 'index'])->name('dashboard');

        Route::prefix('mbti')
        ->name('mbti.')
        ->group(function () {
            Route::get('/test', [MbtiController::class, 'index'])->name('index');
            Route::post('/test', [MbtiController::class, 'store'])->name('store');
            Route::get('/result', [MbtiController::class, 'result'])->name('result');
            Route::post('/retake', [MbtiController::class, 'retake'])->name('retake');
        });
    });

Route::prefix('center')->name('center.')->middleware(['auth','center'])
    ->group(function () {
        Route::get('/dashboard', [DashboardCenterController::class, 'index'])->name('dashboard');

        Route::get('/departments', [DashboardCenterController::class, 'departments'])->name('departments.index');
        Route::get('/department/{id}', [DashboardCenterController::class, 'show'])->name('departments.show');

        Route::resource('teachers', TeacherInCenterController::class);
        Route::resource('students', StudentInCenterController::class);
        Route::get('/profile/edit/{id}', [CenterProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/{id}', [CenterProfileController::class, 'update'])->name('profile.update');
    });


Route::middleware(['auth', 'teacher']) // 'teacher' middlewareی خۆمان
    ->prefix('teacher')
    ->as('teacher.')
    ->group(function () {

        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

        Route::get('/departments', [TeacherDashboardController::class, 'departments'])->name('departments.index');
        Route::get('/department/{id}', [TeacherDashboardController::class, 'show'])->name('departments.show');

        Route::resource('/students', TeacherByStudentController::class);

        Route::get('/profile/edit/{id}', [TeacherProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/{id}', [TeacherProfileController::class, 'update'])->name('profile.update');

    });
