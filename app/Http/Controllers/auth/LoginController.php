<?php

namespace App\Http\Controllers\auth;

use App\Helpers\Helpers;
use App\Helpers\Sms;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreLogin;
use App\Models\User;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller {

    public function index(){


        return view('Auth.login');
    }

    public function login(StoreLogin $request){

        $user = User::where('melli_code', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)){

            return back()->with('error','نام کاربری یا رمز عبور اشتباه است ');
        }



        Auth::login($user, true);

        return redirect()->route('home');


    }



    public function logout(){

        Auth::logout();
        return redirect()->route('login');

    }



}
