<?php

use App\Model\EduStudent_Teacher;
use App\Model\EduCourseAssignClass_Provider;
use Carbon\Carbon;

class Helper {

	static $googleKey = 'AIzaSyC2Um1RKYtS32dKJn00CmmmhWQPfW6nAGU';

    public static function getYoutubeVideoTitle($video_id)
    {
        $json = self::file_get_content_curl('https://www.googleapis.com/youtube/v3/videos?id='.$video_id.'&key=AIzaSyC2Um1RKYtS32dKJn00CmmmhWQPfW6nAGU&part=snippet');
        $ytdata = json_decode($json);
        if(!empty($ytdata->items)) {
            return $ytdata->items[0]->snippet->title;
        } else {
            return "";
        }
    }
    public static function getYoutubeVideoDuration($video_id) {
        $json = self::file_get_content_curl('https://www.googleapis.com/youtube/v3/videos?id='.$video_id.'&key=AIzaSyC2Um1RKYtS32dKJn00CmmmhWQPfW6nAGU&part=contentDetails');
        $ytdata = json_decode($json);
        if(empty($ytdata->items)) {
            return 0;
        } else {
            $duration = $ytdata->items[0]->contentDetails->duration;
            $duration = new DateInterval($duration);
            $duration = ($duration->h*3600+$duration->i*60+$duration->s);
            return $duration;
        }
    }
    public static function file_get_content_curl ($url) 
    {
        // Throw Error if the curl function does'nt exist.
        if (!function_exists('curl_init')){ 
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    public static function getUploadedFileName($mainFile, $imgPath, $reqWidth=0, $reqHeight=0)
    {
        $fileExtention = $mainFile->extension();
        $fileOriginalName = $mainFile->getClientOriginalName();
        $file_size 	= $mainFile->getSize();

        $validExtentions = array('jpeg', 'jpg', 'png', 'gif');
        $path = public_path($imgPath);
        $currentTime = time();
        $fileName = $currentTime.'.'.$fileExtention;
        
        if (in_array($fileExtention, $validExtentions)) {
            $imgDimention = true; 
            if ($reqWidth > 0 || $reqHeight > 0) {
                $imgSizeArr = getimagesize($mainFile);
                $imgWidth = $imgSizeArr[0];
                $imgHeight = $imgSizeArr[1];
                if ($reqWidth > 0 && $reqHeight > 0 && ($imgWidth != $reqWidth || $imgHeight != $reqHeight)) {
                    $imgDimention = false;
                    $dimentionErrMsg = "Image size must be ".$reqWidth."px * ".$reqHeight."px";
                } elseif ($reqWidth > 0 && $imgWidth != $reqWidth) {
                    $imgDimention = false;
                    $dimentionErrMsg = "Image width must be ".$reqWidth."px";
                } elseif ($reqHeight > 0 && $imgHeight != $reqHeight) {
                    $imgDimention = false;
                    $dimentionErrMsg = "Image height must be ".$reqHeight."px";
                }
            } 

            if ($imgDimention) {
                $mainFile->move($path, $fileName);
                //create instance
                $img = Image::make($path.'/'.$fileName);
                //resize image
                $img->resize(80, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($path.'/thumb/'.$fileName);
                
                $output['status'] = 1;
                $output['file_name'] = $fileName;
                $output['file_original_name'] = $fileOriginalName;
                $output['file_extention']     = $fileExtention;
                $output['file_size']          =  $file_size;
            } else {
                $output['errors'] = $dimentionErrMsg;
                $output['status'] = 0;
            }

        } else {
            $output['errors'] = $fileExtention.' File is not support';
            $output['status'] = 0;
        }
        return $output;

    }

    public static function getUploadedAttachmentName($mainFile, $validPath)
    {
        $fileExtention = $mainFile->extension();
        $fileOriginalName = $mainFile->getClientOriginalName();
        $file_size 	= $mainFile->getSize();
        $validExtentions = array('zip','pdf', 'doc', 'docx', 'jpeg', 'jpg', 'png');
        $path = public_path($validPath);
        $currentTime = time();
        $fileName = $currentTime.'.'.$fileExtention;

        if($file_size<=5242880) {
            if (in_array($fileExtention, $validExtentions)) {
                $mainFile->move($path, $fileName);
        
                $output['status']             = 1;
                $output['file_name']          = $fileName;
                $output['file_original_name'] = $fileOriginalName;
                $output['file_extention']     = $fileExtention;
                $output['file_size']          =  $file_size;
    
            } else {
                $output['errors'] = $fileExtention.' File is not support';
                $output['status'] = 0;
            }
        } else {
            $output['errors'] = $file_size.'size is too large !!!';
            $output['status'] = 0;
        }
        
        return $output;

    }
    // FIXED SIZE IMAGE CROP FUNCTION
    public static function getFixedUpFileName($mainFile, $imgPath, $isFixed=false, $reqWidth=0, $reqHeight=0)
    {
        $fileExtention = $mainFile->extension();
        $fileOriginalName = $mainFile->getClientOriginalName();
        $file_size 	= $mainFile->getSize();
        $imgSizeArr = getimagesize($mainFile);

        $validExtentions = array('jpeg', 'jpg', 'png');
        $path = public_path($imgPath);
        $currentTime = time();
        $fileName = $currentTime.'.'.$fileExtention;
        
        if (in_array($fileExtention, $validExtentions)) {
            if ($isFixed) { //Size Fixed
                $imgDimention = false; 
                $imgWidth = $imgSizeArr[0];
                $imgHeight = $imgSizeArr[1];
                if ($reqWidth <= $imgWidth && $reqHeight <= $imgHeight) {
                    $imgDimention = true;
                } else {
                    $imgDimention = false;
                    $dimentionErrMsg = "Image size must be greater than or equal ".$reqWidth."px * ".$reqHeight."px";
                }

                if ($imgDimention) {
                    $mainFile->move($path, $fileName);
                    //create instance
                    //Image Fixed resizing for Use
                    $img = Image::make($path.'/'.$fileName)->resize($reqWidth, $reqHeight)->save($path.'/usedImg/'.$fileName);
                    // //resize image for thumb
                    $img->resize(80, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save($path.'/thumb/'.$fileName);
                    
                    $output['status']             = 1;
                    $output['file_name']          = $fileName;
                    $output['file_original_name'] = $fileOriginalName;
                    $output['file_extention']     = $fileExtention;
                    $output['file_size']          = $file_size;
                } else {
                    $output['errors'] = $dimentionErrMsg;
                    $output['status'] = 0;
                }

            } else { //Size Not Fixed
                $mainFile->move($path, $fileName);
                //create instance
                $img = Image::make($path.'/'.$fileName);
                //resize image
                $img->resize(80, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($path.'/thumb/'.$fileName);
                
                $output['status']             = 1;
                $output['file_name']          = $fileName;
                $output['file_original_name'] = $fileOriginalName;
                $output['file_extention']     = $fileExtention;
                $output['file_size']          =  $file_size;
            }

        } else {
            $output['errors'] = $fileExtention.' File is not support';
            $output['status'] = 0;
        }
        return $output;

    }

    public static function dateYMD($date){
        $date = date_create($date);
        return $date = date_format($date,"Y-m-d");
    }

    public static function timeHi24($time){    
        $time = date("H:i", strtotime($time));
        return $time;
    }
    public static function timeHis($time){    
        $time = DateTime::createFromFormat('g:i A', $time);
        $time = $time->format('H:i:s');
    }

    public static function timeGia($time){  // calculate am,pm  
        $time = DateTime::createFromFormat('H:i:s', $time);
        return $time = $time->format('g:i A');
    }

    public static function secondsToTime($seconds) {
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60);

        return $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    }
    public static function timeToSecond($time) {
        $full_array = explode(":", $time);
        $counter =  count($full_array);
        if($counter == 2){
            $seconds = $full_array[0]*60 + $full_array[1];
            return $seconds;
        }else if($counter == 3){
            $seconds = $full_array[0]*3600 + $full_array[1]*60 + $full_array[2];
            return $seconds;
        }else{
            return $time;
        }
        // $diff = $diff[0]*3600 + $diff[1]*60 + $diff[0];
    }

    public static function generateAutoID($table_name,$generate_field){
        $check = DB::table($table_name)->get();
        if(count($check) > 0){
            $sl_no = DB::table($table_name)->where('valid', 1)->max($generate_field)+1;
        }else{
            $sl_no = 1;
        }
        return $sl_no;
    }

    public static function dayName($day_dt){
        switch ($day_dt) {
            case "0":
                return "Sunday";
                break;
            case "1":
                return "Monday";
                break;
            case "2":
                return "Tuesday";
                break;            
            case "3":
                return "Wednesday";
                break;            
            case "4":
                return "Thursday";
                break;            
            case "5":
                return "Friday";
                break;
            case "6":
                return "Saturday";
                break;

            default:
                return "";
        }
    }

    //For showing file size
	public static function fileSizeConvert($bytes) {
		$bytes = floatval($bytes);
		$arBytes = array(
			0 => array(
				"UNIT" => "TB",
				"VALUE" => pow(1024, 4)
			),
			1 => array(
				"UNIT" => "GB",
				"VALUE" => pow(1024, 3)
			),
			2 => array(
				"UNIT" => "MB",
				"VALUE" => pow(1024, 2)
			),
			3 => array(
				"UNIT" => "KB",
				"VALUE" => 1024
			),
			4 => array(
				"UNIT" => "B",
				"VALUE" => 1
			),
		);
		if($bytes > 0) {
			foreach($arBytes as $arItem) {
				if($bytes >= $arItem["VALUE"]) {
					$result = $bytes / $arItem["VALUE"];
					$result = strval(round($result, 2))." ".$arItem["UNIT"];
					break;
				}
			}
			return $result;
		} else {
			return 0;
		}

    }
    
    //For showing file Thumb
	public static function getFileThumb($file_ext) {

            // if($file_ext=='jpg' || $file_ext=='jpeg' || $file_ext=='png' || $file_ext=='gif' || $file_ext=='mp4' || $file_ext=='mp3') {
            if($file_ext=='doc' || $file_ext=='docx') {
                $thumb ='file_icon/doc.png';
            } else if($file_ext=='ppt' || $file_ext=='pptx') {
                $thumb ='file_icon/ppt.png';
            } else if($file_ext=='xls' || $file_ext=='xlsx') {
                $thumb ='file_icon/xls.png';
            } else if($file_ext=='zip' || $file_ext=='rar' || $file_ext=='tar') {
                $thumb ='file_icon/zip.png';
            } else if($file_ext=='pdf') {
                $thumb ='file_icon/pdf.png';
            } else if($file_ext=='csv') {
                $thumb ='file_icon/csv.png';
            } else if($file_ext=='txt') {
                $thumb ='file_icon/txt.png';
            } else if($file_ext=='mp4') {
                $thumb ='file_icon/mp4.jpg';
            } else if($file_ext=='mp3') {
                $thumb ='file_icon/mp3.jpg';
            } else if($file_ext=='jpg' || $file_ext=='jpeg' || $file_ext=='png') {
                $thumb ='file_icon/image.jpg';
            } else {
                $thumb = 'file_icon/zip.png'; //default
            }
            return $thumb;
    }
    
    public static function studentInfo($id){
        $student_info = DB::table('users')->where('id',$id)->where('valid',1)->first();
        return $student_info;
    }
    public static function studentName($id){
       return DB::table('users')->find($id)->name;
    }
    public static function studentProfileImg($id){
       return DB::table('users')->find($id)->image;
    }
    public static function batchName($id){
        return DB::table('edu_assign_batches')->find($id)->batch_no;
    }
    public static function courseName($id){
        return DB::table('edu_courses')->find($id)->course_name;
    }
    public static function className($id){
        return DB::table('edu_course_assign_classes')->where('valid',1)->find($id)->class_name;
    }
    public static function getClassName($id){
        $class_id = DB::table('edu_assign_batch_classes')->where('valid', 1)->find($id)->class_id;
        return DB::table('edu_course_assign_classes')->where('valid',1)->find($class_id)->class_name;
    }
    // FOR SUCCESS STORY REACTION STATUS
    public static function getReactStatus($success_story_id, $auth_id){
        return DB::table('edu_std_success_story_reactions')->where('success_story_id', $success_story_id)->where('created_by', $auth_id)->first()->react_status;
    }
    
    public static function supportCategoryName($id){
        return DB::table('edu_support_categories')->where('valid',1)->find($id)->category_name;
    }
    
    public static function supportManagerInfo($id){ 
        return DB::table('edu_supports')->where('valid',1)->find($id);
    }

    public static function getTeacherName($id){
        return DB::table('edu_teachers')->where('valid',1)->find($id)->name;
    }

    public static function getPaymentSystemName($id){
        return DB::table('edu_payment_systems')->find($id)->payment_type;
    }
    
    // teacher name for notification
    public static function getAuthorName($type,$id){
        if($type == 1){
            return DB::table('edu_provider_users')->where('valid',1)->find($id)->name;
        }elseif($type == 2){
            return DB::table('edu_teachers')->where('valid',1)->find($id)->name;
        }elseif($type == 3){
            return DB::table('users')->where('valid',1)->find($id)->name;
        }elseif($type == 4){
            return DB::table('edu_supports')->where('valid',1)->find($id)->name;
        }else {
            return 'No Type';
        }
        
    }
    // SMS RECEIVER NAME 
    public static function getUserName($type,$id){
        if($type == 1){
            return DB::table('users')->where('id',$id)->where('valid',1)->first()->name;
        }elseif($type == 2){
            return DB::table('edu_teachers')->where('valid',1)->find($id)->name;
        }elseif($type == 3){
            return DB::table('edu_supports')->where('valid',1)->find($id)->name;
        }
        else{
            return 'No Type';
        }
    }
    //Zoom Methods

    //FOR ZOOM INTEGRATION (CREATE / UPDATE)
    public static function zoomIntegrationFunction($curl_url, $curl_method, $postFields, $token) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $curl_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $curl_method,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => array(
            "authorization: Bearer".$token,
            "content-type: application/json"
            ),
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $output['messege'] = $err;
            $output['msgType'] = 'error';
            $output['msgStatus'] = 0;
        } else {
            $output['info'] = json_decode($response);
            $output['msgStatus'] = 1;
        }

        return $output;
    }
    //END FOR ZOOM INTEGRATION (CREATE / UPDATE)

    //ZOOM DATA GET/DELETE
    public static function zoomGetDelete($token, $curl_method, $meeting_id) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zoom.us/v2/meetings/". $meeting_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $curl_method,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer".$token,
                "content-type: application/json"
            ),
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $output['messege'] = $err;
            $output['msgType'] = 'error';
            $output['msgStatus'] = 0;
        } else {
            $output['info'] = json_decode($response);
            $output['msgStatus'] = 11;
            $output['messege'] = '';
        }

