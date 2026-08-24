<?php

use App\Http\Controllers\Candidate\CandidateAuthController;
use App\Http\Controllers\Candidate\EligibilityCheckController;
use App\Http\Controllers\Candidate\OlevelController;
use App\Http\Controllers\Candidate\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\AdmissionsController;
use App\Http\Controllers\Staff\CandidateImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//
// Route::get('dashboard', function () {
//    return view('staff.dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('dashboard');
    Route::get('/admissions', [AdmissionsController::class, 'index'])->name('admissions.index');
    Route::post('/admissions/{candidate}/recommend', [AdmissionsController::class, 'recommend'])->name('admissions.recommend');
    Route::get('candidate/import', [CandidateImportController::class, 'create'])->name('candidate.import');
    Route::post('/candidates/import', [CandidateImportController::class, 'store'])->name('candidates.import.store');
});

Route::get('/eligibility-check', [EligibilityCheckController::class, 'create'])->name('eligibility.check');
Route::post('/eligibility-check', [EligibilityCheckController::class, 'check'])->name('eligibility.check.store');

Route::prefix('candidate')->name('candidate.')->group(function () {

    Route::get('/login', [CandidateAuthController::class, 'create'])->name('login');
    Route::post('/login', [CandidateAuthController::class, 'login'])->name('login.store');

    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

    Route::middleware(['candidate'])->group(function () {

        Route::post('/logout', [CandidateAuthController::class, 'destroy'])->name('logout');
        Route::get('/dashboard', function () {
            return view('candidate.dashboard');
        })->name('dashboard');

        // Payment Routes
        Route::get('/payment/screening', [PaymentController::class, 'screeningPay'])->name('payment.screening');
        Route::get('/payment/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payment.receipt');

        // O'Level Routes
        Route::get('/olevel/create', [OlevelController::class, 'create'])->name('olevel.create');
        Route::post('/olevel', [OlevelController::class, 'store'])->name('olevel.store');
        Route::get('/olevel/verify', [OlevelController::class, 'verify'])->name('olevel.verify');
        Route::post('/olevel/verify', [OlevelController::class, 'confirmVerification'])->name('olevel.verify.store');

        // Document Routes
        Route::get('/documents', [\App\Http\Controllers\Candidate\DocumentController::class, 'index'])->name('documents.index');
        Route::post('/documents', [\App\Http\Controllers\Candidate\DocumentController::class, 'store'])->name('documents.store');
    });

});

require __DIR__.'/auth.php';
