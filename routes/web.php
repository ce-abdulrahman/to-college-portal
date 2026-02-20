<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\AIRankingController;
use App\Http\Controllers\Student\FinalReportController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Student\DashboardStudentController;
use App\Http\Controllers\Student\DepartmentSelectionController;
use App\Http\Controllers\Student\GISController;
use App\Http\Controllers\Student\MbtiController;



Route::get('/', function () {
    return view('welcome');
});


Route::get('/d', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';

Route::prefix('s')->name('student.')
    ->middleware(['auth','student'])
    ->group(function () {
        Route::get('/d', [DashboardStudentController::class, 'index'])->name('dashboard');
        Route::get('/final-report', [FinalReportController::class, 'index'])->name('final-report');

        Route::prefix('mbti')
        ->name('mbti.')
        ->group(function () {
            Route::get('/test', [MbtiController::class, 'index'])->name('index');
            Route::post('/test', [MbtiController::class, 'store'])->name('store');
            Route::get('/result', [MbtiController::class, 'result'])->name('result');
            Route::post('/retake', [MbtiController::class, 'retake'])->name('retake');
        });

        Route::prefix('ai-ranking')->name('ai-ranking.')->group(function () {
            Route::get('/preferences', [AIRankingController::class, 'preferences'])
                ->name('preferences');
            Route::post('/generate', [AIRankingController::class, 'generate'])
                ->name('generate');
        });

        Route::get('/departments/selection', [DepartmentSelectionController::class, 'index'])
            ->name('departments.selection');

        Route::post('/departments/add', [DepartmentSelectionController::class, 'addDepartment'])
            ->name('departments.add');

        Route::delete('/departments/remove/{id}', [DepartmentSelectionController::class, 'removeDepartment'])
            ->name('departments.remove');

        Route::get('/departments/selected', [DepartmentSelectionController::class, 'getSelectedDepartments'])
            ->name('departments.selected');


        Route::get('/departments/request-more', [DepartmentSelectionController::class, 'showRequestForm'])
            ->name('departments.request-more');
        Route::get('/features/request', [DepartmentSelectionController::class, 'showRequestForm'])
            ->name('features.request');

        Route::post('/departments/submit-request', [DepartmentSelectionController::class, 'submitRequest'])
            ->name('departments.submit-request');

        Route::delete('/departments/cancel-request/{id}', [DepartmentSelectionController::class, 'cancelRequest'])
            ->name('departments.cancel-request');

        Route::get('/departments/request-history', [DepartmentSelectionController::class, 'requestHistory'])
            ->name('departments.request-history');

        // New API Routes for Department Selection (No Livewire)
        Route::get('/departments/available-api', [DepartmentSelectionController::class, 'availableApi'])
            ->name('departments.available-api');
        Route::post('/departments/save-ranking', [DepartmentSelectionController::class, 'saveRanking'])
            ->name('departments.save-ranking');
        Route::post('/departments/select-final', [DepartmentSelectionController::class, 'selectFinal'])
            ->name('departments.select-final');
        Route::get('/universities-by-province/{province_id}', [DepartmentSelectionController::class, 'getUniversities'])
            ->name('universities-by-province');
        Route::get('/colleges-by-university/{university_id}', [DepartmentSelectionController::class, 'getColleges'])
            ->name('colleges-by-university');

        Route::prefix('gis')->group(function () {
            Route::get('/', [GISController::class, 'index'])
                ->name('gis.index');

            Route::get('/province/{id}', [GISController::class, 'getProvinceData'])
                ->name('gis.province');

            Route::post('/search', [GISController::class, 'search'])
                ->name('gis.search');

            Route::post('/add', [GISController::class, 'addDepartment'])
                ->name('gis.add');

            Route::delete('/remove/{id}', [GISController::class, 'removeDepartment'])
                ->name('gis.remove');
        });

    });