        return $output;
    }

    public static function zoomTimeGia($time){  // calculate am,pm  
        $full_array = explode(":", $time);
        $counter =  count($full_array);
        if($counter == 2){
            $time = DateTime::createFromFormat('H:i', $time);
            $time_value =  $time->format('g');
            if(strlen($time_value) == 1){
                return $time = $time->format('0g:i A');
            }else{
                return $time = $time->format('g:i A');
            }
        }else if($counter == 3){
            $time = DateTime::createFromFormat('H:i:s', $time);
            $time_value =  $time->format('g');
            if(strlen($time_value) == 1){
                return $time = $time->format('0g:i A');
            }else{
                return $time = $time->format('g:i A');
            }

        }else{
            return $time = $time->format('g:i A');
        }

        $time = DateTime::createFromFormat('H:i:s', $time);
        return $time = $time->format('%hh %im');
    }

    public static function time($get_hour,$real_min,$get_time_format) {

        $real_hour = '';
        switch(TRUE)
        {
           case ($get_hour=='12'&&$get_time_format=='AM'):
           $real_hour = "18";
           break;

           case ($get_hour=='01'&&$get_time_format=='AM'):
           $real_hour = "19";
           break;

           case ($get_hour=='02'&&$get_time_format=='AM'):
           $real_hour = "20";
           break;

           case ($get_hour=='03'&&$get_time_format=='AM'):
           $real_hour = "21";
           break;

           case ($get_hour=='04'&&$get_time_format=='AM'):
           $real_hour = "22";
           break;

           case ($get_hour=='05'&&$get_time_format=='AM'):
           $real_hour = "23";
           break;

           case ($get_hour=='06'&&$get_time_format=='AM'):
           $real_hour = "00";
           break;

           case ($get_hour=='07'&&$get_time_format=='AM'):
           $real_hour = "01";
           break;

           case ($get_hour=='08'&&$get_time_format=='AM'):
           $real_hour = "02";
           break;

           case ($get_hour=='09'&&$get_time_format=='AM'):
           $real_hour = "03";
           break;

           case ($get_hour=='10'&&$get_time_format=='AM'):
           $real_hour = "04";
           break;

           case ($get_hour=='11'&&$get_time_format=='AM'):
           $real_hour = "05";
           break;

           case ($get_hour=='12'&&$get_time_format=='PM'):
           $real_hour = "06";
           break;

           case ($get_hour=='01'&&$get_time_format=='PM'):
           $real_hour = "07";
           break;

           case ($get_hour=='02'&&$get_time_format=='PM'):
           $real_hour = "08";
           break;

           case ($get_hour=='03'&&$get_time_format=='PM'):
           $real_hour = "09";
           break;

           case ($get_hour=='04'&&$get_time_format=='PM'):
           $real_hour = "10";
           break;

           case ($get_hour=='05'&&$get_time_format=='PM'):
           $real_hour = "11";
           break;

           case ($get_hour=='06'&&$get_time_format=='PM'):
           $real_hour = "12";
           break;

           case ($get_hour=='07'&&$get_time_format=='PM'):
           $real_hour = "13";
           break;

           case ($get_hour=='08'&&$get_time_format=='PM'):
           $real_hour = "14";
           break;

           case ($get_hour=='09'&&$get_time_format=='PM'):
           $real_hour = "15";
           break;

           case ($get_hour=='10'&&$get_time_format=='PM'):
           $real_hour = "16";
           break;

           case ($get_hour=='11'&&$get_time_format=='PM'):
           $real_hour = "17";
           break;

           default:
           $real_hour = "00";
           break;

        }

        return $time = $real_hour.':'.$real_min.':00';
    }
    //Zoom Methods

    public static function getRemainingDays($last_date)
    {
        if (isset($last_date)) {
            $LastDaywithTenDays = date('Y-m-d', strtotime($last_date. ' + 10 days'));
            $remaining_days = Carbon::now()->diffInDays(Carbon::parse($LastDaywithTenDays));
        } else {
            $remaining_days = 0;
        }
        return $remaining_days;
    }

    public static function getRemainingDayTime($last_date)
    {
        // DAY HOUR MINUTE
        if (isset($last_date)) {
            $today = date('Y-m-d H:i:s');
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $last_date);
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $today);

            $days = $startDate->diffInDays($endDate);
            $hours = $startDate->copy()->addDays($days)->diffInHours($endDate);
            $minutes = $startDate->copy()->addDays($days)->addHours($hours)->diffInMinutes($endDate);
            $remaining_days = $days.' Days '.$hours.' Hours '.$minutes.' Minutes'; 
        } else {
            $remaining_days = '0 Days 0 Hours 0 Minutes';
        }
        return $remaining_days;
    }

    //sms
    public static function dynamicSms($messageData)
    {
        $params = [
            "api_token" => "d737ded1-fdc3-4481-9778-963656713ca4",
            "sid"       => "LFWFANON",
            "sms" => $messageData,
        ];
        $params = json_encode($params);
        $url = "https://smsplus.sslwireless.com/api/v3/send-sms/dynamic";

        return self::callApi($url, $params);
    }

    public static function singleSms($msisdn, $messageBody, $csmsId)
    {
        $params = [
            "api_token" => "d737ded1-fdc3-4481-9778-963656713ca4",
            "sid"       => "LFWFANON",
            "msisdn"    => $msisdn,
            "sms"       => $messageBody,
            "csms_id"   => $csmsId
        ];
        $url = 'https://smsplus.sslwireless.com/api/v3/send-sms';
        $params = json_encode($params);

        return self::callApi($url, $params);
    }

    public static function callApi($url, $params)
    {
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($params),
            'accept:application/json'
        ));
    
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    
}