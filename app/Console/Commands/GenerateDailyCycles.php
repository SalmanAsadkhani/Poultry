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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $today = Verta::now()->format('Y/m/d');

        $activeCycles = BreedingCycle::where('status', 1)->whereNull('end_date')->get();

        foreach ($activeCycles as $cycle) {
            $startDate = Verta::parse($cycle->start_date);
            $days = $startDate->diffDays(Verta::now()) + 1;

            $exists = $cycle->dailyReports()->where('date', $today)->exists();

            if ($exists) {
                $this->info("⛔ گزارش امروز ($today) برای دوره {$cycle->id} قبلاً ثبت شده.");
                continue;
            }

            $feed = $this->getFeedTypeForDay($days);

            $cycle->dailyReports()->create([
                'date' => $today,
                'days_number' => $days,
                'feed_type' => $feed,
            ]);

            $this->info("✅ گزارش روز $days برای دوره {$cycle->id} ساخته شد.");
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
