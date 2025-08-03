<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedConsumption extends Model
{
    protected $fillable = [ 'daily_report_id','feed_type', 'bag_count'];

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }
}
