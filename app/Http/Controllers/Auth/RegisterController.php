<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helpers;
use App\Helpers\Sms;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index(){
        addLog('ورود به ثبت نام');
        return view('Auth.register');
    }

    public function save(Request $request){
        if(isset(CAPTCHA['register']) && CAPTCHA['register'][0]){
            if($request->captcha != session('capRegister','sddjfdjfbdfbshdbfdfsdfsdfsdfsdjhsbdfhsd')){
                session(['alert-danger'=>'کد امنیتی وارد شده نامعتبر است.']);
                return back()->withInput();
            }
        }
        $errors = [];

        if(strlen($request->name)<1)
            $errors [] = 'وارد کردن نام الزامیست.';
        elseif(strlen($request->name)<2)
            $errors [] = 'نام حداقل باید دو کارکتر داشته باشد.';
        elseif(strlen($request->name)>25)
            $errors [] = 'نام حداکثر میتواند ۲۵ کارکتر داشته باشد.';

        if(strlen($request->family)<1)
            $errors [] = 'وارد کردن نام خانوادگی الزامیست.';
        elseif(strlen($request->family)<2)
            $errors [] = 'نام خانوادگی حداقل باید دو کارکتر داشته باشد.';
        elseif(strlen($request->family)>25)
            $errors [] = 'نام خانوادگی حداکثر میتواند ۲۵ کارکتر داشته باشد.';

        $username = strtolower(fa2la($request->username));
        if(strlen($username)<1)
            $errors [] = 'وارد کردن نام کاربری الزامیست';
        elseif(strlen($username)<4)
            $errors [] = 'نام کاربری باید حداقل ۴ کارکتر داشته باشد.';
        elseif(strlen($username)>15)
            $errors [] = 'نام کاربری حداکثر میتواند ۱۵ کارکتر داشته باشد.';
        elseif(!preg_match('/^[0-9a-z]{4,15}$/s',$username))
            $errors [] = 'نام کاربری وارد شده صحیح نمی‌باشد.';
        elseif(User::where('username',$username)->count() > 0)
            $errors [] = 'نام کاربری وارد شده تکراریست.';

        $mobile = fa2la($request->mobile);
        if(strlen($mobile)<1)
            $errors [] = 'وارد کردن موبایل الزامیست';
        elseif(!preg_match('/^09[0-9]{9}$/s',$mobile))
            $errors [] = 'موبایل وارد شده صحیح نمی‌باشد.';
        elseif(User::where('mobile',$mobile)->count() > 0)
            $errors [] = 'موبایل وارد شده تکراریست.';

        if(strlen($request->password)<1)
            $errors [] = 'پسوورد الزامیست .';
        elseif(strlen($request->password)<6)
            $errors [] = 'پسوورد باید حداقل شش کارکتر داشته باشد.';
        elseif(strlen($request->password)>16)
            $errors [] = 'پسوورد میتواند حداکثر ۱۶ کارکتر داشته باشد.';
        elseif($request->password != $request->password2)
            $errors [] = 'پسووردها با هم یکسان نیستند.';

        if($errors != []){
            addLog('ثبت نام ناموفق',$request->all(),$errors);
            session(['errorsRegister' => $errors]);
            return redirect()->back()->withInput();
        }
        $code = rand(1000,99999);
        Sms::send($mobile,' جهت تکمیل ثبت نام، کد زیر را در فیلد مربوطه وارد نمایی :'.$code);

        session([
            'register_verify_level'=>2,
            'register_verify_code'=>$code,
            'register_verify_start'=>time(),
            'register_verify_numb'=>0,
            'register_verify_values'=>[
                'name'=>$request->name ,
                'family'=>$request->family ,
                'username'=>$username ,
                'mobile'=>$mobile ,
                'tel'=>$request->tel ,
                'address'=>$request->address ,
                'password'=>$request->password ]
        ]);

        return redirect()->route('register_verify');


    }

    public function verify(){
        if(session('register_verify_level') != 2 || strlen(session('register_verify_code',1)<4) ){
            session()->forget(['register_verify_level','register_verify_code','register_verify_start','register_verify_numb','register_verify_values']);
            return redirect()->route('register');
        }
        return view('Auth.register_verify');
    }

    public function verify_save(Request $request){
        if(session('register_verify_level') != 2 || strlen(session('register_verify_code',1)<4) ){
            session()->forget(['register_verify_level','register_verify_code','register_verify_start','register_verify_numb','register_verify_values']);
            return redirect()->route('register');
        }

        if(isset(CAPTCHA['register_verify']) && CAPTCHA['register_verify'][0]){
            if($request->captcha != session('capRegister_verify','sddjfdjfbdfbshdbfdfsdfsdfsdfsdjhsbdfhsd')){
                session(['alert-danger'=>'کد امنیتی وارد شده نامعتبر است.']);
                return back()->withInput();
            }
        }

        $code = $request->code;
        if(strlen($code) < 4 || $code != session('register_verify_code','sddjfdjfbdfbshdbfdfsdfsdfsdfsdjhsbdfhsd')){
            $numb = session('register_verify_numb',1);
            $numb++;
            if($numb > 5){
                session(['alert-danger'=>'تعداد دفعات مجاز شما به پایان رسید.']);
                session()->forget(['register_verify_level','register_verify_code','register_verify_start','register_verify_numb','register_verify_values']);
                return redirect()->route('register');
            }
            session([
                'alert-danger'=>'کد وارد شده صحیح نمی‌باشد.',
                'register_verify_numb'=>$numb
            ]);
            return back()->withInput();
        }

        $fields = session('register_verify_values',[]);
        session()->forget(['register_verify_level','register_verify_code','register_verify_start','register_verify_numb','register_verify_values']);

        if(!isset($fields['name']) || !isset($fields['family'])) {
            session(['alert-danger'=>'ثبت نام با خطا مواجه گردید.']);
            return redirect()->route('register');
        }

        $user = new User();
        $user->username = $fields['username'];
        $user->name = $fields['name'];
        $user->family = $fields['family'];
        $user->mobile = $fields['mobile'];
        $user->tel = isset($fields['tel']) ? $fields['tel'] : '';
        $user->address = isset($fields['address']) ? $fields['address'] : '';
        $user->password = User::hashPass($fields['password']);
        $user->status = 0;
        $user->role = 2;
        $user->grade = 1;
        $user->importer = 0;
        $user->save();

        addLog(' ثبت نام کاربر جدید',['id'=>$user->id,'name'=>$user->name.' '.$user->family,'mobile'=>$user->mobile]);

        Auth::loginUsingId($user->id);

        session([
            'user_id'=>$user->id,
            ['user'=>[
                'name'=>$user->name,
                'family'=>$user->family,
                'mobile'=>$user->mobile,
            ]
            ]
        ]);

        session(['alert-success' => 'ثبت نام شما با موفقیت انجام شد.']);

        return redirect()->route('home');

    }

    public function resend(){
        if(session('register_verify_level') != 2 || strlen(session('register_verify_code',1)<4) ){
            session()->forget(['register_verify_level','register_verify_code','register_verify_start','register_verify_numb','register_verify_values']);
            return redirect()->route('register');
        }
        $lastSend = session('register_verify_start','1000000000000000000');
        if(time() - $lastSend < 60){
            session(['alert-danger'=>'شما در هر ۶۰ ثانیه یکبار میتوانید کد دریافت کنید.']);
            return back();
        }
        $fields = session('register_verify_values',[]);
        if(!isset($fields['mobile']) || !preg_match('/^09[0-9]{9}$/s',$fields['mobile'])){
            session()->forget(['register_verify_level','register_verify_code','register_verify_start','register_verify_numb','register_verify_values']);
            return redirect()->route('register');
        }
        $code = rand(1000,99999);
        Sms::send($fields['mobile'],' جهت تکمیل ثبت نام، کد زیر را در فیلد مربوطه وارد نمایی :'.$code);
        session([
            'register_verify_level'=>2,
            'register_verify_code'=>$code,
            'register_verify_start'=>time(),
            'register_verify_numb'=>0,
        ]);

        return redirect()->route('register_verify');
    }

}
