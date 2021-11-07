<?php

namespace App\Http\Controllers\Provider;
use DB;
use Helper;

use Illuminate\Http\Request;
use App\Models\EduStudent_Provider;
use App\Http\Controllers\Controller;
use App\Models\EduExamConfig_Provider;
use App\Models\EduAssignBatch_Provider;
use App\Models\EduStudentExam_Provider;
use App\Models\EduClassAssignments_Provider;
use App\Models\EduStudentAttendence_Provider;
use App\Models\EduAssignBatchClasses_Provider;
use App\Models\EduAssignBatchStudent_Provider;
use App\Models\EduBatchStudentRemark_Provider;
use App\Models\EduStudentPerformance_Provider;
use App\Models\EduAssignBatchSchedule_Provider;
use App\Models\EduStudentPracticeTime_Provider;
use App\Models\EduAssignmentSubmission_Provider;
use App\Models\EduStudentVideoWatchInfo_Provider;
use App\Models\EduStudentProgress_Provider;

class AnalysisActivityController extends Controller
{
    public function index()
    {

        $data['all_batches'] = $all_batches = EduAssignBatch_Provider::join('edu_courses', 'edu_courses.id', '=', 'edu_assign_batches.course_id')
            ->select('edu_assign_batches.*', 'edu_courses.course_name')
            ->where('edu_assign_batches.valid', 1)
            ->get();
        
        foreach($all_batches as $key =>$batch){
            $batch->schedules = EduAssignBatchSchedule_Provider::valid()->where('batch_id',$batch->id)->get();
            $batch->total_class = EduAssignBatchClasses_Provider::valid()
                ->where('batch_id',$batch->id)
                ->where('course_id',$batch->course_id)
                ->count();
        }

        return view('provider.analysisActivity.listData', $data);

    }

