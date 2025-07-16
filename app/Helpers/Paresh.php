<?php
namespace App\Helpers;


use App\User;

class Paresh{
    private $_token = null;
    private function get_token(){

$curl = curl_init();




        if(is_null($this->_token)){
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://2.180.30.220:8010/Token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array("keyValue"=>"PareshTest"),
            ));
// if (curl_errno($curl)) {
//     dd(curl_error($curl));
// }
            $response = json_decode(curl_exec($curl),1);

            curl_close($curl);



            if(!$response || $response['result']!='OK'){
               dd(2,$response,'خطای سرور حسابداری',1);
            }

            $this->_token = $response['result_value']['token'];
        }
        return $this->_token;
    }

    public function get_data($year, $mount, $nType=1){

        $token = $this->get_token();
            // dd($year, $mount, $nType, $token);
    //    dd($token);

         $data_res = array('NType' => $nType,'PYear' => $year,'PMonth' => $mount);
//  dd($data_res);

// $token = 'df';


        $curl = curl_init();

        curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'https://overtime.neyshabur.ir/paresh.php',

            CURLOPT_URL => 'http://2.180.30.220:8010/PayRoll/GetSPSelectPayWS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_res,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token
            ),
        ));

        $response = json_decode(curl_exec($curl),1);
        
// dd($response,$token);

        curl_close($curl);

        $ziro = [];
        $not_ziro = 0;
        if(is_array($response['Data']))
        foreach ($response['Data'] as $datum){
            if($user = User::where('melli_code',$datum['NPerNationalId'])->first()){
                $user->rate_holiday = $datum['NOdment55'];
                $user->save();
            }else
                $ziro[]=$datum;

        }
// dd($ziro,$not_ziro);
        return $response;

    }


}
