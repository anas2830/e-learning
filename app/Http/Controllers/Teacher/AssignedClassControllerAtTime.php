<?php

namespace App\Http\Controllers\Teacher;

use DB;
use Auth;

use Helper;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EduCourses_Teacher;
use App\Models\EduStudent_Teacher;
use App\Http\Controllers\Controller;
use App\Models\EduExamConfig_Teacher;
use App\Models\EduAssignBatch_Teacher;
use App\Models\EduStudentExam_Teacher;
use App\Models\EduZoomAccount_Teacher;
use App\Models\EduStudentProgress_Teacher;
use App\Models\EduClassAssignments_Teacher;
use App\Models\EduStudentAttendence_Teacher;
use App\Models\EduAssignBatchClasses_Teacher;
use App\Models\EduStudentPerformance_Teacher;
use App\Models\EduAssignBatchSchedule_Teacher;
use App\Models\EduStudentExamQuestion_Teacher;
use App\Models\EduStudentPracticeTime_Teacher;
use App\Models\EduAssignmentSubmission_Teacher;
use App\Models\EduCourseClassMaterials_Teacher;
use App\Models\EduStudentVideoWatchInfo_Teacher;
use App\Models\EduStudentLiveClassSchedule_Teacher;

class AssignedClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['batch_id'] = $batch_id = $request->batch_id;
        $authId = Auth::guard('teacher')->id();
        $checkBatch = EduAssignBatch_Teacher::valid()
            ->where('teacher_id', $authId)
            ->find($batch_id);

        if(!empty($checkBatch)){
            $data['assigned_classes'] = EduAssignBatchClasses_Teacher::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
                ->select('edu_assign_batch_classes.*','edu_course_assign_classes.class_name')
                ->where('edu_assign_batch_classes.batch_id',$batch_id)
                ->where('edu_assign_batch_classes.valid',1)
                ->where('edu_course_assign_classes.valid',1)
                ->get();

            $data['batcn_info'] = $assignBatchInfo  = EduAssignBatch_Teacher::valid()->find($batch_id);
            $data['course_name']= EduCourses_Teacher::valid()->find($assignBatchInfo->course_id)->course_name;
        }else{
            $data['back_route'] = "teacher.dashboard";
            $data['messege'] = "Sorry !! it's not assign your batch";
            return view('examError', $data);
        }

        return view('teacher.assignBatch.class.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // this method use for live class schedule
    public function show($id)
    {
        $data['assign_batch_class_id'] = $id;
        $data['liveClass_Schedule'] = EduStudentLiveClassSchedule_Teacher::valid()->where('assign_batch_classes_id',$id)->first();
        $data['zoom_account_info'] =  EduZoomAccount_Teacher::valid()->first(); 
        return view('teacher.assignBatch.class.updateLiveSchedule', $data);
    }

    // this method use for update  live class schedule
    public function store(Request $request)
    {
        $liveScheduleid = $request->live_schedule_id;
        $assign_batch_classs_id = $request->assign_batch_classs_id;
        $course_class_id = EduAssignBatchClasses_Teacher::valid()->find($assign_batch_classs_id)->class_id;
        $className = Helper::className($course_class_id);

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

        $zoom_acc_id = $request->zoom_acc_id;
        $zoom_account_info = EduZoomAccount_Teacher::valid()->find($zoom_acc_id); 

        if(!empty($zoom_account_info)){
            $zoom_acc_id = $zoom_account_info->id;
            $email = $zoom_account_info->email;
            $token = $zoom_account_info->token;
        }else{
            $output['messege'] = "Zoom account is not valid !";
            $output['msgType'] = 'danger';
            return redirect()->back()->with($output);
        }
        
        $validator = Validator::make($request->all(), [
            'start_date'  => 'required',
            'start_time'  => 'required',
            'd_hour'      => 'required|numeric',
            'd_min'       => 'required|numeric'
        ]);

        if ($validator->passes()) {

            DB::beginTransaction();
            //FOR ZOOM SCHEDULE CREATE/UPDATE INFO---
            $liveClassData = array(
                'topic'                  => $className,
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

            if (isset($liveScheduleid)) {
                $meeting_id = EduStudentLiveClassSchedule_Teacher::valid()->find($liveScheduleid)->meeting_id;
                //ZOOM INFO
                $curl_url = "https://api.zoom.us/v2/meetings/".$meeting_id;
                $curl_method = "PATCH";
                $message = "updated";
            } else {
                //ZOOM INFO
                $curl_url = "https://api.zoom.us/v2/users/".$email."/meetings";
                $curl_method = "POST";
                $message = "created";
            }

            $postFields = json_encode($liveClassData);

            $zoomInfo = Helper::zoomIntegrationFunction($curl_url, $curl_method, $postFields, $token);

            if (isset($liveScheduleid)) {
                $curl_method = "GET";
                $zoomInfo = Helper::zoomGetDelete($token, $curl_method, $meeting_id);
            }

            if (property_exists($zoomInfo["info"], 'code')) {
                $msgStatus = 0;
            } else {
                $msgStatus = 1;
            }  

            if ($msgStatus==1) {
                
                $liveClassZoomData = [
                    'zoom_acc_id'             => $zoom_acc_id,
                    'assign_batch_classes_id' => $assign_batch_classs_id, 
                    'day_dt'                  => $day_dt, 
                    'start_date'              => $start_date, 
                    'start_time'              => strftime('%X', strtotime($start_time)),
                    'end_time'                => $end_time, 
                    'hour'                    => $duration_hours, 
                    'min'                     => $duration_minutes, 
                    'duration'                => $duration_of_min, 
                    'type'                    => $zoomInfo['info']->type,
                    'meeting_id'              => $zoomInfo['info']->id,
                    'host_id'                 => $zoomInfo['info']->host_id,
                    'start_url'               => $zoomInfo['info']->start_url,
                    'join_url'                => $zoomInfo['info']->join_url,
                    'timezone'                => $zoomInfo['info']->timezone
                ];

                if (isset($liveScheduleid)) {
                    EduStudentLiveClassSchedule_Teacher::find($liveScheduleid)->update($liveClassZoomData); // UPDATE
                } else {
                    EduStudentLiveClassSchedule_Teacher::create($liveClassZoomData); //CREATE
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

        }else{
            return redirect()->back()->withErrors($validator);
        }
        

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

    public function classStatus(Request $request,$class_id,$batch_id)
    {

        $data['class_id'] = $class_id = $class_id;
        $data['batch_id'] = $batch_id = $batch_id;

        $data['status'] = EduAssignBatchClasses_Teacher::valid()->find($class_id)->complete_status;

        return view('teacher.assignBatch.class.updateClassStatus', $data);
    }

    public function updateClassStatus(Request $request,$class_id,$batch_id)
    {

        $status = $request->status;
        $assign_batch_class_id = $class_id;
        $doneAttendence = EduStudentAttendence_Teacher::valid()->where('batch_id',$batch_id)->where('class_id',$assign_batch_class_id)->count();

        $validator = Validator::make($request->all(), [
            'status'         => 'required',
        ]);
        
        if ($validator->passes()) {
            $allClassess = EduAssignBatchClasses_Teacher::valid()->where('batch_id', $batch_id)->where('complete_status', 1)->get();

            foreach ($allClassess as $key => $studentClass) {
                $assign_batch_class_id = $studentClass->id;

                $class = EduAssignBatchClasses_Teacher::valid()->where('batch_id', $batch_id)->find($assign_batch_class_id);
                $next_class = EduAssignBatchClasses_Teacher::where('id', '>',$class->id)->where('class_id','>',$class->class_id)->where('batch_id',$batch_id)->first();
                
                $completed_date = date('Y-m-d');
                $completed_day_dt = date('w');
                $day_dt = EduAssignBatchSchedule_Teacher::valid()->where('batch_id',$batch_id)->pluck('day_dt')->toArray();
        
                if(count($day_dt) > 0) {
        
                    $first_class = $day_dt[0];
                    $get_day = '';
                    foreach ($day_dt as $key => $day) { 
                        if($completed_day_dt >= $day)
                        {
                            $get_day = $first_class;
                        } 
                        else 
                        {
                            $higher_day = $completed_day_dt-$day;
                            if($higher_day < 0){
                                $get_day = $day;
                            break;
                            }
                        }
                    }
        
                    $get_day_name = Helper::dayName($get_day);
                    $nextDay = strtotime("next"." ".$get_day_name);
                    $next_class_date = date('Y-m-d', $nextDay);
            
                    if($get_day != ''){
                        $get_day_name = Helper::dayName($get_day);
                        $next_class_time = EduAssignBatchSchedule_Teacher::where('batch_id',$batch_id)->where('day_dt',$get_day)->first()->start_time;
                    } else {
                        $get_day_name = '';
                        $next_class_time = '';
                    }
            
                    if(empty($get_day_name) || empty($next_class_time)){
                        $nextDay = '';
                        $next_class_date = '';
                        $next_class_time =  '';
                    }else{
                        $nextDay = strtotime("next"." ".$get_day_name);
                        $next_class_date = date('Y-m-d', $nextDay);
                        $next_class_time = $next_class_time;
                    }
                } else {
                    $next_class_date = '';
                    $next_class_time =  '';
                }
        
                // for all student progress
                // $data['all_students'] = $all_students = EduStudent_Teacher::join('edu_assign_batch_students','edu_assign_batch_students.student_id','=','users.id')
                //     ->select('users.*','edu_assign_batch_students.course_id')
                //     ->where('edu_assign_batch_students.batch_id', $batch_id)
                //     ->where('edu_assign_batch_students.is_running', 1)
                //     ->where('edu_assign_batch_students.active_status', 1)
                //     ->where('edu_assign_batch_students.valid', 1)
                //     ->where('users.valid',1)
                //     ->get();
        
                // $today_date = date('Y-m-d');
                // $start_class_date = strtotime($class->start_date);
                // $end_class_date = strtotime($today_date);
                // $total_practice_days = ceil(abs($end_class_date - $start_class_date) / 86400);
                // $total_base_practice_time = $total_practice_days * 14400;

                DB::beginTransaction();

                if($completed_date >= $class->start_date){

                    // if($doneAttendence > 0){

                        // EduAssignBatchClasses_Teacher::find($class->id)->update([
                        //     'end_date'       => $completed_date,
                        //     'complete_status'=> $status
                        // ]);

                        // if(!empty($next_class_date) && !empty($next_class_time)){
                        //     if(!empty($next_class)){
                        //         EduAssignBatchClasses_Teacher::find($next_class->id)->update([
                        //             'start_date'     => $next_class_date,
                        //             'start_time'     => $next_class_time,
                        //             'complete_status'=> 2, // 2 = running
                        //         ]);
                        //     }
                        // }
                        //student wise class performance value
                        $haveThisClassPerform = EduStudentPerformance_Teacher::valid()->where('batch_id', $class->batch_id)->where('assign_batch_classes_id', $assign_batch_class_id)->count();
                        if ($haveThisClassPerform == 0) {
                            //THIS BATCH'S ALL STUDENTS
                            $data['all_students'] = $all_students = EduStudent_Teacher::join('edu_assign_batch_students','edu_assign_batch_students.student_id','=','users.id')
                                ->select('users.*','edu_assign_batch_students.course_id')
                                ->where('edu_assign_batch_students.batch_id', $batch_id)
                                ->where('edu_assign_batch_students.is_running', 1)
                                ->where('edu_assign_batch_students.active_status', 1)
                                ->where('edu_assign_batch_students.valid', 1)
                                ->where('users.valid',1)
                                ->get();

                            $today_date = date('Y-m-d');
                            $start_class_date = strtotime($class->start_date);
                            $end_class_date = strtotime($today_date);
                            $total_practice_days = ceil(abs($end_class_date - $start_class_date) / 86400);
                            $total_base_practice_time = $total_practice_days * 14400;
                            
                            foreach($all_students as $key => $student){
                                // CLASS ATTENDENCE & PERFORMANCE MARK
                                    $class_attendence = EduStudentAttendence_Teacher::valid()
                                        ->where('batch_id', $batch_id)
                                        ->where('student_id', $student->id)
                                        ->where('class_id', $assign_batch_class_id)
                                        ->where('is_attend', 1)
                                        ->first();
        
                                    if(!empty($class_attendence)){
                                        $gained_class_mark = $class_attendence->mark > 100 ? 100 : $class_attendence->mark;
                                        $final_attendence_progress = 100;
                                        $final_teacherMark_progress = round($gained_class_mark, 2);
                                    }else{
                                        $final_attendence_progress = 0;
                                        $final_teacherMark_progress = 0;
                                    }
                                // END CLASS ATTENDENCE & PERFORMANCE MARK
    
                                //ASSIGNMENT MARK
                                    $teach_given_assignment_ids = EduClassAssignments_Teacher::valid()
                                        ->where('assign_batch_class_id', $assign_batch_class_id)
                                        ->where('batch_id', $batch_id)
                                        ->get()->pluck('id');
        
                                    if(count($teach_given_assignment_ids) > 0) {
                                        $student_assignment_info = EduAssignmentSubmission_Teacher::valid()
                                            ->whereIn('assignment_id', $teach_given_assignment_ids)
                                            ->where('created_by', $student->id)
                                            ->first();
                                        if(!empty($student_assignment_info)) {
                                            $gained_assignemnt_mark = $student_assignment_info->mark > 100 ? 100 : $student_assignment_info->mark;
                                            if($gained_assignemnt_mark > 0 ) {
                                                $assignment_progress = $gained_assignemnt_mark;
                                                $final_assignment_progress = round($assignment_progress, 2);
                                            } else {
                                                $final_assignment_progress = 0;
                                            }
                                        } else {
                                            $final_assignment_progress = 0;
                                        }
                                    } else {
                                        $final_assignment_progress = 100;
                                    }
                                //END ASSIGNMENT MARK
                    
                                //TOTAL PRACTICE TIME
                                    $gained_practice_time = EduStudentPracticeTime_Teacher::valid()
                                        ->where('created_by', $student->id)
                                        ->where('course_id', $class->course_id)
                                        ->where('batch_id', $class->batch_id)
                                        ->whereDate('date', '>=', $class->start_date)
                                        ->whereDate('date', '<', $today_date)
                                        ->sum('total_time');
        
                                    if($gained_practice_time >= $total_base_practice_time){
                                        $gained_practice_time = $total_base_practice_time;
                                    }
        
                                    if($gained_practice_time > 0){
                                        $practice_time_percentage = ($gained_practice_time * 100) / $total_base_practice_time;
                                        $practice_time_percentage = $practice_time_percentage >= 100 ? 100 : $practice_time_percentage;
                                        $final_practice_time_progress = round($practice_time_percentage, 2);
                                    }else{
                                        $final_practice_time_progress = 0;
                                    }
                                // END TOTAL PRACTICE TIME
                    
                                // TOTAL VIDEO WATCH TIME
                                    $base_video_duration = EduCourseClassMaterials_Teacher::valid()
                                        ->where('course_id', $class->course_id)
                                        ->where('class_id', $class->class_id)
                                        ->sum('video_duration');
                                    $gained_watch_time = EduStudentVideoWatchInfo_Teacher::valid()
                                        ->where('batch_id', $batch_id)
                                        ->where('student_id', $student->id)
                                        ->where('assign_batch_class_id', $assign_batch_class_id)
                                        ->sum('watch_time');
        
                                    if($gained_watch_time > 0){
                                        $watch_time_percentage = ($gained_watch_time * 100) / $base_video_duration;
                                        $watch_time_percentage = $watch_time_percentage > 100 ? 100 : $watch_time_percentage;
                                        $final_watch_time_progress = round($watch_time_percentage, 2);
                                    }else{
                                        $final_watch_time_progress = 0;
                                    }
                                // END TOTAL VIDEO WATCH TIME
                    
                                // QUIZ MARK
                                    $check_class_exam = EduExamConfig_Teacher::valid()->where('assign_batch_class_id', $assign_batch_class_id)->where('batch_id', $batch_id)->first();
        
                                    if(!empty($check_class_exam)){
                                        $base_exam_mark = $check_class_exam->total_question * $check_class_exam->per_question_mark;
                                        $student_exam_info = EduStudentExam_Teacher::valid()
                                            ->where('exam_config_id', $check_class_exam->id)
                                            ->where('student_id', $student->id)
                                            ->first();
        
                                        if(!empty($student_exam_info)) {
                                            $gained_exam_mark = $student_exam_info->total_correct_answer * $check_class_exam->per_question_mark;
                                            if($gained_exam_mark > 0) {
                                                $exam_percentage = ($gained_exam_mark * 100) / $base_exam_mark;
                                                $exam_percentage = $exam_percentage > 100 ? 100 : $exam_percentage;
                                                $final_exam_progress = round($exam_percentage, 2);
                                            } else {
                                                $final_exam_progress = 0;
                                            }
                                        } else {
                                            $final_exam_progress = 0;
                                        }
                                    } else {
                                        $final_exam_progress = 100;
                                    }
                                // END QUIZ MARK
        
                                EduStudentPerformance_Teacher::create([
                                    'student_id'              => $student->id,
                                    'course_id'               => $class->course_id,
                                    'course_class_id'         => $class->class_id,
                                    'batch_id'                => $class->batch_id,
                                    'assign_batch_classes_id' => $assign_batch_class_id,
                                    'practice_time'           => $final_practice_time_progress,
                                    'video_watch_time'        => $final_watch_time_progress,
                                    'attendence'              => $final_attendence_progress,
                                    'class_mark'              => $final_teacherMark_progress,
                                    'assignment'              => $final_assignment_progress,
                                    'quiz'                    => $final_exam_progress,
                                ]);
                            }
                            $output['messege'] = 'Class Status has been Updated';
                            $output['msgType'] = 'success';
                        } else {
                            $output['messege'] = 'Already Submitted!!!';
                            $output['msgType'] = 'danger';
                        }
                    // }else{
                    //     $output['messege'] = 'Please At first take attendence';
                    //     $output['msgType'] = 'danger';
                    // }
                    
                } else{
                    $output['messege'] = 'Class date is not past yet !!';
                    $output['msgType'] = 'danger';
                }

                DB::commit();
            }
            return redirect()->back()->with($output);
        } else{
            return redirect()->back()->withErrors($validator);
        }
        
    }

    public static function zoomPaaword()
    {
        $password = Str::random(8);
        return $password;
    }


}
