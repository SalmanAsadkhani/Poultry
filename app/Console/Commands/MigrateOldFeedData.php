<?php

namespace App\Console\Commands;

use App\Models\DailyReport;
use App\Models\FeedConsumption;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateOldFeedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-feed-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates old feed data from daily_reports to the new feed_consumptions table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('شروع فرآیند انتقال داده‌های دان...');

        // برای جلوگیری از خطا، فرآیند را داخل یک transaction اجرا می‌کنیم
        DB::transaction(function () {
            // تمام گزارش‌هایی که اطلاعات دان دارند را پیدا کن
            $reportsToMigrate = DailyReport::whereNotNull('feed_type')
                ->where('feed_count', '>', 0)
                ->get();

            if ($reportsToMigrate->isEmpty()) {
                $this->info('هیچ داده‌ای برای انتقال یافت نشد.');
                return;
            }

            // یک نوار پیشرفت برای نمایش وضعیت ایجاد می‌کنیم
            $progressBar = $this->output->createProgressBar($reportsToMigrate->count());
            $progressBar->start();

            foreach ($reportsToMigrate as $report) {
                // یک رکورد جدید در جدول مصرف دان ایجاد کن
                FeedConsumption::create([
                    'daily_report_id' => $report->id,
                    'feed_type'       => trim($report->feed_type), // trim برای حذف فاصله‌های اضافی احتمالی
                    'bag_count'       => $report->feed_count,
                ]);

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();
            $this->info('انتقال داده‌ها با موفقیت انجام شد. ' . $reportsToMigrate->count() . ' رکورد منتقل شد.');
        });

        return 0;
    }
}
