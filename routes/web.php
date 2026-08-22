<?php

use App\Http\Controllers\Candidate\CandidateAuthController;
use App\Http\Controllers\Candidate\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\CandidateImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//
//Route::get('dashboard', function () {
//    return view('staff.dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->name('dashboard');
    Route::get('candidate/import', [CandidateImportController::class, 'create'])->name('candidate.import');
    Route::post('/candidates/import', [CandidateImportController::class, 'store'])->name('candidates.import.store');
});

Route::prefix('candidate')->name('candidate.')->group(function () {

    Route::get('/login', [CandidateAuthController::class, 'create'])->name('login');
    Route::post('/login', [CandidateAuthController::class, 'login'])->name('login.store');

    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

    Route::middleware(['candidate'])->group(function () {
        Route::post('/logout', [CandidateAuthController::class, 'destroy'])->name('logout');
        Route::get('/dashboard', function () {
            return view('candidate.dashboard');
        })->name('dashboard');
        Route::get('/payment/screening', [PaymentController::class, 'screeningPay'])->name('payment.screening');
    });

});

require __DIR__.'/auth.php';
