<?php

use App\Http\Controllers\Teacher\TeacherByStudentController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Teacher\TeacherProfileController;
use Illuminate\Support\Facades\Route;



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


Route::middleware(['auth', 'teacher']) // 'teacher' middlewareی خۆمان
    ->prefix('teacher')
    ->as('teacher.')
    ->group(function () {

        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

        Route::get('/departments', [TeacherDashboardController::class, 'departments'])->name('departments.index');

        Route::resource('/students', TeacherByStudentController::class);

        Route::get('/profile/edit/{id}', [TeacherProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/{id}', [TeacherProfileController::class, 'update'])->name('profile.update');

    });
