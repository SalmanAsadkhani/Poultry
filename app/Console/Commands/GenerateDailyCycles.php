<?php
namespace App\Console\Commands;

use App\Models\BreedingCycle;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Console\Command;

class GenerateDailyCycles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-daily-cycles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily reports for breeding cycles.';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        $today = Verta::now()->format('Y/m/d'); // تاریخ امروز
        $yesterday = Verta::now()->subDay()->format('Y/m/d'); // تاریخ دیروز

        // دریافت دوره‌های پرورش فعال که هنوز به پایان نرسیده‌اند
        $activeCycles = BreedingCycle::where('status', 1)->whereNull('end_date')->get();

        foreach ($activeCycles as $cycle) {
            $startDate = Verta::parse($cycle->start_date);  // تاریخ شروع دوره
            $firstReportDate = $startDate->addDay();  // تاریخ اولین گزارش باید روز بعد از start_date باشد
            $daysPassed = $firstReportDate->diffDays(Verta::now()) + 1; // تعداد روزهای گذشته از اولین گزارش

            // حلقه برای ایجاد گزارش روزانه از روز بعد از تاریخ شروع
            for ($day = 1; $day <= $daysPassed; $day++) {
                // تاریخ هر روز از روز شروع
                $currentDate = $firstReportDate->addDays($day - 1)->format('Y/m/d');

                // اگر تاریخ امروز است، گزارش ایجاد نکن
                if ($currentDate == $today) {
                    continue;
                }

                // بررسی می‌کنیم که آیا گزارشی برای این تاریخ قبلاً ثبت شده است
                $exists = $cycle->dailyReports()->where('date', $currentDate)->exists();

                if ($exists) {
                    $this->info("⛔ گزارش برای روز $currentDate برای دوره {$cycle->id} قبلاً ثبت شده.");
                    continue;
                }

                // دریافت نوع خوراک برای این روز
                $feed = $this->getFeedTypeForDay($day);

                // ایجاد گزارش روزانه برای این روز
                $cycle->dailyReports()->create([
                    'date' => $currentDate,
                    'days_number' => $day,
                    'feed_type' => $feed,
                ]);

                $this->info("✅ گزارش روز $day برای دوره {$cycle->id} برای تاریخ $currentDate ساخته شد.");
            }
        }
    }



    private function getFeedTypeForDay(int $day): string
    {
        return match (true) {
            $day <= 7 => 'استارتر',
            $day <= 21 => 'پیش دان',
            $day <= 35 => 'میان دان',
            default    => 'پس دان',
        };
    }
}