    public function batchStudents(Request $request)
    {   
        $data['batch_id'] = $batch_id =  $request->batch_id;
        $batch_course_id = EduAssignBatch_Provider::valid()->find($batch_id)->course_id;
        $data['all_students'] = $all_students = EduStudent_Provider::join('edu_assign_batch_students','edu_assign_batch_students.student_id','=','users.id')
            ->select('users.*','edu_assign_batch_students.course_id','edu_assign_batch_students.batch_id')
            ->where('edu_assign_batch_students.batch_id',$batch_id)
            ->where('edu_assign_batch_students.course_id',$batch_course_id)
            ->where('edu_assign_batch_students.active_status',1)
            ->where('edu_assign_batch_students.valid',1)
            ->where('users.valid',1)
            ->get();

        $assign_batch_class_ids = EduAssignBatchClasses_Provider::valid()
            ->where('batch_id',$batch_id)
            ->where('complete_status',1)
            ->pluck('id')->toArray();

        foreach($all_students as $key => $student){

            $student->student_remark = EduBatchStudentRemark_Provider::valid()
                ->where('batch_id', $batch_id)
                ->where('course_id', $batch_course_id)
                ->where('student_id', $student->id)
                ->first();
            //total attendence
            $student->total_attendence = $total_attendence = EduStudentAttendence_Provider::valid()
                ->where('batch_id', $batch_id)
                ->where('student_id', $student->id)
                ->count();
            // end total attendence

            // total attend and teacher mark
            $student->attend = EduStudentAttendence_Provider::valid()
                ->where('batch_id', $batch_id)
                ->where('student_id', $student->id)
                ->where('is_attend',1)
                ->count();

            // last 5 missing class
            $last_three_assign_batch_class_id = EduAssignBatchClasses_Provider::valid()
                ->where('batch_id',$batch_id)
                ->where('complete_status',1)
                ->orderBy('class_id', 'desc')
                ->limit(3)->pluck('id')->toArray();

            if(count($last_three_assign_batch_class_id) > 1){
                $exact_last_two_class = array_slice($last_three_assign_batch_class_id, 0, 2);
                $exact_last_three_class = [];

                if(count($last_three_assign_batch_class_id) > 2){
                    $exact_last_two_class = array_slice($last_three_assign_batch_class_id, 0, 2);
                    $exact_last_three_class = array_slice($last_three_assign_batch_class_id, 0, 3);
                }

                $count_last_two_missing_class = EduStudentAttendence_Provider::valid()
                    ->where('batch_id', $batch_id)
                    ->where('student_id', $student->id)
                    ->whereIn('class_id',$exact_last_two_class)
                    ->where('is_attend',0)
                    ->count();

                $count_last_three_missing_class = EduStudentAttendence_Provider::valid()
                    ->where('batch_id', $batch_id)
                    ->where('student_id', $student->id)
                    ->whereIn('class_id',$exact_last_three_class)
                    ->where('is_attend',0)
                    ->count();

                if($count_last_three_missing_class == 3){
                    $student->total_last_missing_class = 'Last 3 Missing Class';
                }else if($count_last_two_missing_class == 2){
                    $student->total_last_missing_class = 'Last 2 Missing Class';
                }else{
                    $student->total_last_missing_class = 'Regular';
                }

            }else{
                $student->total_last_missing_class = 'Regular';
            }
            
            // last class attend
            $last_assign_batch_class_id = EduAssignBatchClasses_Provider::valid()
                ->where('batch_id',$batch_id)
                ->where('complete_status',1)
                ->orderBy('class_id', 'desc')
                ->first()->id;

            $last_class_attend = EduStudentAttendence_Provider::valid()
                ->where('batch_id', $batch_id)
                ->where('student_id', $student->id)
                ->where('class_id',$last_assign_batch_class_id)
                ->where('is_attend',1)
                ->count();
            if($last_class_attend > 0){
                $student->last_class_attend = 'Yes';
            }else{
                $student->last_class_attend = 'No';
            }
            // end last class attend

            // STUDENT PERFORMANCE TABLE
            $studentPerforms = EduStudentPerformance_Provider::valid()
                ->where('batch_id', $batch_id)
                ->whereIn('assign_batch_classes_id', $assign_batch_class_ids)
                ->where('student_id', $student->id)
                ->get();

            $student->practice_time    = round($studentPerforms->avg('practice_time'), 2);
            $student->video_watch_time = round($studentPerforms->avg('video_watch_time'), 2);
            $student->attendence       = round($studentPerforms->avg('attendence'), 2);
            $student->class_mark       = round($studentPerforms->avg('class_mark'), 2);
            $student->assignment       = round($studentPerforms->avg('assignment'), 2);
            $student->quiz             = round($studentPerforms->avg('quiz'), 2);
            // STUDENT PERFORMANCE TABLE

            $std_progress             = EduStudentProgress_Provider::valid()->where('type',1)->first();

            $std_practice_progress    = ($student->practice_time * $std_progress->practice_time) / 100;
            $std_video_watch_progress = ($student->video_watch_time * $std_progress->video_watch_time) / 100;
            $std_attendence_progress  = ($student->attendence * $std_progress->attendence) / 100;
            $std_class_mark_progress  = ($student->class_mark * $std_progress->class_mark) / 100;
            $std_assignment_progress  = ($student->assignment * $std_progress->assignment) / 100;
            $std_quiz_progress        = ($student->quiz * $std_progress->quiz) / 100;

            $student->main_score = round($std_practice_progress + $std_video_watch_progress+ $std_attendence_progress + $std_class_mark_progress + $std_assignment_progress + $std_quiz_progress, 2) ;
            // STUDENT PERFORMANCE TABLE
        }
        return view('provider.analysisActivity.student.listData', $data);

    }

    public function remarkUpdate(Request $request, $student_id, $batch_id)
    {
        $data['student_id'] = $student_id = $student_id;
        $data['batch_id'] = $batch_id = $batch_id;

        $data['remark_info'] = EduBatchStudentRemark_Provider::valid()
            ->where('student_id', $student_id)
            ->where('batch_id', $batch_id)
            ->first();

        return view('provider.analysisActivity.student.updateRemark', $data);
    }

    public function remarkUpdateAction(Request $request, $student_id, $batch_id)
    {
        $data['student_id'] = $student_id = $student_id;
        $data['batch_id'] = $batch_id = $batch_id;
        $batch_course_id = EduAssignBatch_Provider::valid()->find($batch_id)->course_id;

        $remark_info = EduBatchStudentRemark_Provider::valid()
            ->where('student_id', $student_id)
            ->where('batch_id', $batch_id)
            ->first();

        if (!empty($remark_info)) {
            $remark_info->update([
                'remark' => $request->remark
            ]);
            $output['messege'] = 'Student Remark has been Updated';
            $output['msgType'] = 'success';
        } else {
            EduBatchStudentRemark_Provider::create([
                'batch_id'   => $batch_id,
                'course_id'  => $batch_course_id,
                'student_id' => $student_id,
                'remark'     => $request->remark
            ]);
            $output['messege'] = 'Student Remark has been Created';
            $output['msgType'] = 'success';
        }
        return redirect()->back()->with($output);
    }
}
