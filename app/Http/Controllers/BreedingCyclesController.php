<?php

namespace App\Http\Controllers;

use App\Http\Requests\Add_Breeding;
use App\Http\Requests\Add_daily;
use App\Models\BreedingCycle;
use App\Models\DailyReport;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;

class BreedingCyclesController extends Controller
{



    public function index()
    {
        $breedingCycles = BreedingCycle::all();
        return view('breeding.index' , compact('breedingCycles'));

    }

    public function add_breeding(Add_Breeding $request)
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
        $breedingCycle = BreedingCycle::with('dailyReports')->findOrFail($id)->first();
        $total_mortality = $breedingCycle->dailyReports()->sum('mortality_count');

        $today = Verta::now()->format('Y/m/d');
        $startDate = Verta::parse($breedingCycle->start_date);
        $firstReportDate = $startDate->addDay();
        $chickAge = $firstReportDate->diffDays(Verta::now()) + 1;



        return view('breeding.show', compact('breedingCycle' , 'total_mortality' , 'chickAge'));
    }


    public function daily_confirm(Add_daily $request)
    {
        $daily = DailyReport::with('cycle')->where('id', $request->daily_id)->first();

        $daily->update([
            'breeding_cycle_id' => $daily->cycle->id,
            'mortality_count' =>fa2la( $request->mortality),
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
