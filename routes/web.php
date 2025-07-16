<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
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

