<?php


use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('panel')->group(function () {
    Route::get('' , [HomeController::class, 'index'])->name('home')->middleware('auth');
});

Route::prefix('panel/breeding')->group(function () {
    Route::get('' , [\App\Http\Controllers\BreedingCyclesController::class, 'index'])->name('breeding.index')->middleware('auth');
    Route::post('add' , [\App\Http\Controllers\BreedingCyclesController::class, 'add_breeding'])->name('breeding.add')->middleware('auth');
    Route::get('show/{id}' , [\App\Http\Controllers\BreedingCyclesController::class, 'show'])->name('breeding.show')->middleware('auth');
    Route::post('daily-confirm/{id}' , [\App\Http\Controllers\BreedingCyclesController::class, 'daily_confirm'])->name('daily.confirm')->middleware('auth');
});
