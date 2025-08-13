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
    Route::post('Invoice/store' , [\App\Http\Controllers\ExpenseController::class, 'Invoice'])->name('Invoice.store')->middleware('auth');
    Route::get('/{type}/{category}/show', [ExpenseController::class, 'showCategory'])->name('expense.category.show')->middleware('auth');
    Route::post('store' , [\App\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store')->middleware('auth');
    Route::post('{id}/update' , [\App\Http\Controllers\ExpenseController::class, 'update'])->name('expenses.update')->middleware('auth');
    Route::post('{id}/destroy' , [\App\Http\Controllers\ExpenseController::class, 'destroy'])->name('expenses.destroy')->middleware('auth');
});


Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store'])->name('push_subscriptions.store')->middleware('auth');


Route::get('t' , function (){
    $remindersSent = 0;

    // پیدا کردن کاربران با حداقل یک دوره فعال
    $users = User::whereHas('cycle', function ($query) {
        $query->where('status', 1)->whereNull('end_date');
    })->with([
        'cycle' => function ($query) {
            $query->where('status', 1)->whereNull('end_date');
        },
        'cycle.dailyReports' // همه گزارش‌ها رو لود می‌کنیم تا بعدا آخرین رو پیدا کنیم
    ])->get();

    if ($users->isEmpty()) {
      echo 'هیچ کاربری با دوره فعال یافت نشد.';
        return 0;
    }

    foreach ($users as $user) {
        $needsReminder = false;

        foreach ($user->cycle as $activeCycle) {
            // گرفتن آخرین گزارش ثبت‌شده این دوره بر اساس تاریخ
            $lastReport = $activeCycle->dailyReports
                ->sortByDesc('date')
                ->first();

            // اگر گزارش وجود نداره یا مقدار تلفات خالیه => یادآوری لازم
            if (is_null($lastReport->mortality_count)) {
                $needsReminder = true;
                break; // همین که یکی پیدا شد کافیه
            }
        }

        // ارسال اعلان
        if ($needsReminder && $user->pushSubscriptions()->exists()) {
            try {
                $user->notify(new DailyReportReminder());
               echo "یادآور برای کاربر {$user->name} ارسال شد.";
                $remindersSent++;
            } catch (\Exception $e) {

            }
        }
    }

});
