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

    public function getFeedConsumptionAnalytics(): array
    {

        $summarizedFeeds = $this->feeds
            ->groupBy(fn($feed) => trim($feed->name))
            ->map(function ($group) {
                return [
                    'total_weight' => $group->sum('quantity'),
                    'total_bags' => $group->sum('bag_count'),
                ];
            });



        $avgWeights = $summarizedFeeds
            ->where('total_bags', '>', 0)
            ->map(fn($feed) => $feed['total_weight'] / $feed['total_bags']);


        $allConsumptions = $this->dailyReports->pluck('feedConsumptions')->flatten();

        $consumptionsByType = $allConsumptions->groupBy(function ($consumption) {
            return trim($consumption->feed_type);
        });

        $feedSummary = [];
        $grandTotalWeightConsumed = 0;

        foreach ($consumptionsByType as $feedName => $consumptions) {
            $avgWeight = $avgWeights->get($feedName, 0);
            $bagsUsed = $consumptions->sum('bag_count');
            $totalWeightConsumed = $bagsUsed * $avgWeight;

            $feedSummary[] = [
                'name' => $feedName,
                'total_weight_used' => round($totalWeightConsumed),
                'bags_used' => $bagsUsed,
            ];
            $grandTotalWeightConsumed += $totalWeightConsumed;
        }

        foreach ($this->dailyReports as $report) {
            $dailyFeedWeight = $report->feedConsumptions->sum(function ($consumption) use ($avgWeights) {
                $avgWeight = $avgWeights->get(trim($consumption->feed_type), 0);
                return $consumption->bag_count * $avgWeight;
            });

            $report->feed_daily_used = round($dailyFeedWeight);
        }


        return [
            'summary' => $feedSummary,
            'grand_total_kg' => round($grandTotalWeightConsumed),
        ];
    }
}
