<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BreedingCycle extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'start_date',
        'end_date',
        'chicken_count',
        'chicken_price',
        'notes',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function feedCategories()
    {
        return $this->hasMany(FeedCategory::class);
    }

    public function drugCategories()
    {
        return $this->hasMany(DrugCategory::class);
    }

    public function miscellaneousCategories()
    {
        return $this->hasMany(MiscellaneousCategory::class);
    }

    public function feeds(): HasMany
    {
        return $this->hasMany(Feed::class);
    }

    public function chickenIncomeCategories(): HasMany
    {
        return $this->hasMany(ChickenSalesCategory::class);
    }

    public function miscIncomeCategories(): HasMany
    {
        return $this->hasMany(MiscellaneousIncomeCategory::class);
    }

    public function getFeedConsumptionAnalytics(): array
    {
        // ۱. آماده‌سازی داده‌ها
        // از همان خریدهایی که در کنترلر بارگذاری شده استفاده می‌کنیم
        $inventory = $this->feeds;

        // ❌ این خط را حذف می‌کنیم چون گزارش‌ها از قبل در کنترلر بارگذاری شده‌اند
        // $sortedDailyReports = $this->dailyReports()->with('feedConsumptions')->orderBy('report_date', 'asc')->get();

        // ۲. مقداردهی اولیه متغیرهای نتیجه (بدون تغییر)
        $grandTotalWeightConsumed = 0;
        $summaryMap = [];

        // ۳. شبیه‌سازی مصرف روزانه - حالا مستقیماً روی رابطه از قبل بارگذاری شده کار می‌کنیم
        // ✅ این خط جایگزین می‌شود
        foreach ($this->dailyReports as $report) {
            $dailyFeedWeight = 0;

            foreach ($report->feedConsumptions as $consumption) {
                $bagsToConsume = $consumption->bag_count;
                $feedTypeToConsume = trim($consumption->feed_type);

                while ($bagsToConsume > 0) {
                    $batch = $inventory->first(function ($feed) use ($feedTypeToConsume) {
                        return trim($feed->name) === $feedTypeToConsume && $feed->remaining_bags > 0;
                    });

                    if (!$batch) {
                        break;
                    }

                    $avgWeightPerBagInBatch = ($batch->bag_count > 0) ? ($batch->quantity / $batch->bag_count) : 0;
                    $bagsTakenFromBatch = min($bagsToConsume, $batch->remaining_bags);
                    $weightConsumedFromBatch = $bagsTakenFromBatch * $avgWeightPerBagInBatch;
                    $dailyFeedWeight += $weightConsumedFromBatch;
                    $grandTotalWeightConsumed += $weightConsumedFromBatch;
                    $batch->remaining_bags -= $bagsTakenFromBatch;
                    $bagsToConsume -= $bagsTakenFromBatch;

                    if (!isset($summaryMap[$feedTypeToConsume])) {
                        $summaryMap[$feedTypeToConsume] = ['name' => $feedTypeToConsume, 'total_weight_used' => 0, 'bags_used' => 0];
                    }
                    $summaryMap[$feedTypeToConsume]['total_weight_used'] += $weightConsumedFromBatch;
                    $summaryMap[$feedTypeToConsume]['bags_used'] += $bagsTakenFromBatch;
                }
            }

            // این خط حالا آبجکت گزارش اصلی را که به view فرستاده می‌شود، اصلاح می‌کند
            $report->feed_daily_used = round($dailyFeedWeight);
        }

        // بقیه کد بدون تغییر باقی می‌ماند...
        $feedSummary = array_values($summaryMap);
        foreach ($feedSummary as &$summaryItem) {
            $summaryItem['total_weight_used'] = round($summaryItem['total_weight_used']);
        }

        return [
            'summary' => $feedSummary,
            'grand_total_kg' => round($grandTotalWeightConsumed),
        ];
    }


}
