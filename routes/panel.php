<?php


use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::prefix('panel')->group(function () {
    Route::get('' , [HomeController::class, 'index'])->name('home')->middleware('auth');
});

Route::prefix('panel/breeding')->group(function () {
    Route::get('' , [\App\Http\Controllers\BreedingCyclesController::class, 'index'])->name('breeding.index')->middleware('auth');
    Route::post('add_breeding' , [\App\Http\Controllers\BreedingCyclesController::class, 'add_breeding'])->name('breeding.add')->middleware('auth');
    Route::get('show/{id}' , [\App\Http\Controllers\BreedingCyclesController::class, 'show'])->name('breeding.show')->middleware('auth');
    Route::post('daily-confirm/{id}' , [\App\Http\Controllers\BreedingCyclesController::class, 'daily_confirm'])->name('daily.confirm')->middleware('auth');
});

Route::prefix('panel/expense')->group(function () {
    Route::get('' , [\App\Http\Controllers\ExpenseController::class, 'index'])->name('expense.index')->middleware('auth');
    Route::post('Invoice/store' , [\App\Http\Controllers\ExpenseController::class, 'Invoice'])->name('Invoice.store')->middleware('auth');
    Route::get('{id}/show' , [\App\Http\Controllers\ExpenseController::class, 'show'])->name('expense.show')->middleware('auth');
    Route::post('store' , [\App\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store')->middleware('auth');
    Route::post('{id}/update' , [\App\Http\Controllers\ExpenseController::class, 'update'])->name('expenses.update')->middleware('auth');
    Route::post('{id}/delete' , [\App\Http\Controllers\ExpenseController::class, 'delete'])->name('expenses.delete')->middleware('auth');
});

