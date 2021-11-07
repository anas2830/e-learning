<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Auth;
use Validator;
use Helper;
use DB;
use Illuminate\Support\Str;

use App\Models\EduSupports;
use App\Models\EduStudentSupportRequest_Support;
use App\Models\EduSupportLiveClassSchedules_Support;
use App\Models\EduZoomAccount_Support;
use App\Models\EduAssingedSupportCategory_Support;

class StdRequestController extends Controller  
{

    public function index(Request $request){
        $data['authId'] = $authId = Auth::guard('support')->id();
        $auth_category_ids = EduAssingedSupportCategory_Support::where('support_id', $authId)->get()->pluck('support_category_id');

        $data['all_requests'] = $all_requests = EduStudentSupportRequest_Support::join('users', 'users.id', '=', 'edu_student_support_requests.created_by')
            ->select('edu_student_support_requests.*', 'users.name as student_name', 'users.phone', 'users.fb_profile')
            ->whereIn('edu_student_support_requests.category_id', $auth_category_ids)
            ->where('edu_student_support_requests.valid', 1)
            ->orderBy('edu_student_support_requests.id', 'desc')
            ->get();
        foreach ($all_requests as $key => $stdReq) {
            $stdReq->liveSchedule = EduSupportLiveClassSchedules_Support::valid()
                ->where('std_support_req_id', $stdReq->id)
                ->where('created_by', $authId)
                ->first();
        }
        
        return view('support.studentRequest.requestList', $data);
    }

    public function requestDetails(Request $request)
    {
        $data['requestDetails'] = EduStudentSupportRequest_Support::valid()->find($request->req_id);
        return view('support.studentRequest.requestDetails', $data);
    }

    public function stdRequestSchedule(Request $request){

        $authId = Auth::guard('support')->id();
        $auth_category_ids = EduAssingedSupportCategory_Support::where('support_id', $authId)->get()->pluck('support_category_id');

        $data['std_req_id'] = $std_req_id =  $request->std_req_id;
        $data['zoom_account_info'] = EduZoomAccount_Support::valid()->first();
        
        $check_std_req = EduStudentSupportRequest_Support::valid()->find($std_req_id);

        if(!empty($check_std_req)){

            if($check_std_req->supported_by != null){
                if($check_std_req->supported_by == $authId){
                    $data['liveClass_Schedule'] = EduSupportLiveClassSchedules_Support::valid()
                        ->where('std_support_req_id', $std_req_id)
                        ->whereIn('support_cat_id', $auth_category_ids)
                        ->where('created_by', $authId)
                        ->first();
                    
                    return view('support.studentRequest.reqSchedule', $data);
                }else{
                    $data['back_route'] = "support.home";
                    $data['messege'] = "Sorry !!";
                    return view('examError', $data);
                }
            }else{
                $data['liveClass_Schedule'] = '';
                return view('support.studentRequest.reqSchedule', $data);
            }
            
        }else{
            $data['back_route'] = "support.home";
            $data['messege'] = "Sorry !!";
            return view('examError', $data);
        }
       
    }

