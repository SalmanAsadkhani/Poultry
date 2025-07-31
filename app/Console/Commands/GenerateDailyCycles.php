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


//     app/Console/Commands/GenerateDailyCycles.php

    public function handle()
    {
        $activeCycles = BreedingCycle::where('status', 1)->whereNull('end_date')->get();

        $yesterday = Verta::now()->subDay();

        $yesterdayString = $yesterday->format('Y/m/d');

        $yesterdayDate = $yesterday->DateTime()->format('Y-m-d');

        foreach ($activeCycles as $cycle) {
            $startDate = Verta::parse($cycle->start_date);


            $daysNumber = $startDate->diffDays($yesterday) + 1;


            if ($cycle->dailyReports()->where('date', $yesterdayDate)->exists()) {
                $this->info("⛔ گزارش برای تاریخ $yesterdayString قبلاً ثبت شده.");
                continue;
            }

            $feed = $this->getFeedTypeForDay($daysNumber);

            $cycle->dailyReports()->create([
                'date' => $yesterdayDate,
                'days_number' => $daysNumber,
                'feed_type' => $feed,
            ]);

            $this->info("✅ گزارش روز $daysNumber برای دوره {$cycle->id} برای تاریخ $yesterdayString ساخته شد.");
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
