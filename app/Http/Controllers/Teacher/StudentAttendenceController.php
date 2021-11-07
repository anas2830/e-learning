<?php

namespace App\Http\Controllers\Teacher;

use DB;
use Auth;
use File;
use Helper;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EduCourses_Teacher;
use App\Models\EduEventSms_Teacher;
use App\Models\EduStudent_Provider;
use App\Http\Controllers\Controller;
use App\Models\EduAssignBatch_Teacher;
use App\Models\EduCourseAssignClass_Teacher;
use App\Models\EduStudentAttendence_Teacher;
use App\Models\EduAssignBatchClasses_Teacher;
use App\Models\EduAssignBatchStudent_Teacher;
use App\Models\EduCourseClassMaterials_Teacher;
use App\Models\EduStudentVideoWatchInfo_Teacher;
use App\Models\EduStudentPracticeTime_Teacher;
use App\Models\EduStudentPerformance_Teacher;
use App\Models\EduExamConfig_Teacher;
use App\Models\EduStudentExam_Teacher;
use App\Models\EduClassAssignments_Teacher;
use App\Models\EduAssignmentSubmission_Teacher;
use App\Models\EduStudentWiseClassAssignment_Teacher;
use App\Models\EduGroupStudyAttendence_Teacher;


class StudentAttendenceController extends Controller
{
    public function index()
    {
        $authId = Auth::guard('teacher')->id();
        $data['assign_batches'] = EduAssignBatch_Teacher::join('edu_courses', 'edu_courses.id', '=', 'edu_assign_batches.course_id')
            ->select('edu_assign_batches.*', 'edu_courses.course_name')
            ->where('edu_assign_batches.valid', 1)
            ->where('edu_assign_batches.teacher_id', $authId)
            ->where('edu_courses.valid', 1)
            ->orderBy('edu_assign_batches.id', 'desc')
            ->get();
        return view('teacher.studentAttendence.assignBatchListData', $data);
    }
    public function classList(Request $request, $batch_id)
    {
        $assignBatchInfo = EduAssignBatch_Teacher::valid()->find($batch_id);
        $data['batch_no']=  $assignBatchInfo->batch_no;
        $data['course_name']= EduCourses_Teacher::valid()->find($assignBatchInfo->course_id)->course_name;
        $data['assign_classes'] = $assign_classes = EduAssignBatchClasses_Teacher::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
            ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name')
            ->where('edu_assign_batch_classes.batch_id', $batch_id)
            ->where('edu_assign_batch_classes.valid', 1)
            ->where('edu_course_assign_classes.valid', 1)
            ->orderBy('edu_assign_batch_classes.id', 'asc')
            ->get();
        foreach ($assign_classes as $key => $class) {
            $class->isAttendanceDone = EduStudentAttendence_Teacher::valid()->where('batch_id', $batch_id)->where('class_id', $class->id)->count();
        }
        return view('teacher.studentAttendence.classListData', $data);
    }
    public function giveAttendence(Request $request)
    {
        $data['assign_class_id'] = $assign_class_id = $request->batch_class_id;
        $data['assignBatchClassInfo'] = $assignBatchClassInfo = EduAssignBatchClasses_Teacher::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
            ->join('edu_assign_batches', 'edu_assign_batches.id', '=', 'edu_assign_batch_classes.batch_id')
            ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name', 'edu_assign_batches.batch_no', 'edu_assign_batches.course_id')
            ->where('edu_course_assign_classes.valid', 1)
            ->where('edu_assign_batch_classes.valid', 1)
            ->where('edu_assign_batch_classes.id', $assign_class_id)
            ->first();
        $data['batch_no'] =  $assignBatchClassInfo->batch_no;
        $data['course_info'] = EduCourses_Teacher::valid()->find($assignBatchClassInfo->course_id);

        $done_attendence = EduStudentAttendence_Teacher::valid()
            ->where('batch_id',$assignBatchClassInfo->batch_id)
            ->where('class_id',$assign_class_id)
            ->count(); 
        // START STUDENT PREVIOUS CLASS INFO
        $last_assign_batch_info = EduAssignBatchClasses_Teacher::valid()
            ->where('batch_id',$assignBatchClassInfo->batch_id)
            ->where('course_id', $assignBatchClassInfo->course_id)
            ->where('complete_status', 1)
            ->orderBy('id', 'desc')
            ->first();

        if(!empty($last_assign_batch_info)){
            $previous_attendence = EduStudentAttendence_Teacher::valid()
                ->where('batch_id',$assignBatchClassInfo->batch_id)
                ->where('course_id', $assignBatchClassInfo->course_id)
                ->where('class_id', $last_assign_batch_info->id)
                ->get(['student_id','is_attend']);
            
            //eheck video
            $last_class_video = EduCourseClassMaterials_Teacher::valid()
                ->where('course_id', $last_assign_batch_info->course_id)
                ->where('class_id', $last_assign_batch_info->class_id)
                ->count();

            if($last_class_video > 0 ){
                $hasVideo = true;
            }else{
                $hasVideo = false;
            }
            //end check video

            //check quiz
            $last_class_quiz = EduExamConfig_Teacher::valid()
                ->where('assign_batch_class_id', $last_assign_batch_info->id)
                ->count();

            if($last_class_quiz > 0){
                $hasQuiz = true;
            }else{
                $hasQuiz = false;
            }
            //end check quiz

            //check assignment
            $last_class_assignment = EduClassAssignments_Teacher::valid()
                ->where('assign_batch_class_id', $last_assign_batch_info->id)
                ->count();

            if($last_class_assignment > 0){
                $hasAssignment = true;
            }else{
                $hasAssignment = false;
            }

            if($hasAssignment){
                $studentWiseAssignment = EduStudentWiseClassAssignment_Teacher::valid()
                    ->where('assign_batch_classes_id', $last_assign_batch_info->id)
                    ->first();

                if(!empty($studentWiseAssignment)){
                    $throwAssignment = true;
                }else{
                    $throwAssignment = false;
                }
            }
            //end check assignment

            //eheck group study
            $last_class_gs = EduGroupStudyAttendence_Teacher::valid()
                ->where('assign_batch_class_id', $last_assign_batch_info->id)
                ->count();
            if($last_class_gs > 0){
                $hasGs = true;
            }else{
                $hasGs = false;
            }
            //end check group study
        
            $previous_studentt_attendence = [];
            foreach($previous_attendence as $Key => $p_attendence){

                $performance_info = EduStudentPerformance_Teacher::valid()
                    ->where('assign_batch_classes_id', $last_assign_batch_info->id)
                    ->where('student_id', $p_attendence->student_id)
                    ->first();

                //check video
                if($hasVideo){
                    if(empty($performance_info)){
                        $not_watch_video = true;
                    }else{
                        if($performance_info->video_watch_time == 0.00){
                            $not_watch_video = true;
                        }else{
                            $not_watch_video = false;
                        }
                    }
                }else{
                    $not_watch_video = false;
                }
                //end check video

                //eheck paractice time
                if(empty($performance_info)){
                    $not_practice = true;
                }else{
                    if($performance_info->practice_time == 0.00){
                        $not_practice = true;
                    }else{
                        $not_practice = false;
                    }
                }
                //end check practice time

                //check quiz
                if($hasQuiz){
                    $checkQuiz = EduStudentExam_Teacher::valid()
                        ->where('assign_batch_class_id', $last_assign_batch_info->id)
                        ->where('student_id', $p_attendence->student_id)
                        ->first();
                    if(empty($checkQuiz)){
                        $not_quiz = true;
                    }else{
                        $not_quiz = false;
                    }
                }else{
                    $not_quiz = false;
                }
                //end check quiz

                //cehck assignment
                if($throwAssignment){
                    $checkAssignment = EduAssignmentSubmission_Teacher::valid()
                        ->where('assignment_id', $studentWiseAssignment->class_assignment_id)
                        ->where('created_by', $p_attendence->student_id)
                        ->first();

                    if(empty($checkAssignment)){
                        $not_assignment = true;
                    }else{
                        $not_assignment = false;
                    }
                }else{
                    $not_assignment = false;
                }
                //end check assignment

                //check gs
                if($hasGs){

                    $checkGs = EduGroupStudyAttendence_Teacher::valid()
                        ->where('assign_batch_class_id', $last_assign_batch_info->id)
                        ->where('student_id', $p_attendence->student_id)
                        ->first();

    
                    if(empty($checkGs)){
                        $not_gs = true;
                    }else{
                        if($checkGs->is_attend == 0){
                            $not_gs = true;
                        }else{
                            $not_gs = false;
                        }
                    }
                }else{
                    $not_gs = false;
                }

                //end class gs

                $previous_studentt_attendence[$p_attendence->student_id]['attendence'] = $p_attendence->is_attend;
                $previous_studentt_attendence[$p_attendence->student_id]['watchVideo'] = $not_watch_video;
                $previous_studentt_attendence[$p_attendence->student_id]['practiceTime'] = $not_practice;
                $previous_studentt_attendence[$p_attendence->student_id]['quiz'] = $not_quiz;
                $previous_studentt_attendence[$p_attendence->student_id]['assignment'] = $not_assignment;
                $previous_studentt_attendence[$p_attendence->student_id]['groupStudy'] = $not_gs;
            }

            $data['previous_studence_attendence'] = $previous_studentt_attendence;
        }else{
            $data['previous_attendence'] = [];
        }
        // END STUDENT PREVIOUS CLASS INFO
        if($done_attendence > 0){
            $data['assign_students'] = [];
        } else{
            $data['assign_students'] = EduAssignBatchStudent_Teacher::join('users', 'users.id', '=', 'edu_assign_batch_students.student_id')
            ->select('edu_assign_batch_students.*', 'users.name', 'users.id as user_id', 'users.student_id as gen_student_id')
            ->where('edu_assign_batch_students.valid', 1)
            ->where('edu_assign_batch_students.active_status', 1)
            ->where('edu_assign_batch_students.batch_id', $assignBatchClassInfo->batch_id)
            ->where('edu_assign_batch_students.course_id', $assignBatchClassInfo->course_id)
            ->get();
        }

        return view('teacher.studentAttendence.giveAttendence', $data);
    }
    
