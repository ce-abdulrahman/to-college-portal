<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\CollegeController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ResultController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('website.web.admin.dashboard');
    })->name('admin.dashboard');

    // System Routes name admin.systems
    Route::resource('admin/systems', SystemController::class)->names('admin.systems');

    // Province Routes name admin.provinces
    Route::resource('admin/provinces', ProvinceController::class)->names('admin.provinces');

    // University Routes name admin.universities
    Route::resource('admin/universities', UniversityController::class)->names('admin.universities');

    // College Routes name admin.colleges
    Route::resource('admin/colleges', CollegeController::class)->names('admin.colleges');

    // Department Routes name admin.departments
    Route::resource('admin/departments', DepartmentController::class)->names('admin.departments');
    Route::get('/admin/api/universities', [DepartmentController::class, 'getUniversities'])->name('admin.api.universities');
    Route::get('/admin/api/colleges', [DepartmentController::class, 'getColleges'])->name('admin.api.colleges');

    // users routes will be here
    Route::resource('admin/users', UserProfileController::class)->names('admin.users');

    // student routes will be here
    Route::resource('admin/students', StudentController::class)->names('admin.students');

    // results for all students
    Route::resource('admin/results', ResultController::class)->names('admin.results');


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

