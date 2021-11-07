<?php

namespace App\Http\Controllers\Provider;

use Auth;
use File;
use Helper;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request; 
use App\Models\EduCourses_Provider;
use App\Models\EduSendSms_Provider;
use App\Models\EduStudent_Provider;
use App\Models\EduSupport_Provider;
use App\Models\EduTeacher_Provider;
use App\Http\Controllers\Controller;
use App\Models\EduAssignBatch_Provider;
use App\Models\EduAssignBatchStudent_Provider;

class SendSmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['all_sms'] = EduSendSms_Provider::valid()->get();
        return view('provider.sendSms.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['course_list'] = EduCourses_Provider::valid()->where('publish_status',1)->get();
        $data['batch_list'] = EduAssignBatch_Provider::valid()->where('active_status',1)->get();
        $data['students_list'] = EduStudent_Provider::valid()->where('active_status',1)->get();

        $data['teachers_list'] = EduTeacher_Provider::valid()->where('active_status',1)->get();
        $data['supports_list'] = EduSupport_Provider::valid()->where('active_status',1)->get();

        return view('provider.sendSms.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $output = array();
        $input =  $request->all();
        $user_type = $request->user_type;

        $validator = [
            'user_type'  => 'required',
            'message'    => 'required',
        ];

        if($user_type == 1){

            $validator['student_filter_type'] = 'required';

            if(isset($request->student_filter_type) && $request->student_filter_type == 1){
                $validator['course_id'] = 'required';
                $validator['batch_id'] = 'required';
                $validator['student_type'] = 'required';
            }

            if(isset($request->student_type) && $request->student_type == 1){
                $validator['student_ids'] = 'required';
            }

            if(isset($request->student_without_batch_type) && $request->student_without_batch_type == 1){
                $validator['student_without_batch_ids'] = 'required';
            }
    
        }

        
        if($user_type == 2){
            $validator['teacher_type'] = 'required';

            if(isset($request->teacher_type) && $request->teacher_type == 1){
                $validator['teacher_ids'] = 'required';
            }
        }

        if($user_type == 3){
            $validator['support_type'] = 'required';

            if(isset($request->support_type) && $request->support_type == 1){
                $validator['support_ids'] = 'required';
            }
        }

        $validator = Validator::make($input, $validator);

        if ($validator->passes()) {

            $message = $request->message;
            
            switch ($user_type) {

                case "1":
                    $course_id = $request->course_id;
                    $batch_id = $request->batch_id;
                    $student_filter_type = $request->student_filter_type;
                    $messageBody = $message;

                    if($student_filter_type == 1){
                        $student_type =  $request->student_type;
                        if($student_type == 0){
                            $users = EduAssignBatchStudent_Provider::join('users','users.id', '=','edu_assign_batch_students.student_id')
                                ->select('users.id','users.name','users.phone')
                                ->where('edu_assign_batch_students.course_id', $course_id)
                                ->where('edu_assign_batch_students.batch_id',$batch_id)
                                ->get();
                        }else{
                            $students_ids = $request->student_ids;
                            $users = EduAssignBatchStudent_Provider::join('users','users.id', '=','edu_assign_batch_students.student_id')
                                ->select('users.id','users.phone','users.name')
                                ->where('edu_assign_batch_students.course_id', $course_id)
                                ->where('edu_assign_batch_students.batch_id', $batch_id)
                                ->whereIn('edu_assign_batch_students.student_id', $students_ids)
                                ->get();
                        }
                    }else{
                        $student_without_batch_type = $request->student_without_batch_type;
                        if($student_without_batch_type == 0){
                            $users = EduAssignBatchStudent_Provider::join('users','users.id', '=','edu_assign_batch_students.student_id')
                                ->select('users.id','users.name','users.phone')
                                ->get();
                        }else{
                            $student_without_batch_ids = $request->student_without_batch_ids;
                            $users = EduAssignBatchStudent_Provider::join('users','users.id', '=','edu_assign_batch_students.student_id')
                                ->select('users.id','users.phone','users.name')
                                ->whereIn('edu_assign_batch_students.student_id', $student_without_batch_ids)
                                ->get();
                        }
                    }

                    $messageData = array();
                    foreach($users as $key => $user){

                        if(preg_match("~\@"."name"."\@~", $messageBody)){
                            $messageBody = str_replace("@name@", $user->name , $messageBody);
                        }

                        $messageData[$key]['msisdn'] =$user->phone;
                        $messageData[$key]['text'] = $messageBody;
                        $messageData[$key]['csms_id'] = Str::random(12);
                    }

                    if(count($messageData) > 0){
                        $smsResponse = Helper::dynamicSms($messageData);
                    }

                    $smsResponse = json_decode($smsResponse);

                    if ($smsResponse->status == 'FAILED') {
                        $output['messege'] = $smsResponse->error_message;
                        $output['msgType'] = 'danger';   
                    } else {
                        if($student_filter_type == 1){
                            EduSendSms_Provider::create([
                                'batch_id'          => $batch_id,
                                'course_id'         => $course_id,
                                'sms_receiver_id'   => $student_type, //0=all,1=selected users
                                'sms_receiver_type' => 1, //1=student,2=teacher,3=support
                                'message'           => $request->message,
                                'date'              => date('Y-m-d'),
                            ]);
                        }else{
                            EduSendSms_Provider::create([
                                'batch_id'          => null,
                                'course_id'         => null,
                                'sms_receiver_id'   => $student_without_batch_type, //0=all,1=selected users
                                'sms_receiver_type' => 1, //1=student,2=teacher,3=support
                                'message'           => $request->message,
                                'date'              => date('Y-m-d'),
                            ]);
                        }

                        $output['messege'] = 'Sms has been Send Successfully!';
                        $output['msgType'] = 'success';
                    }

                  break;

                case "2":
                    $teacher_type = $request->teacher_type;
                    $messageBody = $message;

                    if($teacher_type == 0){
                        $teachers = EduTeacher_Provider::valid()->get(['name','phone']);
                    }else{
                        $teacher_ids = $request->teacher_ids;
                        $teachers = EduTeacher_Provider::valid()->whereIn('id', $teacher_ids)->get(['name','phone']);
                    }

                    $messageData = array();
                    foreach($teachers as $key => $teacher){

                        if(preg_match("~\@"."name"."\@~", $messageBody)){
                            $messageBody = str_replace("@name@", $teacher->name , $messageBody);
                        }

                        $messageData[$key]['msisdn'] =$teacher->phone;
                        $messageData[$key]['text'] = $messageBody;
                        $messageData[$key]['csms_id'] = Str::random(12);
                    }
    
                    if(count($messageData) > 0){
                        $smsResponse = Helper::dynamicSms($messageData);
                    }

                    $smsResponse = json_decode($smsResponse);

                    if ($smsResponse->status == 'FAILED') {
                        $output['messege'] = $smsResponse->error_message;
                        $output['msgType'] = 'danger';   
                    } else {
                        EduSendSms_Provider::create([
                            'sms_receiver_id'   => $teacher_type, //0=all,1=selected users
                            'sms_receiver_type' => 2, //1=student,2=teacher,3=support
                            'message'           => $request->message,
                            'date'              => date('Y-m-d'),
                        ]);
                        $output['messege'] = 'Sms has been Send Successfully!';
                        $output['msgType'] = 'success';
                    }

                  break;

                case "3":

                    $support_type = $request->support_type;
                    $messageBody = $message;

                    if($support_type == 0){
                        $supporters = EduSupport_Provider::valid()->get(['name','phone']);
                    }else{
                        $support_ids = $request->support_ids;
                        $supporters = EduSupport_Provider::valid()->whereIn('id', $support_ids)->get(['name','phone']);
                    }

                    $messageData = array();
                    foreach($supporters as $key => $support){
                        if(preg_match("~\@"."name"."\@~", $messageBody)){
                            $messageBody = str_replace("@name@", $support->name , $messageBody);
                        }
                        $messageData[$key]['msisdn'] =$support->phone;
                        $messageData[$key]['text'] = $messageBody;
                        $messageData[$key]['csms_id'] = Str::random(12);
                    }

                    if(count($messageData) > 0){
                        $smsResponse = Helper::dynamicSms($messageData);
                    }

                    $smsResponse = json_decode($smsResponse);

                    if ($smsResponse->status == 'FAILED') {
                        $output['messege'] = $smsResponse->error_message;
                        $output['msgType'] = 'danger';   
                    } else {
                        EduSendSms_Provider::create([
                            'sms_receiver_id'   => $support_type,
                            'sms_receiver_type' => 3, //1=student,2=teacher,3=support
                            'message'           => $request->message,
                            'date'              => date('Y-m-d'),
                        ]);
                        $output['messege'] = 'Sms has been Send Successfully!';
                        $output['msgType'] = 'success';
                    }

                  break;
                default:
                  echo "Something Wrong !!";
            }

            return redirect()->back()->with($output);

        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
