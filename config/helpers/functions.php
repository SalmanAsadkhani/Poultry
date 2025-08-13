<?php

use App\LogActivity;



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


if (!function_exists('captcha')) {
    function captcha (){

        session_start();

        $code = rand(10000, 99999);
        $_SESSION['captcha'] = $code;


        $width = 100;
        $height = 40;
        $image = imagecreate($width, $height);


        $bg = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);

        imagestring($image, 5, 25, 10, $code, $textColor);


        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);

    }
}
