<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\BreedingCycle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function captcha($captchaName,$count=5,$level=3){
        Helpers::captcha($captchaName,$count,$level);
    }

    public function index()
    {
//        return view('panel.dashboard');
        $breedingCycles = BreedingCycle::all();
        return view('breeding.index' , compact('breedingCycles'));
    }
}
