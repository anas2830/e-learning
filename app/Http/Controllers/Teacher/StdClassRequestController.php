<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Auth;
use Validator;
use Helper;
use DB;
use Illuminate\Support\Str;

use App\Models\EduClassRequest_Teacher;
use App\Models\EduAssignBatch_Teacher;

class StdClassRequestController extends Controller  
{

    public function index(Request $request){
        $data['authId'] = $authId = Auth::guard('teacher')->id();
        $data['class_requests'] = EduClassRequest_Teacher::join('edu_assign_batches', 'edu_assign_batches.id', '=', 'edu_class_requests.batch_id')
            ->join('edu_assign_batch_classes', 'edu_assign_batch_classes.id', 'edu_class_requests.assign_batch_class_id')
            ->join('edu_course_assign_classes', 'edu_course_assign_classes.id', 'edu_assign_batch_classes.class_id')
            ->join('users', 'users.id', 'edu_class_requests.created_by')
            ->select('edu_class_requests.*', 'edu_assign_batches.batch_no', 'edu_course_assign_classes.class_name', 'users.email')
            ->where('edu_assign_batches.teacher_id', $authId)
            ->where('edu_class_requests.valid', 1)
            ->orderBy('edu_class_requests.id', 'desc')
            ->get();
        
        return view('teacher.studentClassRequest.requestList', $data);
    }

    public function requestFeedback(Request $request){

        $authId = Auth::guard('teacher')->id();
        $assigned_batch_ids = EduAssignBatch_Teacher::valid()->where('teacher_id', $authId)->get()->pluck('id')->toArray();
        $classRequestInfo = EduClassRequest_Teacher::valid()->find($request->class_request_id);

        if(in_array($classRequestInfo->batch_id, $assigned_batch_ids)){
            $data['class_request'] = $classRequestInfo;
            return view('teacher.studentClassRequest.reqFeebackForm', $data);
        } else{
            $data['back_route'] = "teacher.stdRequestClass";
            $data['messege'] = "Sorry !!";
            return view('examError', $data);
        }
       
    }

    public function requestFeebackAction(Request $request)
    {
        $authId = Auth::guard('teacher')->id();
        $validator = Validator::make($request->all(), [
            'class_link' => 'required'
        ]);

        if ($validator->passes()) {
            $classRequestInfo = EduClassRequest_Teacher::valid()->find($request->class_request_id);
            if (!empty($classRequestInfo)) {
                $classRequestInfo->update([
                    'approve_status' => 1,
                    'response'       => $request->response,
                    'class_link'     => $request->class_link,
                    'supported_by'   => $authId
                ]);
                $output['messege'] = 'Class Request feedback has been given';
                $output['msgType'] = 'success';
            } else {
                $output['messege'] = 'You are not allow for this Request';
                $output['msgType'] = 'danger';
            }
            return redirect()->back()->with($output);
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    public static function zoomPaaword()
    {
        $password = Str::random(8);
        return $password;
    }

}