    public function stdRequestScheduleAction(Request $request)
    {
        $authId = Auth::guard('support')->id();
        $std_req_id = $request->std_req_id;
        $std_req_info = EduStudentSupportRequest_Support::valid()->find($std_req_id);
        $zoom_acc_id = $request->zoom_acc_id;
        $start_date =  Helper::dateYMD($request->start_date);

        // $start_time = $request->start_time;
        $start_time = Helper::zoomTimeGia($request->start_time);
        
        $duration_hours = $request->d_hour;
        $duration_minutes = $request->d_min;
        $duration_of_sec = (int)$request->d_hour*60*60+(int)$request->d_min*60; // Calculate by seconds
        $duration_of_min = (int)$request->d_hour*60+(int)$request->d_min; // Calculate by seconds
        $end_time = strftime('%X', strtotime($start_time) + $duration_of_sec);

        //TIME CALCULATION
        $get_hour = substr($start_time, 0, 2);
        $get_time_format = substr($start_time, -2);

        $get_min = substr($start_time, -5);
        $real_min = substr($get_min, 0, 2);

        $real_hour = Helper::time($get_hour,$real_min,$get_time_format);

        $day_dt  = date('w', strtotime($start_date));

        $zoom_account_info = EduZoomAccount_Support::valid()->find($zoom_acc_id); 
        if (!empty($zoom_account_info)) {
            $email = $zoom_account_info->email;
            $token = $zoom_account_info->token;

            //check exits same acount
            $checkScheduleInfo = EduSupportLiveClassSchedules_Support::valid()
                ->where('zoom_acc_id',$zoom_acc_id)
                ->where('std_support_req_id',$std_req_id)
                ->where('created_by',$authId)
                ->first();

            $check_already_exists_schedule =  EduSupportLiveClassSchedules_Support::valid()->where('std_support_req_id',$std_req_id)->first();

            $validator = Validator::make($request->all(), [
                'zoom_acc_id' => 'required',
                'start_date'  => 'required',
                'start_time'  => 'required',
                'd_hour'      => 'required|numeric',
                'd_min'       => 'required|numeric'
            ]);

            if ($validator->passes()) {
                DB::beginTransaction();
                //FOR ZOOM SCHEDULE CREATE/UPDATE INFO---
                $liveClassData = array(
                    'topic'                  => 'Support Request',
                    'agenda'                 => 'Description',  //description was too much long. which is not accepted in zoom
                    'start_time'             => $start_date."T".$real_hour."Z",
                    'duration'               => $duration_of_min,
                    'timezone'               => 'Asia/Dhaka',
                    'password'               => self::zoomPaaword(),
                    'settings'               => array(
                        'join_before_host'       => false, 
                        'mute_upon_entry'        => true, 
                        'waiting_room'           => true, 
                        'meeting_authentication' => true
                    ),
                    
                );

                if (!empty($checkScheduleInfo)) {
                    //ZOOM INFO
                    $curl_url = "https://api.zoom.us/v2/meetings/".$checkScheduleInfo->meeting_id;
                    $curl_method = "PATCH";
                    $message = "updated";
                } else {

                    if(!empty($check_already_exists_schedule)){
                        $output['messege'] = "Schedule Already Created !";
                        $output['msgType'] = 'danger';
                    }else{
                        //ZOOM INFO
                        $curl_url = "https://api.zoom.us/v2/users/".$email."/meetings";
                        $curl_method = "POST";
                        $message = "created";
                    }
                }

                $liveClassData['type'] = 2;
                // $zoomInfo["info"]->code = 0;
                $postFields = json_encode($liveClassData);

                $zoomInfo = Helper::zoomIntegrationFunction($curl_url, $curl_method, $postFields, $token);

                if (!empty($checkScheduleInfo)) {
                    $curl_method = "GET";
                    $zoomInfo = Helper::zoomGetDelete($token, $curl_method, $checkScheduleInfo->meeting_id);
                }

                if (property_exists($zoomInfo["info"], 'code')) {
                    $msgStatus = 0;
                } else {
                    $msgStatus = 1;
                }  

                if ($msgStatus==1) {
                    
                    $liveClassZoomData = [
                        'zoom_acc_id'        => $zoom_acc_id,
                        'std_support_req_id' => $std_req_id, 
                        'support_cat_id'     => $std_req_info->category_id, 
                        'student_id'         => $std_req_info->created_by, 
                        'day_dt'             => $day_dt, 
                        'start_date'         => $start_date, 
                        'start_time'         => strftime('%X', strtotime($request->start_time)),
                        'end_time'           => $end_time, 
                        'hour'               => $duration_hours, 
                        'min'                => $duration_minutes, 
                        'duration'           => $duration_of_min, 
                        'type'               => $zoomInfo['info']->type,
                        'meeting_id'         => $zoomInfo['info']->id,
                        'host_id'            => $zoomInfo['info']->host_id,
                        'start_url'          => $zoomInfo['info']->start_url,
                        'join_url'           => $zoomInfo['info']->join_url,
                        'timezone'           => $zoomInfo['info']->timezone
                    ];

                    if (!empty($checkScheduleInfo)) {
                        $checkScheduleInfo->update($liveClassZoomData); //PUDATE
                    } else {
                        EduSupportLiveClassSchedules_Support::create($liveClassZoomData); //CREATE
                        $std_req_info->update(['approve_status' => 1, 'supported_by' => $authId ]);
                    }
                    //END LIVE CLASS ZOOM INFO

                    $output['messege'] = 'Live class schedule has been '.$message;
                    $output['msgType'] = 'success';
                } else {
                    $output['messege'] = "Access token is expired!";
                    $output['msgType'] = 'danger';
                }

                DB::commit();
                return redirect()->back()->with($output);
            } else {
                return redirect()->back()->withErrors($validator);
            }
        } else {
            $output['messege'] = 'Add Your Zoom Account First!!!';
            $output['msgType'] = 'danger';
            return redirect()->back()->with($output);
        }
    }

    public static function zoomPaaword()
    {
        $password = Str::random(8);
        return $password;
    }

}
