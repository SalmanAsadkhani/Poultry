<?php

namespace App\Http\Controllers;

use App\Http\Requests\Add_Breeding;
use App\Http\Requests\Add_daily;
use App\Models\BreedingCycle;
use App\Models\DailyReport;
use Illuminate\Http\Request;

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
        $breedingCycle = BreedingCycle::with('dailyReports')->findOrFail($id);

        return view('breeding.show', compact('breedingCycle'));
    }

    public function daily_confirm(Add_daily  $request)
    {
        $daily = DailyReport::create([
            'breeding_cycle_id' => '1' ,
            'mortality_count' => $request->mortality,
            'description'  =>$request->desc ,
            'actions_taken' => $request->actions
        ]);

        if($daily){
            return response()->json([
                'res' => 10,
                'mySuccess' => 'گزارش با موفقیت ثبت گردید',
                'myAlert' =>""
            ]);
        }

        return  response()->json([
            'res' => 1,
            'mySuccess' => '',
        ]);
    }
}
