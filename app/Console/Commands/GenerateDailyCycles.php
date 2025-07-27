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
        $today = Verta::now()->format('Y/m/d'); // Get current date
        $yesterday = Verta::now()->subDay()->format('Y/m/d'); // Get yesterday's date

        // Get active cycles that are still ongoing
        $activeCycles = BreedingCycle::where('status', 1)->whereNull('end_date')->get();

        foreach ($activeCycles as $cycle) {
            $startDate = Verta::parse($cycle->start_date);
            $daysPassed = $startDate->diffDays(Verta::now()) + 1;  // Get the number of days passed since start

            // Loop through each day from start to yesterday and create the report if it doesn't exist
            for ($day = 1; $day < $daysPassed; $day++) { // Loop until day before today
                $currentDate = Verta::parse($cycle->start_date)->addDays($day - 1)->format('Y/m/d'); // Get each date from start to yesterday

                // Skip today, don't create a report for today
                if ($currentDate == $today) {
                    continue;
                }

                // Check if the report already exists for the current date
                $exists = $cycle->dailyReports()->where('date', $currentDate)->exists();

                if ($exists) {
                    $this->info("⛔ گزارش برای روز $currentDate برای دوره {$cycle->id} قبلاً ثبت شده.");
                    continue;
                }

                // Get the feed type for the current day
                $feed = $this->getFeedTypeForDay($day);

                // Create the daily report for the current day
                $cycle->dailyReports()->create([
                    'date' => $currentDate,
                    'days_number' => $day,
                    'feed_type' => $feed,
                ]);

                $this->info("✅ گزارش روز $day برای دوره {$cycle->id} برای تاریخ $currentDate ساخته شد.");
            }
        }
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
