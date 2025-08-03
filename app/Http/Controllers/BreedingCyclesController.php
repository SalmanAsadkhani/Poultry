<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBreeding;
use App\Http\Requests\StoreDaily;
use App\Models\BreedingCycle;
use App\Models\DailyReport;
use Hekmatinasser\Verta\Facades\Verta;
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
            'dailyReports' => fn($query) => $query->orderBy('date', 'asc')
        ])
            ->withSum('dailyReports as total_mortality', 'mortality_count')
            ->findOrFail($id);


        $startDate = Verta::parse($cycle->start_date);
        $chickAge = $startDate->diffDays(Verta::now()) + 1;

        $feedPurchases = $cycle->feeds()
            ->select('name', DB::raw('SUM(quantity) as total_weight'), DB::raw('SUM(bag_count) as total_bags'))
            ->groupBy('name')
            ->get()
            ->keyBy('name');


        $allConsumptions = $cycle->dailyReports()->with('feedConsumptions')->get()->pluck('feedConsumptions')->flatten();

        $consumptionsByType = $allConsumptions->groupBy('feed_type');

        $feedSummary = [];
        $grandTotalWeight = 0;


        foreach ($consumptionsByType as $feedName => $consumptions) {
            $purchase = $feedPurchases->get($feedName);

            if ($purchase && $purchase->total_bags > 0) {
                $avgWeightPerBag = $purchase->total_weight / $purchase->total_bags;
                $bagsUsed = $consumptions->sum('bag_count');
                $totalWeightConsumed = $bagsUsed * $avgWeightPerBag;

                $feedSummary[] = [
                    'name' => $feedName,
                    'total_weight_used' => round($totalWeightConsumed),
                    'bags_used' => $bagsUsed,
                ];
                $grandTotalWeight += round($totalWeightConsumed);
            }
        }

        return view('breeding.show', compact(
            'cycle',
            'chickAge',
            'feedSummary',
            'grandTotalWeight'
        ));
    }

    public function store(StoreDaily $request)
    {

        $daily = DailyReport::findOrFail($request->daily_id);

        $daily->update([
            'mortality_count' => fa2la($request->mortality),
            'description'     => $request->desc,
            'actions_taken'   => $request->actions,
        ]);

        if ($request->has('feeds')) {
            $feedData = $request->feeds;
            $existingIds = [];

            foreach ($feedData as $feed) {

                $consumption = $daily->feedConsumptions()->updateOrCreate(
                    ['id' => $feed['id']],
                    [
                        'feed_type' => $feed['type'],
                        'bag_count' => fa2la($feed['bags']),
                    ]
                );
                $existingIds[] = $consumption->id;
            }

            $daily->feedConsumptions()->whereNotIn('id', $existingIds)->delete();
        }
        $this->updateTotalMortality($daily->breeding_cycle_id);

        return response()->json([
            'res' => 10,
            'mySuccess' => 'گزارش با موفقیت ثبت گردید',
            'myAlert' => ""
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
