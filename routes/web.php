<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\CollegeController;
use App\Http\Controllers\Admin\DepartmentController;

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

    // edit profile
    Route::get('admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
    Route::get('admin/profile/create', [AdminProfileController::class, 'create'])->name('admin.profile.create');
    Route::post('admin/profile', [AdminProfileController::class, 'store'])->name('admin.profile.store');
    Route::get('admin/profile/{id}/edit', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('admin/profile/{id}', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('admin/profile/{id}', [AdminProfileController::class, 'destroy'])->name('admin.profile.destroy');



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

