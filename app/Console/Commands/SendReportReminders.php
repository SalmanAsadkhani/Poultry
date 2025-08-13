<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\DailyReportReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendReportReminders extends Command
{
    protected $signature = 'app:send-report-reminders';
    protected $description = 'Sends a push notification to users who have not filled their daily report.';


    public function handle()
    {
        $this->info('شروع فرآیند ارسال یادآورها...');
        $remindersSent = 0;

        $users = User::whereHas('cycle', function ($query) {
            $query->where('status', 1)->whereNull('end_date');
        })->with([
            'cycle' => function ($query) {
                $query->where('status', 1)->whereNull('end_date');
            },
            'cycle.dailyReports'
        ])->get();

        if ($users->isEmpty()) {
            $this->info('هیچ کاربری با دوره فعال یافت نشد.');
            return 0;
        }

        foreach ($users as $user) {
            $needsReminder = false;

            foreach ($user->cycle as $activeCycle) {

                $lastReport = $activeCycle->dailyReports
                    ->sortByDesc('date')
                    ->first();

                if (is_null($lastReport->mortality_count)) {
                    $needsReminder = true;
                    break;
                }
            }


            if ($needsReminder && $user->pushSubscriptions()->exists()) {
                try {
                    $user->notify(new DailyReportReminder());
                    Log::info($user->pushSubscriptions()->get()->toArray());
                    $this->info("یادآور برای کاربر {$user->name} ارسال شد.");
                    $remindersSent++;
                }  catch (\Exception $e) {
                    Log::error("Push error for user {$user->id}: " . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
            }

            }
        }

        $this->info("عملیات به پایان رسید. {$remindersSent} یادآور ارسال شد.");
        return 0;
    }

}
