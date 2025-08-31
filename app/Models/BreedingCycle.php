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

    // in app/Models/BreedingCycle.php

    public function getFeedConsumptionAnalytics(): array
    {

        $inventory = [];
        foreach ($this->feeds->all() as $feed) {
            $inventory[$feed->id] = $feed->getAttributes();
        }

        $sortedDailyReports = $this->dailyReports->sortBy('created_at');


        $grandTotalWeightConsumed = 0;
        $summaryMap = [];


        foreach ($sortedDailyReports as $report) {
            $dailyFeedWeight = 0;

            foreach ($report->feedConsumptions as $consumption) {
                $bagsToConsume = $consumption->bag_count;
                $feedTypeToConsume = trim($consumption->feed_type);

                while ($bagsToConsume > 0) {
                    $batchId = null;

                    foreach ($inventory as $id => &$batchData) {
                        if (trim($batchData['name']) === $feedTypeToConsume && $batchData['remaining_bags'] > 0) {
                            $batchId = $id;
                            break;
                        }
                    }

                    if (is_null($batchId)) {
                        break;
                    }


                    $batch = &$inventory[$batchId];

                    $avgWeightPerBagInBatch = ($batch['bag_count'] > 0) ? ($batch['quantity'] / $batch['bag_count']) : 0;
                    $bagsTakenFromBatch = min($bagsToConsume, $batch['remaining_bags']);
                    $weightConsumedFromBatch = $bagsTakenFromBatch * $avgWeightPerBagInBatch;

                    $dailyFeedWeight += $weightConsumedFromBatch;
                    $grandTotalWeightConsumed += $weightConsumedFromBatch;

                    $batch['remaining_bags'] -= $bagsTakenFromBatch;
                    $bagsToConsume -= $bagsTakenFromBatch;


                    if (!isset($summaryMap[$feedTypeToConsume])) {
                        $summaryMap[$feedTypeToConsume] = ['name' => $feedTypeToConsume, 'total_weight_used' => 0, 'bags_used' => 0];
                    }
                    $summaryMap[$feedTypeToConsume]['total_weight_used'] += $weightConsumedFromBatch;
                    $summaryMap[$feedTypeToConsume]['bags_used'] += $bagsTakenFromBatch;
                }
            }
            $report->feed_daily_used = round($dailyFeedWeight);
        }

        $feedSummary = array_values($summaryMap);
        foreach ($feedSummary as &$summaryItem) {
            $summaryItem['total_weight_used'] = round($summaryItem['total_weight_used']);
        }

         $finalInventory = array_map(fn($item) => ['name' => $item['name'], 'remaining' => $item['remaining_bags']], $inventory);

        return [
            'summary' => $feedSummary,
            'grand_total_kg' => round($grandTotalWeightConsumed),
            'inventory_left' => $finalInventory,
        ];

    }


}
