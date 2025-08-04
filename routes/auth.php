<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotController;

Route::prefix('login')->group(function () {
    Route::get('', [LoginController::class, 'index'])->name('login')->middleware('guest');
    Route::post('', [LoginController::class, 'login'])->name('login')->middleware( 'throttle:5,1');
});


Route::get('logout', [LoginController::class, 'logout'])->name('logout');