    public function saveAttendence(Request $request)
    {
        $mark_arr = $request->mark;
        $remark = $request->remark;
        $student_arr = $request->student_id;
        $class_id = $request->class_id;
        $class_info = EduAssignBatchClasses_Teacher::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
                    ->select('edu_course_assign_classes.class_name', 'edu_course_assign_classes.course_id', 'edu_assign_batch_classes.class_id')
                    ->where('edu_assign_batch_classes.id',$class_id)
                    ->where('edu_course_assign_classes.valid',1)
                    ->where('edu_assign_batch_classes.valid',1)
                    ->first();

        $assign_students = EduAssignBatchStudent_Teacher::valid()
            ->where('active_status', 1)
            ->where('batch_id', $request->batch_id)
            ->where('course_id', $class_info->course_id)
            ->pluck('student_id')->toArray();

        $batch_no = EduAssignBatch_Teacher::valid()->find($request->batch_id)->batch_no;
        $course_name = EduCourses_Teacher::valid()->find($class_info->course_id)->course_name;

        $validator = Validator::make($request->all(), [

        ]);
        
        if ($validator->passes()) {
            DB::beginTransaction();
            if ( isset($student_arr) && count($student_arr) > 0) {

                $messageData = array();
                foreach($assign_students as $key => $student) 
                {   
                    $attendenceStudent = EduStudentAttendence_Teacher::create([
                        'batch_id'   => $request->batch_id, 
                        'course_id'  => $request->course_id, 
                        'class_id'   => $class_id, 
                        'student_id' => $student, 
                        'is_attend'  => ( isset($student_arr[$student]) && $student == $student_arr[$student] ) ? 1 : 0,
                        'mark'       => ( isset($student_arr[$student]) ) ? $mark_arr[$student] : 0,
                        'remark'     => ( isset($remark[$student]) ) ? $remark[$student] : '',
                    ]);
                    // SEND SMS
                    $attendenceStudentInfo = EduStudentAttendence_Teacher::valid()->find($attendenceStudent->id);

                    if($attendenceStudentInfo->is_attend == 0){
                        $event_message = EduEventSms_Teacher::valid()->where('type', 4)->where('status',1)->first();
                        if(!empty($event_message)){
                            $message = $event_message->message;
                            $studentName = Helper::studentInfo($student)->name;
                            $studentPhone = Helper::studentInfo($student)->phone;
                            $className =  $class_info->class_name;
    
                            if(preg_match("~\@"."name"."\@~", $message)){
                                $message = str_replace("@name@", $studentName , $message);
                            }
                            if(preg_match("~\@"."class"."\@~", $message)){
                                $message = str_replace("@class@", $className , $message);
                            }
                            if(preg_match("~\@"."batch"."\@~", $message)){
                                $message = str_replace("@batch@", $batch_no , $message);
                            }
                            if(preg_match("~\@"."course"."\@~", $message)){
                                $message = str_replace("@course@", $course_name , $message);
                            }

                            $messageData[$key]['msisdn'] =$studentPhone;
                            $messageData[$key]['text'] = $message;
                            $messageData[$key]['csms_id'] = Str::random(12);
                        }
                    }
                }

                if(count($messageData) > 0){
                    Helper::dynamicSms($messageData);
                }
                
                $output['messege'] =  $class_info->class_name.' '.'Attendence has been Submited';
                $output['msgType'] = 'success';
            } else{
                $output['messege'] =  $class_info->class_name.' '.'Attendence not taken !!';
                $output['msgType'] = 'danger';
            }
            DB::commit();
            
            
            // return redirect()->back()->with($output);
            return redirect()->route('teacher.batchstuClassList', ['batch_id' => $request->batch_id])->with($output);
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    public function showAttendence(Request $request)
    {
        $batch_class_id = $request->batch_class_id;

        $data['attendenceLists'] = EduStudentAttendence_Teacher::join('users','users.id','=','edu_student_attendences.student_id')
            ->select('edu_student_attendences.*','users.name','users.phone','users.student_id')
            ->where('edu_student_attendences.class_id',$batch_class_id)
            ->where('edu_student_attendences.valid',1)
            ->get();

        // echo "<pre>";
        // print_r($assign_students->toArray()); exit();

        return view('teacher.studentAttendence.showAttendence', $data);
    }
}
