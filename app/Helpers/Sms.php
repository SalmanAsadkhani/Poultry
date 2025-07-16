<?php
namespace App\Helpers;
class Sms{
private static $_line_number = '3000255137';
public static function get_token(){
    $APIKey = "e9e318e033e73fe3aa57251d";
    $SecretKey = "dehnavi_mohammad_nbhjbnbn767565765";

    //   $APIKey = "63f7962f7ee5707352f399ec";
    // $SecretKey = "b23dc68610af0969ababc686";
    $postData = array(
        'UserApiKey' => $APIKey,
        'SecretKey' => $SecretKey,
        'System' => 'php_rest_v_1_2'
    );
//    $postString = json_encode($postData);
//
//    $ch = curl_init("http://RestfulSms.com/api/Token");
//    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//        'Content-Type: application/json'
//    ));
//    curl_setopt($ch, CURLOPT_HEADER, false);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//    curl_setopt($ch, CURLOPT_POST, count($postData));
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
//
//    $result = curl_exec($ch);
//    curl_close($ch);
//    $resp = false;
//    $response = json_decode($result);

    $response = self::exec($postData,"http://RestfulSms.com/api/Token",false);
    if(is_object($response)){
        $resultVars = get_object_vars($response);
        if(is_array($resultVars)){
            @$IsSuccessful = $resultVars['IsSuccessful'];
            if($IsSuccessful == true){
                @$TokenKey = $resultVars['TokenKey'];
                $resp = $TokenKey;
            }
        }
    }

    return $resp;
}

public static function send($mobile, $Messages){
    $MobileNumbers = [$mobile];
    $token = self::get_token();

    if($token != false){
        @$SendDateTime = date("Y-m-d")."T".date("H:i:s");
        $postData = [
            'Messages' => [$Messages],
            'MobileNumbers' => $MobileNumbers,
            'LineNumber' => self::$_line_number,
            'SendDateTime' => $SendDateTime,
            'CanContinueInCaseOfError' => 'false'
        ];

        $result = self::exec($postData,"http://RestfulSms.com/api/MessageSend");

        if(is_object($result)){
            $array = get_object_vars($result);
            if(is_array($array)){
                $result = $array['Message'];
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }

    } else {
        $result = false;
    }
    return $result;
}

public static function send2($mobile, $Messages){
    $MobileNumbers = [$mobile];
    $token = self::get_token();

    if($token != false){
        @$SendDateTime = date("Y-m-d")."T".date("H:i:s");
        $postData = [
            'Messages' => [$Messages],
            'MobileNumbers' => $MobileNumbers,
            'LineNumber' => self::$_line_number,
            'SendDateTime' => $SendDateTime,
            'CanContinueInCaseOfError' => 'false'
        ];

        $result = self::exec($postData,"http://RestfulSms.com/api/MessageSend");
        file_put_contents(__DIR__.'/ttt.txt',json_encode($result));

        if(is_object($result)){
           
            $array = get_object_vars($result);
             return $array['IsSuccessful'] ?? false;
            if(is_array($array)){
                $result = $array['Message'];
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }

    } else {
        $result = false;
    }
    return $result;
}
public static function send_fast($mobile,$template_id,$parameters){

    $vars = [];
    foreach ($parameters as $key=>$parameter){
        $vars[]=array(
            "Parameter" =>$key,
            "ParameterValue" => $parameter
        );
    }
    $postData = [
        "ParameterArray" => $vars,
        "Mobile" => $mobile,
        "TemplateId" => $template_id
    ];

    $result = self::exec($postData,"http://RestfulSms.com/api/UltraFastSend");

    if(is_object($result)){
        $array = get_object_vars($result);
//            dd($array);
        if(is_array($array)){
            $dataa = isset($array['Data']) ? ' - '.$array['Data'] : '';
            $result = $array['Message'].$dataa;
        } else {
            $result = false;
        }
    } else {
        $result = false;
    }


    return $result;
}

private static function exec($postData,$url,$by_token=true){
    $token = '';
    if($by_token) {
        $token = self::get_token();
        if (!$token)
            return false;
    }
    $postString = json_encode($postData);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'x-sms-ir-secure-token: '.$token
    ));
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POST, count($postData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

    $res = curl_exec($ch);
    curl_close($ch);
    return  json_decode($res);
}

}
