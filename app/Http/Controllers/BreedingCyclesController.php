<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBreeding;
use App\Http\Requests\StoreDaily;
use App\Models\BreedingCycle;
use App\Models\DailyReport;
use App\Models\FeedConsumption;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BreedingCyclesController extends Controller
{

    public function index()
    {

        $user = auth()->user()->id;
        $breedingCycles = BreedingCycle::where('user_id' ,$user )->get();

        return view('breeding.index' , compact('breedingCycles'));

    }

    public function add_breeding(StoreBreeding $request)
    {
          $breeding =  BreedingCycle::create([
             'user_id' => auth()->user()->id ,
             'name'  =>$request->Name,
             'start_date' =>$request->Date,
             'chicken_count' => $request->Count
         ]);

          if($breeding){
              return response()->json([
                  'res' => 10,
                  'mySuccess' => 'دوره با موفقیت اضافه گردید',
                  'myAlert' =>""
              ]);
          }

          return  response()->json([
              'res' => 1,
              'myAlert' =>"خطایی رخ داده است"
          ]);

    }

    public function show($id)
    {
        $cycle = BreedingCycle::with([
            'dailyReports' => function ($query) {
                $query->with('feedConsumptions')->orderBy('created_at', 'asc');
            },
            'feeds' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }
        ])
            ->withSum('dailyReports as total_mortality', 'mortality_count')
            ->findOrFail($id);

        $feedAnalytics = $cycle->getFeedConsumptionAnalytics();

        $chickAge = Verta::parse($cycle->start_date)->diffDays(Verta::now()) + 1;


        return view('breeding.show', [
            'cycle' => $cycle,
            'chickAge' => $chickAge,
            'feedSummary' => $feedAnalytics['summary'],
            'grandTotalWeight' => $feedAnalytics['grand_total_kg'],
            'inventory_left' => $feedAnalytics['inventory_left'],
        ]);
    }

//    public function store(StoreDaily $request)
//    {
//
//        $daily = DailyReport::findOrFail($request->daily_id);
//
//        $daily->update([
//            'mortality_count' => fa2la($request->mortality),
//            'description'     => $request->desc,
//            'actions_taken'   => $request->actions,
//        ]);
//
//
//        if ($request->has('feeds')) {
//            $feedData = $request->feeds;
//            $processedIds = [];
//
//            foreach ($feedData as $feed) {
//
//                $consumption = $daily->feedConsumptions()->updateOrCreate(
//                    ['id' => $feed['id']],
//                    [
//                        'feed_type' => $feed['type'],
//                        'bag_count' => fa2la($feed['bags']),
//                    ]
//                );
//
//                $processedIds[] = $consumption->id;
//            }
//            $daily->feedConsumptions()->whereNotIn('id', $processedIds)->delete();
//        } else {
//
//            $daily->feedConsumptions()->delete();
//        }
//
//
//        $this->updateTotalMortality($daily->breeding_cycle_id);
//
//        return response()->json([
//            'res'       => 10,
//            'mySuccess' => 'گزارش با موفقیت ثبت گردید',
//        ]);
//    }

    public function store(StoreDaily $request): JsonResponse
    {

        $daily = DailyReport::findOrFail($request->daily_id);
        $cycle = BreedingCycle::findOrFail($daily->breeding_cycle_id);


        if ($request->has('feeds') && !empty($request->feeds)) {
            $requestedBagsByType = [];
            foreach ($request->feeds as $feed) {
                $type = trim($feed['type']);
                $bags = (int)fa2la($feed['bags']);
                if (!isset($requestedBagsByType[$type])) {
                    $requestedBagsByType[$type] = 0;
                }
                $requestedBagsByType[$type] += $bags;
            }

            foreach ($requestedBagsByType as $feedType => $requestedBags) {
                $totalPurchased = $cycle->feeds()->where('name', $feedType)->sum('bag_count');
                $totalConsumedOnOtherDays = FeedConsumption::where('feed_type', $feedType)
                    ->whereHas('dailyReport', function ($query) use ($cycle, $daily) {
                        $query->where('breeding_cycle_id', $cycle->id)
                            ->where('id', '!=', $daily->id);
                    })->sum('bag_count');

                $availableStock = $totalPurchased - $totalConsumedOnOtherDays;


                if ($requestedBags > $availableStock) {
                    return response()->json([
                        'res' => 1,
                        'myAlert' =>  "برای دان '{$feedType}'، شما فقط {$availableStock} کیسه موجودی دارید اما قصد ثبت {$requestedBags} کیسه را دارید.",
                    ]);
                }

            }

        }

        DB::transaction(function () use ($request, $daily) {
            $daily->update([
                'mortality_count' => fa2la($request->mortality),
                'description' => $request->desc,
                'actions_taken' => $request->actions,
            ]);

            if ($request->has('feeds') && !empty($request->feeds)) {
                $feedData = $request->feeds;
                $processedIds = [];
                foreach ($feedData as $feed) {
                    $consumption = $daily->feedConsumptions()->updateOrCreate(
                        ['id' => $feed['id'] ?? null],
                        ['feed_type' => $feed['type'], 'bag_count' => fa2la($feed['bags'])]
                    );
                    $processedIds[] = $consumption->id;
                }
                $daily->feedConsumptions()->whereNotIn('id', $processedIds)->delete();
            } else {
                $daily->feedConsumptions()->delete();
            }
        });

        // ۴. آپدیت آمار کلی
        $this->updateTotalMortality($cycle->id);

        // ۵. ارسال پاسخ موفقیت‌آمیز
        return response()->json([
            'res' => 10,
            'mySuccess' => 'گزارش با موفقیت ثبت گردید',
        ]);
    }
    private function updateTotalMortality(int $cycleId): void
    {
        $reports = DailyReport::where('breeding_cycle_id', $cycleId)->orderBy('date', 'asc')->get();

        $cumulativeMortality = 0;
        $updates = [];

        foreach ($reports as $report) {
            $cumulativeMortality += $report->mortality_count;

            if ($report->total_mortality != $cumulativeMortality) {
                $updateData = $report->getAttributes();

                $updateData['total_mortality'] = $cumulativeMortality;
                $updates[] = $updateData;
            }
        }

        if (!empty($updates)) {
            DailyReport::query()->upsert($updates, ['id'], ['total_mortality']);
        }
    }



}
