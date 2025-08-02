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

        $usedFeedTypes = $cycle->dailyReports
            ->whereNotNull('feed_type')
            ->where('feed_count', '>', 0)
            ->pluck('feed_type')
            ->unique();

        $feedSummary = [];
        $grandTotalWeight = 0;


        foreach ($usedFeedTypes as $feedName) {

            $purchase = $feedPurchases->get($feedName);

            if ($purchase && $purchase->total_bags > 0) {
                $avgWeightPerBag = $purchase->total_weight / $purchase->total_bags;
                $bagsUsed = $cycle->dailyReports->where('feed_type', $feedName)->sum('feed_count');
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
        $daily = DailyReport::with('cycle')->where('id', $request->daily_id)->first();

        $daily->update([
            'breeding_cycle_id' => $daily->cycle->id,
            'mortality_count' =>fa2la( $request->mortality),
            'feed_type' => $request->feed_type,
            'feed_count' =>fa2la( $request->feed),
            'description' => $request->desc,
            'actions_taken' => $request->actions,
        ]);


        $this->updateTotalMortality($daily->cycle->id);

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
