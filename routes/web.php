<?php

use App\Http\Controllers\Center\CenterProfileController;
use App\Http\Controllers\Center\DashboardCenterController;
use App\Http\Controllers\Center\StudentInCenterController;
use App\Http\Controllers\Center\TeacherInCenterController;
use App\Http\Controllers\Teacher\TeacherByStudentController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\AIRankingController;
use App\Http\Controllers\Student\FinalReportController;
use App\Http\Controllers\Teacher\TeacherProfileController;
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
})->middleware(['auth', 'verified'])->name('dashboard');

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

        // AI Ranking
        Route::prefix('ai-ranking')->group(function () {
            // پێشتر: بەڕێزی هەڵبژاردنی فیلتەرەکان
            Route::get('/preferences', [AIRankingController::class, 'preferencesForm'])
                ->name('ai-ranking.preferences');

            Route::post('/save-preferences', [AIRankingController::class, 'savePreferences'])
                ->name('ai-ranking.save-preferences');

            Route::get('/questionnaire', [AIRankingController::class, 'questionnaire'])
                ->name('ai-ranking.questionnaire');

            Route::post('/submit', [AIRankingController::class, 'submitQuestionnaire'])
                ->name('ai-ranking.submit');

            Route::get('/results', [AIRankingController::class, 'results'])
                ->name('ai-ranking.results');

            Route::post('/retake', [AIRankingController::class, 'retake'])
                ->name('ai-ranking.retake');

            Route::post('/add-to-selection', [AIRankingController::class, 'addToSelection'])
                ->name('ai-ranking.add');

            // نوێ Routes
            Route::get('/check-status', [AIRankingController::class, 'checkAIStatus'])
                ->name('ai-ranking.check-status');

            Route::get('/export/excel', [AIRankingController::class, 'exportExcel'])
                ->name('export-excel');

            Route::get('/export/pdf', [AIRankingController::class, 'exportPDF'])
                ->name('export-pdf');

            Route::get('/department/{id}/details', [AIRankingController::class, 'departmentDetails'])
                ->name('ai-ranking.department-details');

            Route::post('/reorder', [AIRankingController::class, 'reorderByFactor'])
                ->name('reorder');

            Route::get('/compare', [AIRankingController::class, 'compareRankings'])
                ->name('ai-ranking.compare');
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

        // Feature Requests
        Route::get('/features/request', [\App\Http\Controllers\Center\FeatureRequestController::class, 'showRequestForm'])->name('features.request');
        Route::post('/features/submit-request', [\App\Http\Controllers\Center\FeatureRequestController::class, 'submitRequest'])->name('features.submit-request');
        Route::delete('/features/cancel-request/{id}', [\App\Http\Controllers\Center\FeatureRequestController::class, 'cancelRequest'])->name('features.cancel-request');
        Route::get('/features/request-history', [\App\Http\Controllers\Center\FeatureRequestController::class, 'requestHistory'])->name('features.request-history');
    });


Route::middleware(['auth', 'teacher'])
    ->prefix('teacher')
    ->as('teacher.')
    ->group(function () {

        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

        Route::get('/departments', [TeacherDashboardController::class, 'departments'])->name('departments.index');
        Route::get('/department/{id}', [TeacherDashboardController::class, 'show'])->name('departments.show');

        Route::resource('/students', TeacherByStudentController::class);

        Route::get('/profile/edit/{id}', [TeacherProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/{id}', [TeacherProfileController::class, 'update'])->name('profile.update');

        // Feature Requests
        Route::get('/features/request', [\App\Http\Controllers\Teacher\FeatureRequestController::class, 'showRequestForm'])->name('features.request');
        Route::post('/features/submit-request', [\App\Http\Controllers\Teacher\FeatureRequestController::class, 'submitRequest'])->name('features.submit-request');
        Route::delete('/features/cancel-request/{id}', [\App\Http\Controllers\Teacher\FeatureRequestController::class, 'cancelRequest'])->name('features.cancel-request');
        Route::get('/features/request-history', [\App\Http\Controllers\Teacher\FeatureRequestController::class, 'requestHistory'])->name('features.request-history');

    });
