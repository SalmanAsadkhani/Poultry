<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotController;


Route::prefix('register')->group(function () {
    Route::get('', [RegisterController::class, 'index'])->name('register');
    Route::post('', [RegisterController::class, 'save'])->name('register');

    Route::get('verify', [RegisterController::class, 'verify'])->name('register_verify');
    Route::post('verify', [RegisterController::class, 'verify_save'])->name('register_verify');

    Route::get('resend', [RegisterController::class, 'resend'])->name('register_resend');
});

Route::prefix('login')->group(function () {
    Route::get('', [LoginController::class, 'index'])->name('login');
    Route::post('', [LoginController::class, 'login'])->name('login');
});

Route::prefix('forgot')->group(function () {
    Route::get('', [ForgotController::class, 'index'])->name('forgot');
    Route::post('', [ForgotController::class, 'checkMobile'])->name('forgot');

    Route::get('verify', [ForgotController::class, 'verifyPage'])->name('forgot_verify');
    Route::post('verify', [ForgotController::class, 'checkCode'])->name('forgot_verify');
});

Route::any('logout', [LoginController::class, 'logout'])->name('logout');
