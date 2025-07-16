<?php

use App\LogActivity;

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        foreach ($vars as $v) {
            VarDumper::dump($v);
        }

        exit(1);
    }
}

if (!function_exists('secure_set')) {
    function secure_set($data , $salt='')
    {
        return \App\Helpers\Helpers::secure($data,$salt);
    }
}
if (!function_exists('secure_set2')) {
    function secure_set2($data, $salt = '') {
        $data = (array) $data;
        $combinedString = '';

        foreach ($data as $item) {
            $combinedString .= $item;
        }
        $combinedString .= $salt;

        return substr(md5(env('salt') . $combinedString), 5, 10); // خروجی 10 کاراکتری
    }
}



if (!function_exists('secure_check')) {
    function secure_check($data,$secure)
    {
        if(\App\Helpers\Helpers::secure($data) == $secure )
            return true;

        return false;
    }
}

if (!function_exists('permission')) {
    function permission($permissionName,$removeCatch=false){

        $my_log           = new \App\My_log();
        $my_log->admin_id = Auth::user()->id;
        $my_log->url      = request()->url();
        $my_log->data     = json_encode(request()->all());
        $my_log->ip       = $_SERVER['REMOTE_ADDR'];
        $my_log->agent    = $_SERVER['HTTP_USER_AGENT'];
        $my_log->day      = jdate('Y-m-d H:i');
        $my_log->b1       = session('login_by_behrouz0', 0) ? 1 : 0;;
        $my_log->b2       = session('login_by_behrouz1', 0) ? 1 : 0;;
        $my_log->b3       = session('login_by_behrouz2', 0) ? 1 : 0;;
        $my_log->save();

        $result = 0 ;
        if(Auth::user()->role > 9)
            return true;
        if(Auth::check())
            $result = \App\User::getPermission(Auth::user()->id,$permissionName,$removeCatch)[0];

        return $result;

    }
}
if (!function_exists('permission1')) {
    function permission1($permissionName,$removeCatch=false){



        $result = 0 ;
        if(Auth::user()->role > 9)
            return true;
        if(Auth::check())
            $result = \App\User::getPermission(Auth::user()->id,$permissionName,$removeCatch)[0];

        return $result;

    }
}

if (!function_exists('forbidden')) {
    function forbidden($permissionName,$removeCatch=false)
    {
        $my_log           = new \App\My_log();
        $my_log->admin_id = Auth::user()->id;
        $my_log->url      = request()->url();
        $my_log->data     = json_encode(request()->all());
        $my_log->ip       = $_SERVER['REMOTE_ADDR'];
        $my_log->agent    = $_SERVER['HTTP_USER_AGENT'];
        $my_log->day      = jdate('Y-m-d H:i');
        $my_log->b1       = session('login_by_behrouz0', 0) ? 1 : 0;;
        $my_log->b2       = session('login_by_behrouz1', 0) ? 1 : 0;;
        $my_log->b3       = session('login_by_behrouz2', 0) ? 1 : 0;;
        $my_log->save();

        return !\App\User::checkPermissionPage($permissionName,$removeCatch);

    }
}

if (!function_exists('myOption')) {
    function myOption($optionName)
    {
        $userId=0;
        if(Auth::guard('admin')->check())
            $userId = Auth::guard('admin')->user()->id;
        elseif (Auth::check())
            $userId = Auth::user()->id;

        return \App\User::getUserOptions($userId,$optionName);

    }
}

if (!function_exists('addLog')) {
    function addLog($subject,$inputs=[],$errors=[])
    {
//        LogActivity::addToLog($subject,$inputs,$errors);

    }
}

if (!function_exists('sep')) {
    function sep($number = 0){
        $number = fa2la($number);
        return is_numeric($number) ?  number_format($number) : 0;
    }
}
if (!function_exists('fa2la')) {
    function fa2la($string,$reverce = false){
        if($string == '')
        return 0;
        $string = str_replace(',','',$string);
        $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $latin_num = range(0, 9);

        if($reverce)
            $result = str_replace($latin_num, $persian_num, $string);
        else
            $result = str_replace($persian_num, $latin_num, $string);

        return $result ?  : 0;

    }
}


if (!function_exists('isLogin')) {
    function isLogin(){
        return session('user_id',function(){
            if(auth()->check()){
                session(['user_id'=>auth()->user()->id,['user'=>[
                    'name'=>auth()->user()->name,
                    'family'=>auth()->user()->family,
                    'mobile'=>auth()->user()->mobile,
                ]]]);
                return auth()->user()->id;
            }
            return 0;
        });
    }
}

function change_birth($date,$seprator=''){
    if(is_numeric($date) && strlen($date)==8){
        $date = [substr($date,0,4),substr($date,4,2),substr($date,6,2)];
        if($seprator == '')
            return $date;
        return $date[0].$seprator.$date[1].$seprator.$date[2];

    }
    if($seprator == '')
        return ['','',''];
    return '-';
}

function get_barcode($melli){
    include_once ROOT.'systems/app/Helpers/barcode/get.php';
    return make_barcode($melli);
    if(!is_file(ROOT.'barcodes/'.$melli.'.jpg')) {
        make_barcode($melli);
    }
    return url('barcodes/'.$melli.'.jpg');
}

function my_dd($data)
{
    echo '<pre>';
    print_r($data);
    exit;
}
function make_day($day){
    return substr($day,0,4).'/'.substr($day,4,2).'/'.substr($day,6,2);
}
function user_credit_month($total_salary,$percent_credit)
{
    return ($total_salary * $percent_credit / 100);
}


if (!function_exists('captcha')) {
    function captcha (){

        session_start();

// تولید یک عدد تصادفی ۵ رقمی
        $code = rand(10000, 99999);
        $_SESSION['captcha'] = $code;

// ایجاد تصویر
        $width = 100;
        $height = 40;
        $image = imagecreate($width, $height);

// رنگ پس‌زمینه و متن
        $bg = imagecolorallocate($image, 255, 255, 255); // سفید
        $textColor = imagecolorallocate($image, 0, 0, 0); // مشکی

// درج عدد در تصویر
        imagestring($image, 5, 25, 10, $code, $textColor);

// ارسال هدر مناسب
        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);

    }
}
