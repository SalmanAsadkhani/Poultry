<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotController;

Route::prefix('login')->group(function () {
    Route::get('', [LoginController::class, 'index'])->name('login')->middleware('guest');
    Route::post('', [LoginController::class, 'login'])->name('login')->middleware( 'throttle:5,1');
});

//Route::prefix('forgot')->group(function () {
//    Route::get('', [ForgotController::class, 'index'])->name('forgot');
//    Route::post('', [ForgotController::class, 'checkMobile'])->name('forgot');
//
//    Route::get('verify', [ForgotController::class, 'verifyPage'])->name('forgot_verify');
//    Route::post('verify', [ForgotController::class, 'checkCode'])->name('forgot_verify');
//});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');
