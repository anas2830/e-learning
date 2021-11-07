<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;  
use DB;

use App\Models\EduStudentLiveClassSchedule_User;
use App\Models\EduAssignBatchStudent_User;
use App\Models\EduAssignBatchClasses_User;

class StdLiveClassController extends Controller
{
    public function stdLiveClass(Request $request){

        $authId = Auth::id();
        $currentDate = date('Y-m-d H:i:s');
        $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
        
        if(!empty($student_course_info)) {
            $running_assign_class_id = EduAssignBatchClasses_User::valid()
                ->where('batch_id',$student_course_info->batch_id)
                ->where('course_id',$student_course_info->course_id)
                ->where('complete_status',2) // 2 = running
                ->first()->id;

            $liveClassDetails = EduStudentLiveClassSchedule_User::valid()
                ->where(DB::raw('TIMESTAMP(start_date, end_time)'), '>=', $currentDate)
                ->where('assign_batch_classes_id', $running_assign_class_id) 
                ->orderBy('start_date', 'asc')
                ->first();

            if(!empty($liveClassDetails)) {
                $data['liveClassDetails'] = $liveClassDetails;
            } else {
                $data['liveClassDetails'] = null;
            }

        } else {
            $output['messege'] = ' You have not any Running course Yet!!!';
            $output['back_route'] = 'home';
            return view('examError', $output);
        }

        return view('student.classroom.liveClass.countDown', $data);
    }
}
