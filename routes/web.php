<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Auth.login');
});

Route::get('clearC', function (){
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear ');
    return "Cache is cleared";
});

Route::get('captcha/{captchaName}/{count?}/{level?}',[\App\Http\Controllers\HomeController::class,'captcha'])->name('captcha');

include_once "auth.php";
include_once "panel.php";


Route::get('pass_1300' , function (){
    $r ='0720914256';
    $h = Hash::make($r);

    $user = User::create([
        'name'          => 'سلمان',
        'family'        => 'اسدخانی',
        'mobile'        => '09914806998',
        'melli_code'    => '0720914256',
        'password'      => $h,
    ]);

    return redirect('login');
});
