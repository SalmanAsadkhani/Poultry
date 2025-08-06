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
            'dailyReports.feedConsumptions',
            'feeds'
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
        ]);
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
            $processedIds = [];

            foreach ($feedData as $feed) {

                $consumption = $daily->feedConsumptions()->updateOrCreate(
                    ['id' => $feed['id']],
                    [
                        'feed_type' => $feed['type'],
                        'bag_count' => fa2la($feed['bags']),
                    ]
                );

                $processedIds[] = $consumption->id;
            }


            $daily->feedConsumptions()->whereNotIn('id', $processedIds)->delete();
        } else {

            $daily->feedConsumptions()->delete();
        }


        $this->updateTotalMortality($daily->breeding_cycle_id);

        return response()->json([
            'res'       => 10,
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
