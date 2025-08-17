<?php


use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfflineController;
use App\Http\Controllers\PushSubscriptionController;
use App\Models\User;
use App\Notifications\DailyReportReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

Route::prefix('panel')->group(function () {
    Route::get('' , [HomeController::class, 'index'])->name('home')->middleware('auth');
    Route::post('/offline/submit', [OfflineController::class, 'handle'])->name('offline.submit');

});

Route::prefix('panel/breeding')->group(function () {
    Route::get('' , [\App\Http\Controllers\BreedingCyclesController::class, 'index'])->name('breeding.index')->middleware('auth');
    Route::post('add_breeding' , [\App\Http\Controllers\BreedingCyclesController::class, 'add_breeding'])->name('breeding.add')->middleware('auth');
    Route::get('{id}/show' , [\App\Http\Controllers\BreedingCyclesController::class, 'show'])->name('breeding.show')->middleware('auth');
    Route::post('{id}/store' , [\App\Http\Controllers\BreedingCyclesController::class, 'store'])->name('daily.confirm')->middleware('auth');
});

Route::prefix('panel/expense')->group(function () {
    Route::get('' , [\App\Http\Controllers\ExpenseController::class, 'index'])->name('expense.index')->middleware('auth');
    Route::post('invoice/store' , [\App\Http\Controllers\ExpenseController::class, 'invoice'])->name('Invoice.store')->middleware('auth');
    Route::post('invoice/{id}/update' , [\App\Http\Controllers\ExpenseController::class, 'invoice_update'])->name('invoice.expense.update')->middleware('auth');
    Route::post('invoice/{id}/destroy' , [\App\Http\Controllers\ExpenseController::class, 'invoice_destroy'])->name('invoice.expense.destroy')->middleware('auth');
    Route::get('/{type}/{category}/show', [ExpenseController::class, 'showCategory'])->name('expense.category.show')->middleware('auth');
    Route::post('store' , [\App\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store')->middleware('auth');
    Route::post('{id}/update' , [\App\Http\Controllers\ExpenseController::class, 'update'])->name('expenses.update')->middleware('auth');
    Route::post('{id}/destroy' , [\App\Http\Controllers\ExpenseController::class, 'destroy'])->name('expenses.destroy')->middleware('auth');
});


Route::prefix('panel/income')->group(function () {
    Route::get('' , [\App\Http\Controllers\IncomeController::class, 'index'])->name('income.index')->middleware('auth');
    Route::post('invoice/store' , [\App\Http\Controllers\IncomeController::class, 'invoice'])->name('Invoice.income.store')->middleware('auth');
    Route::post('invoice/{id}/update' , [\App\Http\Controllers\IncomeController::class, 'invoice_update'])->name('Invoice.income.update')->middleware('auth');
    Route::post('invoice/{id}/destroy' , [\App\Http\Controllers\IncomeController::class, 'invoice_destroy'])->name('Invoice.income.destroy')->middleware('auth');
    Route::get('/{type}/{category}/show', [\App\Http\Controllers\IncomeController::class, 'show'])->name('income.category.show')->middleware('auth');
    Route::post('store' , [\App\Http\Controllers\IncomeController::class, 'store'])->name('income.store')->middleware('auth');
    Route::post('{id}/update' , [\App\Http\Controllers\IncomeController::class, 'update'])->name('income.update')->middleware('auth');
    Route::post('{id}/destroy' , [\App\Http\Controllers\IncomeController::class, 'destroy'])->name('income.destroy')->middleware('auth');
});


Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store'])->name('push_subscriptions.store')->middleware('auth');


