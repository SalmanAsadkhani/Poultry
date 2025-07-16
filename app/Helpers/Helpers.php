<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class Helpers{

    public static function captcha($sessionName, $count = 2, $dificalty = 1, $bg = [120, 100, 205]) {
        header("Content-Type: image/png");

        $count = $count < 2 ? 2 : $count;
        $chars = [];

        for ($i = 0; $i < $count; $i++) {
            if ($dificalty == 1) {
                do { $ascii = rand(48, 90); } while ($ascii > 57);
            } elseif ($dificalty == 2) {
                do { $ascii = rand(48, 90); } while ($ascii < 65);
            } else {
                do { $ascii = rand(48, 90); } while ($ascii > 57 && $ascii < 65);
            }
            $chars[$i] = chr($ascii);
        }

        $code = implode('', $chars);
        session([$sessionName => strtolower($code)]);

        $width = $count * 24;
        $height = 40;
        $image = imagecreate($width, $height);


        imagecolorallocate($image, $bg[0], $bg[1], $bg[2]);

        $font = public_path('assets/fonts/ariblk.ttf');

        for ($i = 0; $i < $count; $i++) {
            $angle = rand(-20, 20);
            $size = rand(17, 22);
            $space = rand(18, 22);
            $heightOffset = rand(27, 33);
            $color = imagecolorallocate($image, rand(230, 255), rand(230, 255), rand(255, 255)); // رنگ روشن برای تضاد

            imagefttext($image, $size, $angle, $i * $space + 5, $heightOffset, $color, $font, $chars[$i]);


            imageline($image, rand(1, $width), rand(5, $height), rand(5, $width), rand(0, $height), $color);
        }

        imagepng($image);
        imagedestroy($image);
    }


    public static function excelToArray($Filepath){

        include_once __DIR__."/excel/export.php";
        return excelToArray($Filepath);
    }
    public static function checkFileAjax($request,$fieldName,$path,$size=10){
        if(!$request->hasFile($fieldName))
            return ['res'=>1,'mySuccess'=>'','myAlert'=>' یک فایل انتخاب نمایید'];
        $file = Helpers::checkAndSaveFile($request->$fieldName ,$path,'all',$size*1024);
        if(!is_array($file))
            return ['res'=>1,'mySuccess'=>'','myAlert'=>'لطفا یک فایل انتخاب نمایید.'];

        if($file[0] == 'isLongFile')
            return ['res'=>1,'mySuccess'=>'','myAlert'=>'فایل انتخابی حجیم است. حداکثر حجم مجاز : '.$size.' مگابایت'];

        if($file[0] == 'mimeNotValid')
            return ['res'=>1,'mySuccess'=>'','myAlert'=>'فایل انتخابی فرمت مناسب را ندارد '];
        $thisFile = ROOT.$path.$file[0];
        if(!is_file($thisFile))
            return ['res'=>1,'mySuccess'=>'','myAlert'=>'عملیات با خطا مواجه گردید.'];
        return ['res'=>10,'file'=>$thisFile];
    }




    public static function checkAndSaveImage($request,$fieldName,$path,$sizes=[[64,64],[200,200]],$maxSize=500,$oldImageName=null){
        if($request->hasFile($fieldName)){

            $fields[$fieldName]='image|mimetypes:image/jpeg,image/png|mimes:jpeg,png,jpg,JPEG,PNG,JPG';
            $validation = Validator::make($request->all(), $fields);
            if (!$validation->fails()) {
                $fields[$fieldName]='max:'.$maxSize;
                $validation = Validator::make($request->all(), $fields);
                if (!$validation->fails()) {
                    $file = $request->file($fieldName);
                    $name = 'upl_' . time() . rand(1, 100) . '.' . strtolower($file->getClientOriginalExtension());

                    $path = trim($path, '/');

                    if (!is_dir($path))
                        mkdir($path);
                    $path .= '/';

                    if (!is_null($oldImageName) && is_file($path . $oldImageName)) {
                        unlink($path . $oldImageName);
                        foreach ($sizes as $k => $size) {
                            if (is_file($path . $size[0] . '_' . $oldImageName))
                                unlink($path . $size[0] . '_' . $oldImageName);
                        }
                    }

                    $request->{$fieldName}->move(ROOT.$path, $name);
                    if(is_array($sizes))
                    foreach ($sizes as $k => $size) {
                        if(isset($size[0]) && isset($size[1])) {
                            $n = $k + 1;
                            Image::make($path . $name)->resize($size[0], $size[1])->save($path . $size[0] . '_'.$size[1] . '_' . $name);
                        }
                    }
                    return $name;
                }else
                    return 'isLong';
            }else
                return 'notImage';
        }else
            return 'notFileFound';
    }
    public static function checkAndSaveImagePdf($request,$fieldName,$path,$maxSize=500,$oldImageName=null){
        if($request->hasFile($fieldName)){

            $fields[$fieldName]='mimetypes:image/jpeg,image/png,application/pdf';
            $validation = Validator::make($request->all(), $fields);
            if (!$validation->fails()) {
                $fields[$fieldName]='max:'.$maxSize;
                $validation = Validator::make($request->all(), $fields);
                if (!$validation->fails()) {
                    $file = $request->file($fieldName);
                    $name = 'upl_' . time() . rand(1, 100) . '.' . strtolower($file->getClientOriginalExtension());

                    $path = trim($path, '/');

                    if (!is_dir($path))
                        mkdir($path);
                    $path .= '/';



                    $request->{$fieldName}->move(ROOT.$path, $name);

                    return $name;
                }else
                    return 'isLong';
            }else
                return 'notImage';
        }else
            return 'notFileFound';
    }

    public static function  CheckMelliCode($input) {
        if (!preg_match("/^\d{10}$/", $input)
            || $input=='0000000000'
            || $input=='1111111111'
            || $input=='2222222222'
            || $input=='3333333333'
            || $input=='4444444444'
            || $input=='5555555555'
            || $input=='6666666666'
            || $input=='7777777777'
            || $input=='8888888888'
            || $input=='9999999999') {
            return false;
        }
        $check = (int) $input[9];
        $sum = array_sum(array_map(function ($x) use ($input) {
                return ((int) $input[$x]) * (10 - $x);
            }, range(0, 8))) % 11;
        return ($sum < 2 && $check == $sum) || ($sum >= 2 && $check + $sum == 11);
    }

    public static function secure($data,$hour=''){
        $data=(array) $data;
        $rr = '';
        if(\Auth::check())
            $rr .= \Auth::user()->id;
        foreach ($data as $dd)
            $rr .= $dd;

        return substr(md5(env('salt').$rr.$hour),5,20);
    }



    public static function checkAndSaveFile($file,$path='pathType',$mimeTypes=['image','pdf'],$maxSize=500,$thumbnailSizes=[64,64]){

        if(!method_exists($file,'getClientSize'))
            return 'notFileValid';
        $mimeTypesValid = [
            'image'=>['image/jpeg','image/png','image/gif','image/xpng','image/bmp','image/jpg'],
            'pdf'=>['application/pdf'],
            'mpeg'=>['video/mpeg'],
            'mp3'=>['audio/mpeg'],
            'mp4'=>['video/mp4'],
            'excel'=>['application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/msexcel','application/x-msexcel','application/x-ms-excel','application/x-excel','application/x-dos_ms_excel','application/xls','application/x-xls','application/vnd.ms-excel'],
            'ppt'=>['application/vnd.ms-powerpoint','application/vnd.openxmlformats-officedocument.presentationml.presentation','application/vnd.openxmlformats-officedocument.presentationml.template','application/vnd.openxmlformats-officedocument.presentationml.slideshow','application/vnd.ms-powerpoint.addin.macroEnabled.12','application/vnd.ms-powerpoint.presentation.macroEnabled.12','application/vnd.ms-powerpoint.template.macroEnabled.12','application/vnd.ms-powerpoint.slideshow.macroEnabled.12'],
            'doc'=>['application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.openxmlformats-officedocument.wordprocessingml.template','application/vnd.ms-word.document.macroEnabled.12'],
            'csv'=>['text/csv','application/csv','application/x-csv','text/comma-separated-values','text/x-comma-separated-values','text/tab-separated-values'],
            'json'=>['application/json','application/x-javascript','text/javascript','text/x-javascript','text/x-json'],
            'rar'=>['application/x-rar-compressed','application/octet-stream'],
            'txt'=>['text/plain'],
            'xml'=>['text/xml','application/xml'],
            'zip'=>['application/zip','application/x-zip-compressed','application/octet-stream','multipart/x-zip'],
        ];

        $mimeTypes = $mimeTypes=='all' ? ['image','pdf','mpeg','mp3','mp4','excel','ppt','doc','csv','json','rar','txt','xml','zip'] : $mimeTypes;
        $mimeTypes = (array) $mimeTypes;

        $types = [];
        foreach ($mimeTypes as $mimeType){
            $mimeType = strtolower($mimeType);
            if(isset($mimeTypesValid[$mimeType]))
                $types[$mimeType] = $mimeTypesValid[$mimeType];
        }

        if($types == []) {
            echo  'این میم تایپ(ها) تعریف نشده است.';
            dd($mimeTypes);
        }


            if($file->getClientSize()/1024 > $maxSize)
                return ['isLongFile',$file->getClientOriginalName()];

//            dd($file->getClientMimeType());
            $fileType = '';
            foreach ($types as $kkk=>$ttt){
                if(in_array($file->getClientMimeType(),$ttt))
                    $fileType = $kkk;
            }
            if($fileType == '')
                return ['mimeNotValid',$file->getClientOriginalName()];

            $name = 'upl_' . time() . Helpers::randString(2,10) . '.' . strtolower($file->getClientOriginalExtension());



            $path = $path=='pathType' || $path=='' ? 'files/'.$fileType : $path;
            $path = trim($path,'/');
            if(!is_dir($path))
                mkdir($path);
            $path.='/';

            $file->move($path, $name);

            if ($fileType == 'image') {
                Image::make($path . $name)->resize($thumbnailSizes[0], $thumbnailSizes[1])->save($path . $thumbnailSizes[0].'_' . $name);
            }
            return [$name,$fileType,$path];

//        }else
//            return 'notFileFound';
    }

    public static function showErrors($removeError = true,$cols='col-md-6 col-lg-5'){
        if($errors = session('errors', false)){
            echo  '<ul class= "errorsAction '.$cols.'">';
            foreach($errors as $error){
                echo "<li>$error</li>";
            }
            echo  "</ul>";
        }
        if($removeError)
            session(['errors'=> []]);
    }

    public static function showWarnings($removeWarnings = true){
        if($warnings = session('warnings', false)){
            echo  '<ul class= "warningsAction col-md-6 col-lg-5">';
            foreach($warnings as $warning){
                echo "<li>$warning</li>";
            }
            echo  "</ul>";
        }
        if($removeWarnings)
            session(['errors'=> []]);
    }

    public static function pagination($page,$total_pages,$limit,$targetpage='#',$adjacents=2){
        if ($page == 0) $page = 1;
        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total_pages/$limit) ;
        $lpm1 = $lastpage - 1;

        $pagination = '';

        if($lastpage > 1) {
            $pagination .= '<ul  class="pagination">';
            //کلید قبلی
            if ($page > 1)
                $pagination .= "<li><a href=\"$targetpage/$prev\">&laquo;</a></li>";
            else
                $pagination .= '<li><a class="disabled" href="#">&laquo;</a></li>';

            //صفحات
            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li class='active'><a href=\"#\">$counter</a></li>";
                    else
                        $pagination .= "<li><a href=\"$targetpage/$counter\">$counter</a></li>";
                }
            }
            elseif ($lastpage > 5 + ($adjacents * 2)) {

                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class='active'><a href=\"#\">$counter</a></li>";
                        else
                            $pagination .= "<li><a href=\"$targetpage/$counter\">$counter</a></li>";
                    }
                    $pagination .= '<li class="spacePagination"> <a class="disabled" href="#"> ..... </a> </li>';
                    $pagination .= "<li><a href=\"$targetpage/$lpm1\">$lpm1</a></li>";
                    $pagination .= "<li><a href=\"$targetpage/$lastpage\">$lastpage</a></li>";
                } elseif ($lastpage - ($adjacents * 2) > $page &&   $page > ($adjacents * 2))
                {
                    $pagination .= "<li><a href=\"$targetpage/1\">1</a></li>";
                    $pagination .= "<li><a href=\"$targetpage/2\">2</a></li>";
                    $pagination .= '<li class="spacePagination"> <a class="disabled" href="#"> ..... </a> </li>';
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class='active'><a href=\"#\">$counter</a></li>";
                        else
                            $pagination .= "<li><a href=\"$targetpage/$counter\">$counter</a></li>";

                    }
                    $pagination .= '<li class="spacePagination"> <a class="disabled" href="#"> ..... </a> </li>';
                    $pagination .= "<li><a href=\"$targetpage/$lpm1\">$lpm1</a></li>";
                    $pagination .= "<li><a href=\"$targetpage/$lastpage\">$lastpage</a></li>";
                }

                else
                {
                    $pagination .= "<li><a href=\"$targetpage/1\">1</a></li>";
                    $pagination .= "<li><a href=\"$targetpage/2\">2</a></li>";
                    $pagination .= '<li> ... </li>';
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class='active'><a href=\"#\">$counter</a></li>";
                        else
                            $pagination .= "<li><a href=\"$targetpage/$counter\">$counter</a></li>";
                    }
                }
            }

            //کلید بعدی
            if ($page < $counter - 1)
                $pagination .= "<li><a href=\"$targetpage/$next\">&raquo;</a></li>";
            else
                $pagination .= "<li><a class='disabled' href=\"#\">&raquo;</a></li>";
            $pagination .= '</ul>';

            return $pagination;
        }
    }

    public static function randString($min,$max){
        $len=rand($min,$max);
        $rand='';
        for($i=0;$i<$len;$i++){
            do{
                $ascii = rand(48 , 90);
            }while($ascii > 57 and $ascii < 65);
            $rand.=chr($ascii);
        }
        return $rand;
    }

    public static function translate($text,$source='fa', $target='en') {

        $response 		= self::requestTranslation($source, $target, $text);
        if (is_string($response) && strpos($response,'Your client issued a request that was too large'))
            return "Your client issued a request that was too large";
        $translation 	= self::getSentencesFromJSON($response);
        return $translation;
    }

    private static function requestTranslation($source, $target, $text) {
        $url = "https://translate.google.com/translate_a/single?client=at&dt=t&dt=ld&dt=qca&dt=rm&dt=bd&dj=1&hl=es-ES&ie=UTF-8&oe=UTF-8&inputm=2&otf=2&iid=1dd3b944-fa62-4b55-b330-74909a99969e";
        $fields = array(
            'sl' => urlencode($source),
            'tl' => urlencode($target),
            'q' => urlencode($text)
        );

        $fields_string = "";
        foreach($fields as $key=>$value) {
            $fields_string .= $key.'='.$value.'&';
        }

        rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AndroidTranslate/5.3.0.RC02.130475354-53000263 5.1 phone TRANSLATE_OPM5_TEST_1');

        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
    }

    private static function getSentencesFromJSON($json) {
        $sentencesArray = json_decode($json, true);
        $sentences = "";
        if(is_array($sentencesArray) && count($sentencesArray))
        foreach ($sentencesArray["sentences"] as $s) {
            $sentences .= $s["trans"];
        }
        return $sentences;
    }

    public static function shortLink($url, $wish = ''){

            $data = http_build_query(
                array(
                    'url' => $url,
                    'wish' => $wish,
                    'language' => 'fa',
                )
            );
            $http = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $data
                )
            );
            $context = stream_context_create($http);
            $result = file_get_contents('http://api.yon.ir/', FALSE, $context);
            $arrayMessage= json_decode($result, true);
            if ($arrayMessage['status'] == "true")
            {
                return 'http://yon.ir/'.$arrayMessage['output'];
            }
            else
            {
                return false;
            }

    }

    public static function checkBirthDay($birthDay){

        if(preg_match('#^\d{4}/\d{1,2}/\d{1,2}$#is',$birthDay)){
            $b = explode('/',$birthDay);
            if(jdate('Ymd','','','','en')<$b[0].$b[1].$b[2])
                return 'notOver';
            if(count($b) == 3){
                if($b[0] > 1255 && $b[0]<= jdate('Y','','','','en')){
                    if($b[1]>0 && $b[1]<13){
                        if($b[2]>0 && $b[2]<32){
                            if($b[1]<7)
                                return 'ok';
                            elseif ($b[2]<31)
                                return 'ok';
                        }
                        return 'dayInvalid';
                    }else
                        return 'mountInvalid';
                }else
                    return 'yearInvalid';
            }
        }
        return 'invalid';
    }

}
