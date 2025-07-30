<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBreeding;
use App\Http\Requests\StoreDaily;
use App\Models\BreedingCycle;
use App\Models\DailyReport;
use Hekmatinasser\Verta\Facades\Verta;

class BreedingCyclesController extends Controller
{



    public function index()
    {
        $breedingCycles = BreedingCycle::all();
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
        $breedingCycle = BreedingCycle::with('dailyReports')->where('id' , $id)->first();

        $total_mortality = $breedingCycle->dailyReports()->sum('mortality_count');

        $total_feed = $breedingCycle->dailyReports()->sum('feed_count');

        $startDate = Verta::parse($breedingCycle->start_date)->addDays(1);

        $chickAge = $startDate->diffDays(Verta::now()) + 1;

        $today = Verta::now()->subDay()->format('Y/m/d');



        return view('breeding.show', compact('breedingCycle' , 'total_mortality' , 'chickAge' , 'total_feed' , 'today'));
    }


    public function daily_confirm(StoreDaily $request)
    {
        $daily = DailyReport::with('cycle')->where('id', $request->daily_id)->first();

        $daily->update([
            'breeding_cycle_id' => $daily->cycle->id,
            'mortality_count' =>fa2la( $request->mortality),
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
