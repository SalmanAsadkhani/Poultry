<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLogin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Exceptions\ThrottleRequestsException;


class LoginController extends Controller {

    public function index(){
        return view('Auth.login');
    }




    public function login(StoreLogin $request)
    {
        try {
            $user = User::where('melli_code', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->with('error', 'نام کاربری یا رمز عبور اشتباه است');
            }

            Auth::login($user, true);
            return redirect()->route('home');
        } catch (ThrottleRequestsException $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function logout(){

        Auth::logout();
        return redirect()->route('login');

    }



}
