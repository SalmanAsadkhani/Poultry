<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReport extends Model
{
    protected $fillable = [
        'breeding_cycle_id',
        'date',
        'days_number',
        'mortality_count',
        'total_mortality',
        'description',
        'feed_type',
        'actions_taken',
        'status',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(BreedingCycle::class, 'breeding_cycle_id');
    }

    // متد برای محاسبه جمع تلفات
    public static function updateTotalMortality($dailyReportId)
    {
        // پیدا کردن گزارش روزانه
        $dailyReport = self::find($dailyReportId);

        // پیدا کردن گزارشات روزانه قبلی برای همان دوره پرورش
        $reports = self::where('breeding_cycle_id', $dailyReport->breeding_cycle_id)
            ->where('date', '<=', $dailyReport->date)
            ->orderBy('date')
            ->get();

        // محاسبه جمع تلفات جدید
        $totalMortality = 0;
        foreach ($reports as $report) {
            $totalMortality += $report->mortality_count;
        }

        // بروزرسانی مقدار total_mortality
        $dailyReport->total_mortality = $totalMortality;
        $dailyReport->save();
    }



    public function getDescriptionAttribute($value)
    {
        // اگر مقدار از قبل در دیتابیس به صورت آرایه کست شده باشد
        if (is_array($value)) {
            return implode(', ', $value);
        }

        // اگر مقدار یک رشته جیسون باشد، آن را به آرایه تبدیل کرده و سپس به رشته تبدیل می‌کند
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return implode(', ', $decoded);
        }

        // در غیر این صورت، خود مقدار را برگردان
        return $value;
    }

    // شما می‌توانید برای هر فیلد دیگری که آرایه است، یک Accessor مشابه بسازید
    // مثلا برای فیلد actions_taken
    public function getActionsTakenAttribute($value)
    {
        if (is_array($value)) {
            return implode(', ', $value);
        }
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return implode(', ', $decoded);
        }
        return $value;
    }


}

