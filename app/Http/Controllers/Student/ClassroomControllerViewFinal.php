<?php

namespace App\Http\Controllers\Student;

use DB;
use Auth;
use Helper;

use Validator;
use Illuminate\Http\Request;
use App\Models\EduAnswer_User;
use App\Models\EduCourses_User;
use App\Models\EduTeacher_User;
use App\Models\EduExamConfig_User;
use App\Models\EduStudentExam_User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\EduArchiveQuestion_User;
use App\Models\EduStudentProgress_User;
use App\Models\EduClassAssignments_User;
use App\Models\EduAssignmentComment_User;
use App\Models\EduCourseAssignClass_User;
use App\Models\EduStudentAttendence_User;
use App\Models\EduAssignBatchClasses_User;
use App\Models\EduAssignBatchStudent_User;
use App\Models\EduStudentPerformance_User;
use App\Models\EduStudentPerformance_View;
use App\Models\EduAssignBatchSchedule_User;
use App\Models\EduStudentExamQuestion_User;
use App\Models\EduStudentNotification_User;
use App\Models\EduStudentPracticeTime_User;
use App\Models\EduAssignBatchStudent_Global;
use App\Models\EduAssignmentSubmission_User;
use App\Models\EduCourseClassMaterials_User;
use App\Models\EduStudentVideoWatchInfo_User;
use App\Models\EduStudentProgressWithConfig_View;
use App\Models\EduClassAssignmentAttachments_User;
use App\Models\EduAssignmentSubmissionAttachment_User;

class ClassroomController extends Controller
{
    /// start class menu method
    public function classIndex(Request $request)
    {
        $data['upcomming_class_id'] = !empty($request->class_id) ? $request->class_id : '';
        
        $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();

        if (isset($student_course_info)) {
            $data['course_classes'] = $course_classes = EduAssignBatchClasses_User::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
                ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name', 'edu_course_assign_classes.class_overview')
                ->where('edu_assign_batch_classes.valid', 1)
                ->where('edu_assign_batch_classes.batch_id', $student_course_info->batch_id)
                ->where('edu_assign_batch_classes.course_id', $student_course_info->course_id)
                ->orderBy('edu_assign_batch_classes.id', 'asc')
                ->get();
            foreach ($course_classes as $key => $class) {
                $class->materials = EduCourseClassMaterials_User::valid()
                    ->where('course_id', $class->course_id)
                    ->where('class_id', $class->class_id)
                    ->orderBy('id', 'asc')
                    ->get();
            }
        } else {
            $data['course_classes'] = [];
        }

        // echo "<pre>";
        // print_r($data['course_classes']->toArray()); exit();

        return view('student.classroom.class.classIndex', $data);
    }
    public function classDetails(Request $request)
    {
        $assign_batch_class_id = $request->batch_class_id;
        $assign_batch_info = EduAssignBatchClasses_User::valid()->find($assign_batch_class_id);
        if(!empty($assign_batch_info)){
            $data['class_overview'] = EduAssignBatchClasses_User::join('edu_course_assign_classes','edu_course_assign_classes.id','=','edu_assign_batch_classes.class_id')
                ->select('edu_assign_batch_classes.*','edu_course_assign_classes.class_overview','edu_course_assign_classes.class_name')
                ->where('edu_course_assign_classes.course_id', '=', $assign_batch_info->course_id)
                ->where('edu_assign_batch_classes.id',$assign_batch_class_id)
                ->where('edu_assign_batch_classes.valid',1)
                ->where('edu_course_assign_classes.valid',1)
                ->first();
        }else{
            $data['class_overview'] = '';
        }

        return view('student.classroom.class.classDetails',$data);
    }

    public function classResource(Request $request)
    {
        $assign_batch_class_id = $request->batch_class_id;
        $assign_batch_info = EduAssignBatchClasses_User::valid()->find($assign_batch_class_id);
        if(!empty($assign_batch_info)){
            $data['class_resource'] = EduAssignBatchClasses_User::join('edu_course_assign_classes','edu_course_assign_classes.id','=','edu_assign_batch_classes.class_id')
                ->select('edu_assign_batch_classes.id', 'edu_course_assign_classes.class_resource','edu_course_assign_classes.class_name')
                ->where('edu_course_assign_classes.course_id', '=', $assign_batch_info->course_id)
                ->where('edu_assign_batch_classes.id',$assign_batch_class_id)
                ->where('edu_assign_batch_classes.valid',1)
                ->where('edu_course_assign_classes.valid',1)
                ->first();
        }else{
            $data['class_resource'] = '';
        }
        return view('student.classroom.class.classResource',$data);
    }

    public function updateVideoWatchTime(Request $request){
        $materialId = $request->materialId;
        $curDuration = $request->curDuration;
        $assign_batch_class_id = $request->batch_class_id;
        $assignBatchClassInfo = EduAssignBatchClasses_User::valid()->find($assign_batch_class_id);
        $output['auth'] = $authId = Auth::id();
        if ($assignBatchClassInfo->complete_status != 1) {
            $video = EduCourseClassMaterials_User::valid()->find($materialId);
            $curDuration = Helper::timeToSecond($curDuration);
    
            $percentage = ($curDuration<$video->video_duration) ? ($curDuration/$video->video_duration)*100 : 100;
            $full_watched = ($percentage>=95) ? 1 : 0;
          
            $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
            
            $video_assign_batch_class_id = EduAssignBatchClasses_User::valid()
                ->where('course_id',$video->course_id)
                ->where('class_id',$video->class_id)
                ->where('batch_id',$student_course_info->batch_id)
                ->first()->id;
    
            $exists_data = EduStudentVideoWatchInfo_User::valid()
                ->where('batch_id',$student_course_info->batch_id)
                ->where('course_id',$student_course_info->course_id)
                ->where('assign_batch_class_id',$video_assign_batch_class_id)
                ->where('material_id',$materialId)
                ->first();
    
            if(!empty($exists_data)){
                if($exists_data->is_complete != 1){
                    EduStudentVideoWatchInfo_User::find($exists_data->id)->update([
                        "watch_time"            =>  $curDuration,
                        "video_duration"        =>  $video->video_duration,
                        "is_complete"           =>  $full_watched
                    ]);
                }
                $output['msgType'] = 'watch time update';
            } else {
                EduStudentVideoWatchInfo_User::create([
                    "student_id"            =>  $authId,
                    "batch_id"              =>  $student_course_info->batch_id,
                    "course_id"             =>  $student_course_info->course_id,
                    "assign_batch_class_id" =>  $assign_batch_class_id,
                    "material_id"           =>  $materialId,
                    "watch_time"            =>  $curDuration,
                    "video_duration"        =>  $video->video_duration,
                    "is_complete"           =>  $full_watched,
                    "date"                  =>  date('Y-m-d'),
                ]);
    
                $output['msgType'] = 'watch time create';
            }
        } else {
            $output['msgType'] = 'Class Already Completed!!';
        }

        return response($output);
    }

    public function assignments(Request $request)
    {
        $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
        $data['assign_batch_class_id'] = $assign_batch_class_id = $request->batch_class_id;
        $user_id = Auth::id();
        if (isset($student_course_info)) {
            $my_final_progress = self::getStudentFinalProgress($user_id);

            $data['assignments'] = $assignments = EduClassAssignments_User::join('edu_teachers','edu_teachers.id','=','edu_class_assignments.created_by')
                ->select('edu_class_assignments.*','edu_teachers.name')
                ->where('edu_class_assignments.assign_batch_class_id',$assign_batch_class_id)
                ->where('edu_class_assignments.valid',1)
                ->get();
    
            foreach ($assignments as $key => $value) {
                $assignBatchClassStatus = EduAssignBatchClasses_User::valid()->find($assign_batch_class_id)->complete_status;
                if ($my_final_progress <= 60) {
                    $value->completeStatus = false;
                } else {
                    $value->completeStatus = $assignBatchClassStatus == 1 ? true : false;
                }
                $value->attachment = EduClassAssignmentAttachments_User::valid()->where('class_assignment_id',$value->id)->first();
                $value->submitted = $submitted = EduAssignmentSubmission_User::valid()->where('assignment_id',$value->id)->where('created_by',$user_id)->first();
                if (!empty($value->submitted)) {
                    $value->submittedAttachment = EduAssignmentSubmissionAttachment_User::valid()->where('assignment_submission_id',$submitted->id)->first();
                }
                $value->teacherComment = EduAssignmentComment_User::join('edu_teachers', 'edu_teachers.id', '=', 'edu_assignment_comments.created_by')
                    ->select('edu_assignment_comments.*', 'edu_teachers.name as teacher_name')
                    ->where('edu_assignment_comments.class_assignments_id',$value->id)
                    ->where('edu_assignment_comments.student_id',$user_id)
                    ->first();
            }
        } else {
            $data['assignments'] = [];
        }
        
        return view('student.classroom.class.assignments', $data);
    }

    public function submitAssignment(Request $request){

        $assignment_id = $request->assignment_id;
        $comment       = $request->comment;
        $mainFile      = $request->attachment;
        $submit_type   = $request->submit_type;
        $authId        = Auth::id();

        $teacher_assignment_info = EduClassAssignments_User::valid()->find($assignment_id);

        $teacher_due_dateTime = strtotime($teacher_assignment_info->due_date." ".$teacher_assignment_info->due_time);
        $student_submit_dateTime = strtotime(date('Y-m-d H:i:s'));

        $validator = Validator::make($request->all(), [
            'comment'    => 'required',
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            $isSubmitted = EduAssignmentSubmission_User::valid()->where('assignment_id', $assignment_id)->where('created_by', $authId)->first();

            $countSubmitted = EduAssignmentSubmission_User::valid()->where('assignment_id', $assignment_id)->where('created_by', $authId)->count();
            
            $student_batch_id = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first()->batch_id;
            if ($student_batch_id == $teacher_assignment_info->batch_id) {

                if ( empty($isSubmitted) && ($countSubmitted == 0) ) {
                    
                    if(isset($mainFile)){
                        // PERFORMANCE TABLE CHECK
                        $exist_performance_data = EduStudentPerformance_User::valid()
                            ->where('student_id', $user_id)
                            ->where('course_id', $course_id)
                            ->where('batch_id', $batch_id)
                            ->where('assign_batch_classes_id', $assign_batch_class_id)
                            ->first();
                        // END PERFORMANCE TABLE CHECK
                        $validPath = 'uploads/assignment/studentAttachment';
                        $uploadResponse = Helper::getUploadedAttachmentName($mainFile, $validPath);
        
                        if($uploadResponse['status'] != 0){
                            $assignment = EduAssignmentSubmission_User::create([
                                'assignment_id'        => $assignment_id,
                                'comment'              => $comment,
                                'submission_date'      => date('Y-m-d'),
                                'submission_time'      => date('H:i:s'),
                                'late_submit'          => $teacher_due_dateTime>$student_submit_dateTime ? 0 : 1,
                                'is_improve'           => !empty($exist_performance_data) ? 1 : 0
                            ]);
            
                            EduAssignmentSubmissionAttachment_User::create([
                                'assignment_submission_id'  => $assignment->id,
                                'file_name'                 => $uploadResponse['file_name'],
                                'file_original_name'        => $uploadResponse['file_original_name'],
                                'size'                      => $uploadResponse['file_size'],
                                'extention'                 => $uploadResponse['file_extention']
                            ]);

                            $output['messege'] = 'Assignment has been Submitted';
                            $output['msgType'] = 'success';
                            $output['status'] = '1';
                            $output['late_submit'] = $teacher_due_dateTime>$student_submit_dateTime ? 0 : 1;
            
                        }else{
                            $output['messege'] = $uploadResponse['errors'];
                            $output['msgType'] = 'danger';
                            $output['status'] = '0';
                        }
                        
                    }else{
                        EduAssignmentSubmission_User::create([
                            'assignment_id'        => $assignment_id,
                            'comment'              => $comment,
                            'submission_date'      => date('Y-m-d'),
                            'submission_time'      => date('H:i:s'),
                            'late_submit'          =>  $teacher_due_dateTime>$student_submit_dateTime ? 0 : 1,
                            'is_improve'           => !empty($exist_performance_data) ? 1 : 0
                        ]);
        
                        $output['messege'] = 'Assignment has been Submitted';
                        $output['msgType'] = 'success';
                        $output['status'] = '1';
                    }
                    
                } else {

                    $isSubmitted->update([
                        'comment'              => $comment,
                        'submission_date'      => date('Y-m-d'),
                        'submission_time'      => date('H:i:s'),
                        'late_submit'          => $teacher_due_dateTime>$student_submit_dateTime ? 0 : 1,
                    ]);

                    $output['messege'] = 'Assignment has been Updated';
                    $output['msgType'] = 'success';
                    $output['status'] = '1';
                }
            } else {
                $output['messege'] = 'Ops!! You dont assigned this batch!!';
                $output['msgType'] = 'danger';
                $output['status'] = '0';
            }
            DB::commit();
            return response($output);

        } else {
            $output['messege'] = 'Comment Field Required!!';
            $output['msgType'] = 'danger';
            $output['status'] = '0';
            return response($output);
        }
    }

    public function activities(Request $request)
    {
        $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
        $data['assign_batch_class_id'] = $assign_batch_class_id = $request->batch_class_id;
        $assign_batch_info = EduAssignBatchClasses_User::valid()->find($assign_batch_class_id);
        $authId = Auth::id();
        if (!empty($assign_batch_info)) {
            // For This Class Assignments
            $data['class_assignments'] = $class_assignments  = EduClassAssignments_User::valid()
                ->where('batch_id', $student_course_info->batch_id)
                ->where('course_id', $student_course_info->course_id)
                ->where('assign_batch_class_id', $assign_batch_class_id)
                ->get();
    
            foreach ($class_assignments as $key => $assignment) {
                $assignment->submit = EduAssignmentSubmission_User::valid()
                    ->where('assignment_id', $assignment->id)
                    ->where('created_by', $authId)
                    ->first();
            }
    
            // For This Class Video's Watch Time
            $data['class_videos'] = $class_videos = EduCourseClassMaterials_User::valid()
                ->where('course_id', $student_course_info->course_id)
                ->where('class_id', $assign_batch_info->class_id)
                ->get();
            foreach ($class_videos as $key => $video) {
                $videoWatchInfo = EduStudentVideoWatchInfo_User::valid()
                    ->where('batch_id', $student_course_info->batch_id)
                    ->where('course_id', $student_course_info->course_id)
                    ->where('assign_batch_class_id', $assign_batch_class_id)
                    ->where('material_id', $video->id)
                    ->first();
                if (!empty($videoWatchInfo)) {
                    $video->watch_time = $videoWatchInfo->watch_time;
                } else {
                    $video->watch_time = 0;
                }
            }
            // For Class Attedence
            $data['attendence_info'] = EduStudentAttendence_User::valid()
                    ->where('batch_id', $student_course_info->batch_id)
                    ->where('course_id', $student_course_info->course_id)
                    ->where('class_id', $assign_batch_class_id) //class_id = assign_batch_class_id
                    ->where('student_id', $authId)
                    ->first();
            
            // STUDENT PERFORMANCE PROGRESS TABLE OF THIS CLASS
            $progress_config = EduStudentProgress_User::valid()->where('type', 1)->first();
            $studentPerforms = EduStudentPerformance_View::where('batch_id', $student_course_info->batch_id)
                ->where('assign_batch_class_id', $assign_batch_class_id)
                ->where('student_id', $authId)
                ->first();
            if (!empty($studentPerforms)) {
                $practice_time    = (($studentPerforms->practice_time != null ? $studentPerforms->practice_time : 0) * $progress_config->practice_time) / 100;
                $video_watch_time = (($studentPerforms->video_watch_time != null ? $studentPerforms->video_watch_time : 0) * $progress_config->video_watch_time) / 100;
                $attendence       = (($studentPerforms->attendence != null ? $studentPerforms->attendence : 0) * $progress_config->attendence) / 100;
                $class_mark       = (($studentPerforms->class_mark != null ? $studentPerforms->class_mark : 0) * $progress_config->class_mark) / 100;
                $assignment       = (($studentPerforms->assignment != null ? $studentPerforms->assignment : 0) * $progress_config->assignment) / 100;
                $quiz             = (($studentPerforms->quiz != null ? $studentPerforms->quiz : 0) * $progress_config->quiz) / 100;
                $data['this_class_final_progress'] = round(($practice_time + $video_watch_time + $attendence + $class_mark + $assignment + $quiz), 2);
            } else {
                $data['this_class_final_progress'] = 0;
            }
            // STUDENT PERFORMANCE PROGRESS TABLE OF THIS CLASS
        } else {
            $data['class_assignments'] = [];
            $data['class_videos'] = [];
            $data['attendence_info'] = [];
            $data['this_class_final_progress'] = 0;
        }
        
        return view('student.classroom.class.activities', $data);
    }

    public function quiz(Request $request)
    {
        $data['assign_batch_class_id'] = $assign_batch_class_id = $request->batch_class_id;
        $assignBatchClassInfo = EduAssignBatchClasses_User::valid()->find($assign_batch_class_id);
        $authId = Auth::id();
        if (!empty($assignBatchClassInfo)) {
            $data['examConfig'] = $examConfig = EduExamConfig_User::valid()
                ->where('batch_id', $assignBatchClassInfo->batch_id)
                ->where('assign_batch_class_id', $assignBatchClassInfo->id)
                ->first();
            $my_final_progress = self::getStudentFinalProgress($authId);
            if ($my_final_progress <= 60) {
                $data['classCompleteStatus'] = false;
            } else {
                $data['classCompleteStatus'] = $assignBatchClassInfo->complete_status == 1 ? true : false;
            }
            
            if (!empty($examConfig)) {
                $examAlreadyGiven = EduStudentExam_User::valid()
                    ->where('exam_config_id', $examConfig->id)
                    ->where('batch_id', $assignBatchClassInfo->batch_id)
                    ->where('assign_batch_class_id', $assignBatchClassInfo->id)
                    ->where('course_id', $assignBatchClassInfo->course_id)
                    ->where('course_class_id', $assignBatchClassInfo->class_id)
                    ->where('student_id', $authId)
                    ->first();

                if (!empty($examAlreadyGiven)) { //Exam Already Given
                    $data['examQuestions'] = $examQuestions = EduStudentExamQuestion_User::join('edu_archive_questions', 'edu_archive_questions.id', '=', 'edu_student_exam_questions.question_id')
                        ->select('edu_student_exam_questions.*', 'edu_archive_questions.question', 'edu_archive_questions.answer_type')
                        ->whereIn('edu_student_exam_questions.question_id', json_decode($examConfig->questions))
                        ->where('edu_student_exam_questions.student_exam_id', $examAlreadyGiven->id)
                        ->get();
                    foreach ($examQuestions as $key => $question) {
                        $question->answerSet = EduAnswer_User::valid()->where('question_id', $question->question_id)->get();
                    }
                    
                    return view('student.classroom.class.examResult', $data);
                } else {
                    return view('student.classroom.class.quiz', $data);
                }
            } else {
                return view('student.classroom.class.quiz', $data);
            }  
        } else {
            $data['classCompleteStatus'] = 0;
            $data['examConfig'] = [];
            return view('student.classroom.class.quiz', $data);
        }
    }
    /// end class menu method

    public function overview()
    {
        $authId = Auth::id();
        $today_date = date('Y-m-d');
        $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
        $student_progress = EduStudentProgress_User::valid()->where('type',1)->first();

        if(!empty($student_course_info)){

            // progress batch wise
            $std_batch_id = $student_course_info->batch_id;
            $std_course_id = $student_course_info->course_id;
        
            // Practice Ratio on Daily/Weekly/Monthly
            $SeveDaysAgo = date('Y-m-d', strtotime('-7 days'));
            $ThirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));

            // Last one Days Calculation
            $last_day = date('Y-m-d',strtotime("-1 days")); 

            // Start Attendence
                $data['all_attendence'] = $all_attendence =  EduStudentAttendence_User::valid()
                    ->where('batch_id', $student_course_info->batch_id)
                    ->where('course_id', $student_course_info->course_id)
                    ->where('student_id', $authId)
                    ->get();

                foreach ($all_attendence as $key => $attendence) {
                    $attendence->course_class_id = EduAssignBatchClasses_User::find($attendence->class_id)->class_id;
                }
            // End Attendence

            //Done/Not Done Assignment List with Mark.
                $data['total_assignment'] = $total_assignment  = EduClassAssignments_User::join('edu_assign_batch_students', 'edu_assign_batch_students.batch_id','=','edu_class_assignments.batch_id')
                    ->select('edu_class_assignments.*')
                    ->where('edu_class_assignments.valid',1)
                    ->where('edu_class_assignments.course_id',$student_course_info->course_id)
                    ->where('edu_assign_batch_students.student_id',$authId)
                    ->get();

                foreach ($total_assignment as $key => $assignment) {
                    $assignment->submit = EduAssignmentSubmission_User::valid()
                        ->where('assignment_id', $assignment->id)
                        ->where('created_by', $authId)
                        ->first();
                }
            // end	Done/Not Done Assignment List with Mark.

            // start quiz
                $data['total_exams'] = $total_exams = EduExamConfig_User::join('edu_assign_batch_classes','edu_assign_batch_classes.id','=','edu_exam_configs.assign_batch_class_id')
                    ->select('edu_exam_configs.*','edu_assign_batch_classes.class_id','edu_assign_batch_classes.course_id')
                    ->where('edu_exam_configs.batch_id', $std_batch_id)
                    ->where('edu_exam_configs.valid', 1)
                    ->where('edu_assign_batch_classes.valid', 1)
                    ->get();

                foreach ($total_exams as $key => $exam) {
                    $exam->submit = EduStudentExam_User::valid()
                        ->where('exam_config_id', $exam->id)
                        ->where('batch_id', $exam->batch_id)
                        ->where('assign_batch_class_id', $exam->assign_batch_class_id)
                        ->where('course_id', $exam->course_id)
                        ->where('course_class_id', $exam->class_id)
                        ->first();
                }
            // end start quiz
            
            // START Practice Ratio on Daily/Weekly/Monthly
                $one_total_time_avg = EduStudentPracticeTime_User::valid()
                    ->where('batch_id', $student_course_info->batch_id)
                    ->where('course_id', $student_course_info->course_id)
                    ->where('student_id', $authId)
                    ->whereDate('date', '=', $last_day)
                    ->first();
                
                if (!empty($one_total_time_avg)) {
                    $data['last_one_practice'] = round((($one_total_time_avg->total_time*100)/14400), 2);
                } else {
                    $data['last_one_practice'] = 0;
                }

                $seven_total_time = EduStudentPracticeTime_User::valid()
                    ->where('batch_id', $student_course_info->batch_id)
                    ->where('course_id', $student_course_info->course_id)
                    ->where('student_id', $authId)
                    ->whereDate('date', '>=', $SeveDaysAgo)
                    ->whereDate('date', '!=', $today_date)
                    ->sum('total_time');
                
                // echo "<pre>";
                // print_r($seven_total_time_avg); exit();

                if ($seven_total_time > 0) {
                    $data['last_seven_practice'] = round(( ($seven_total_time*100) / (14400*7) ), 2);
                } else {
                    $data['last_seven_practice'] = 0;
                }

                // Last Thirty Days Calculation
                $thirty_total_time = EduStudentPracticeTime_User::valid()
                    ->where('batch_id', $student_course_info->batch_id)
                    ->where('course_id', $student_course_info->course_id)
                    ->where('student_id', $authId)
                    ->whereDate('date', '>=', $ThirtyDaysAgo)
                    ->whereDate('date', '!=', $today_date)
                    ->sum('total_time');

                if ($thirty_total_time > 0) {
                    $data['last_thirty_practice'] = round(( ($thirty_total_time*100) / (14400*30) ), 2);
                } else {
                    $data['last_thirty_practice'] = 0;
                }
            // end Practice Ratio on Daily/Weekly/Monthly

            //watch video on daily/weekly/montly
                $today_materials = EduStudentVideoWatchInfo_User::valid()
                    ->where('batch_id', $student_course_info->batch_id)
                    ->where('course_id', $student_course_info->course_id)
                    ->where('student_id', $authId)
                    ->whereDate('date', '=', $last_day)
                    ->get();

                if(count($today_materials) > 0){
                    $today_material_ids = $today_materials->pluck('material_id')->unique('material_id')->toArray();
                    $today_total_watch_time =  $today_materials->sum('watch_time');
                    $today_total_video_time = EduCourseClassMaterials_User::valid()->whereIn('id',$today_material_ids)->sum('video_duration');
                    $data['today_avg_watch_time'] = round((($today_total_watch_time*100)/$today_total_video_time), 2);
                }else{
                    $data['today_avg_watch_time'] = 0;
                }

                //last seven days
                $seven_days_materials = EduStudentVideoWatchInfo_User::valid()
                    ->where('batch_id', $student_course_info->batch_id)
                    ->where('course_id', $student_course_info->course_id)
                    ->where('student_id', $authId)
                    ->whereDate('date', '>=', $SeveDaysAgo)
                    ->whereDate('date', '!=', $today_date)
                    ->get();

                if(count($seven_days_materials) > 0){
                    $seven_days_material_ids = $seven_days_materials->pluck('material_id')->unique('material_id')->toArray();
                    $seven_days_total_watch_time = $seven_days_materials->sum('watch_time');
                    $seven_days_total_video_time = EduCourseClassMaterials_User::valid()->whereIn('id',$seven_days_material_ids)->sum('video_duration');
                    $data['sevenDays_avg_watch_time'] = round((($seven_days_total_watch_time*100)/$seven_days_total_video_time), 2);
                }else{
                    $data['sevenDays_avg_watch_time'] = 0;
                }


                // last thirty days
                $thirty_days_materials = EduStudentVideoWatchInfo_User::valid()
                    ->where('batch_id', $student_course_info->batch_id)
                    ->where('course_id', $student_course_info->course_id)
                    ->where('student_id', $authId)
                    ->whereDate('date', '>=', $ThirtyDaysAgo)
                    ->whereDate('date', '!=', $today_date)
                    ->get();

                if(count($thirty_days_materials) > 0){
                    $thirty_days_material_ids = $thirty_days_materials->pluck('material_id')->unique('material_id')->toArray();
                    $thirty_days_total_watch_time = $thirty_days_materials->sum('watch_time');
                    $thirty_days_total_video_time = EduCourseClassMaterials_User::valid()->whereIn('id',$thirty_days_material_ids)->sum('video_duration');
                    $data['thirtyDays_avg_watch_time'] = round((($thirty_days_total_watch_time*100)/$thirty_days_total_video_time), 2);
                }else{
                    $data['thirtyDays_avg_watch_time'] = 0;
                }

            // End Watch time

            //Auto Notification
            $data['notifications'] = EduStudentNotification_User::valid()->latest()->limit(10)->get();

            $all_assign_class_ids = EduAssignBatchClasses_User::valid()
                ->where('batch_id', $std_batch_id)
                ->where('course_id', $std_course_id)
                ->where('complete_status',1)
                ->pluck('id')->toArray();

            $data['all_assign_classes'] = $all_assign_classes = EduAssignBatchClasses_User::valid()
                ->where('batch_id', $std_batch_id)
                ->where('course_id', $std_course_id)
                ->where('complete_status',1)
                ->get();

            foreach($all_assign_classes as $key => $assign_class){

                $assign_class->std_class_name = Helper::className($assign_class->class_id);

                $studentClassWisePerforms = EduStudentPerformance_View::where('batch_id', $std_batch_id)
                    ->where('course_id', $std_course_id)
                    ->where('assign_batch_class_id', $assign_class->id)
                    ->where('student_id', $authId)
                    ->first();

                if(!empty($studentClassWisePerforms)){
                    $assign_class->std_class_video        = $studentClassWisePerforms->video_watch_time != null ? $studentClassWisePerforms->video_watch_time : 0;
                    $assign_class->std_class_attend       = $studentClassWisePerforms->attendence != null ? $studentClassWisePerforms->attendence : 0;
                    $assign_class->std_class_mark         = $studentClassWisePerforms->class_mark != null ? $studentClassWisePerforms->class_mark : 0;
                    $assign_class->std_class_assignment   = $studentClassWisePerforms->assignment != null ? $studentClassWisePerforms->assignment : 0;
                    $assign_class->std_class_exam         = $studentClassWisePerforms->quiz != null ? $studentClassWisePerforms->quiz : 0;
                }else{
                    $assign_class->std_class_video        = 0;
                    $assign_class->std_class_attend       = 0;
                    $assign_class->std_class_mark         = 0;
                    $assign_class->std_class_assignment   = 0;
                    $assign_class->std_class_exam         = 0;
                }

            }
            $std_course_progress = self::getStudentFinalProgress($authId);
            $data['std_course_progress'] = round($std_course_progress, 2);

        } else {
            $data['all_attendence'] = [];
            $data['total_assignment'] = [];
            $data['total_exams'] = [];
            $data['last_one_practice'] = 0;
            $data['last_seven_practice'] = 0;
            $data['last_thirty_practice'] = 0;
            $data['today_avg_watch_time'] = 0;
            $data['sevenDays_avg_watch_time'] = 0;
            $data['thirtyDays_avg_watch_time'] = 0;
            $data['notifications'] = [];
            $data['all_assign_classes'] = [];
            $data['std_course_progress'] = 0;
        }

        return view('student.classroom.overview', $data);
    }
    // MY BATCH RANKING
    public function studentRanking()
    {
        $authId = Auth::id();
        $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
        if (!empty($student_course_info)) {
            $data['student_ranking_list'] = EduStudentProgressWithConfig_View::join('users', 'users.id', '=', 'view_student_performance_with_progress.student_id')
                ->where('batch_id', $student_course_info->batch_id)
                ->where('course_id', $student_course_info->course_id)
                ->select('view_student_performance_with_progress.student_id', 'users.name', 'users.image', DB::raw('(gained_attendence + gained_class_mark + gained_assignment + gained_video_watch_time + gained_practice_time + gained_quiz) AS final_mark'))
                ->orderby('final_mark', 'desc')
                ->get();
        } else {
            $data['student_ranking_list'] = [];
        }
        return view('student.classroom.studentRanking.listData', $data);
    }

    public function stdRankRunningProgress(Request $request)
    {
        $student_id = $request->student_id;
        $student_course_info = EduAssignBatchStudent_Global::valid()->where('student_id', $student_id)->where('is_running', 1)->where('active_status', 1)->first();
        $std_running_class_info = EduAssignBatchClasses_User::valid()->where('batch_id', $student_course_info->batch_id)->where('course_id', $student_course_info->course_id)->where('complete_status', 2)->first();
        
        // CLASS ATTENDANCE & PERFORMANCE MARK
        $class_attendence = EduStudentAttendence_User::valid()
            ->where('batch_id', $student_course_info->batch_id)
            ->where('class_id', $student_course_info->id) //class_id = assign_batch_class_id
            ->where('student_id', $student_id)
            ->where('is_attend', 1)
            ->first();

        if(!empty($class_attendence)){
            $gained_class_mark = $class_attendence->mark;
            $data['running_class_attendance_mark'] = 100;
            $data['running_class_perform_mark'] = round($gained_class_mark, 2);
        }else{
            $data['running_class_attendance_mark'] = 0;
            $data['running_class_perform_mark'] = 0;
        }
        
        // CLASS ASSIGNMENT
        $teach_given_assignment_ids = EduClassAssignments_User::valid()
            ->where('assign_batch_class_id', $student_course_info->id)
            ->where('batch_id', $student_course_info->batch_id)
            ->get()->pluck('id');

        if(count($teach_given_assignment_ids) > 0) {
            $student_assignment_info = EduAssignmentSubmission_User::valid()
                ->whereIn('assignment_id', $teach_given_assignment_ids)
                ->where('created_by', $student_id)
                ->first();
            if(!empty($student_assignment_info)) {
                $gained_assignemnt_mark = $student_assignment_info->mark;
                if($gained_assignemnt_mark > 0 ) {
                    $data['running_assignment_mark'] = round($gained_assignemnt_mark, 2);
                } else {
                    $data['running_assignment_mark'] = 0;
                }
            } else {
                $data['running_assignment_mark'] = 0;
            }
        } else {
            $data['running_assignment_mark'] = 100;
        }

        // CLASS EXAM
        $running_exam_info = EduStudentExam_User::join('edu_exam_configs', 'edu_exam_configs.id', '=', 'edu_student_exams.exam_config_id')
            ->select('edu_student_exams.*')
            ->where('edu_student_exams.batch_id', $std_running_class_info->batch_id)
            ->where('edu_student_exams.assign_batch_class_id', $std_running_class_info->id)
            ->where('edu_student_exams.student_id', $student_id)
            ->first();
        if(!empty($running_exam_info)) {
            $base_exam_mark = $running_exam_info->total_questions * $running_exam_info->per_question_mark;
            $gained_exam_mark = $running_exam_info->total_correct_answer * $running_exam_info->per_question_mark;
            $data['running_exam_result'] = round(($gained_exam_mark * 100) / $base_exam_mark);
        } else {
            $data['running_exam_result'] = 0;
        }
        
        // CLASS VIDEO WATCH
        $base_video_duration = EduCourseClassMaterials_User::valid()
            ->where('course_id', $std_running_class_info->course_id)
            ->where('class_id', $std_running_class_info->class_id)
            ->sum('video_duration');
        $gained_watch_time = EduStudentVideoWatchInfo_User::valid()
            ->where('batch_id', $std_running_class_info->batch_id)
            ->where('assign_batch_class_id', $std_running_class_info->id)
            ->where('student_id', $student_id)
            ->sum('watch_time');

        if($gained_watch_time > 0){
            $watch_time_percentage = ($gained_watch_time * 100) / $base_video_duration;
            $data['running_watch_time'] = round($watch_time_percentage, 2);
        } else {
            $data['running_watch_time'] = 0;
        }

        // CLASS PRACTICE TIME
        $today_date = date('Y-m-d');
        $start_class_date = strtotime($std_running_class_info->start_date);
        $end_class_date = strtotime($today_date);
        $total_practice_days = ceil(abs($end_class_date - $start_class_date) / 86400);
        $base_practice_time = $total_practice_days * 14400;

        $gained_practice_time = EduStudentPracticeTime_User::valid()
            ->where('created_by', $student_id)
            ->where('batch_id', $std_running_class_info->batch_id)
            ->where('course_id', $std_running_class_info->course_id)
            ->whereDate('date', '>=', $std_running_class_info->start_date)
            ->whereDate('date', '<', $today_date)
            ->sum('total_time');

        if($gained_practice_time >= $base_practice_time){
            $gained_practice_time = $base_practice_time;
        }

        if($gained_practice_time > 0){
            $practice_time_percentage = ($gained_practice_time * 100) / $base_practice_time;
            $data['running_practice_time'] = round($practice_time_percentage, 2);
        }else{
            $data['running_practice_time'] = 0;
        }
        return view('student.classroom.studentRanking.activityDetails', $data);
    }
    
    public function todayGoal(Request $request)
    {
        $authId = Auth::id();
        $today_date = date('Y-m-d');
        $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();

        if (!empty($student_course_info)) {
            $data['hasAnyCourse'] = true;
            // today practice time
            $today_avg_practice_time = EduStudentPracticeTime_User::valid()
                    ->where('batch_id', $student_course_info->batch_id)
                    ->where('course_id', $student_course_info->course_id)
                    ->where('student_id', $authId)
                    ->whereDate('date', '=', $today_date)
                    ->first();
                
            if (!empty($today_avg_practice_time)) {
                $final_practice = round((($today_avg_practice_time->total_time*100)/14400), 2);
                $data['today_practice'] = $final_practice >= 100 ? 100 : $final_practice;
            } else {
                $data['today_practice'] = 0;
            }
            // end today practice time
            // COURSE GOAL
            $data['course_info'] = EduCourses_User::valid()->find($student_course_info->course_id);
    
            // upcomming class
            $upcomming_class =  EduAssignBatchClasses_User::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
                    ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name')
                    ->where('edu_assign_batch_classes.valid', 1)
                    ->where('edu_assign_batch_classes.complete_status', 2)  // 2 = running/upcomming
                    ->where('edu_assign_batch_classes.batch_id', $student_course_info->batch_id)
                    ->where('edu_assign_batch_classes.course_id', $student_course_info->course_id)
                    ->first();
            
            if(!empty($upcomming_class)){
                $data['upcomming_class'] = $upcomming_class;
                $data['running_assignments']  = EduClassAssignments_User::valid()->where('assign_batch_class_id',$upcomming_class->id)->get();
                $data['running_quiz']  = EduExamConfig_User::valid()
                    ->where('assign_batch_class_id', $upcomming_class->id)
                    ->where('batch_id', $upcomming_class->batch_id)
                    ->first();
                $data['running_videos'] = EduCourseClassMaterials_User::valid()
                    ->where('course_id',$upcomming_class->course_id)
                    ->where('class_id',$upcomming_class->class_id)
                    ->get();
                // Batch Weekly Schedules
                $total_days = DB::table('edu_schedule_days')->get();
                foreach($total_days as $day) {
                    $schedule = EduAssignBatchSchedule_User::valid()
                        ->where('batch_id', $student_course_info->batch_id)
                        ->where('day_dt', $day->dt)
                        ->first();
    
                    if(!empty($schedule)) {
                        $day->schedule = $schedule;
                    }
                }
                $data['total_days'] = $total_days;
            }else{
                $data['upcomming_class'] = '';
                $data['running_assignments'] = [];
                $data['running_quiz']  = '';
                $data['running_videos'] = [];
                $data['total_days'] = [];
            }
        } else {
            $data['hasAnyCourse'] = false;
        }
        
        return view('student.classroom.todayGoal', $data);
    }

    public function classResourceModal(Request $request)
    {
        $assign_batch_class_id = $request->assign_batch_class_id;
        $data['running_class_info'] =  EduAssignBatchClasses_User::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
                    ->select('edu_course_assign_classes.class_resource', 'edu_course_assign_classes.class_name')
                    ->where('edu_assign_batch_classes.valid', 1)
                    ->where('edu_assign_batch_classes.complete_status', 2)  // 2 = running/upcomming
                    ->where('edu_assign_batch_classes.id', $assign_batch_class_id)
                    ->first();
        return view('student.classroom.classResource', $data);
    }

    public function improveScore(Request $request)
    {
        $authId = Auth::id();
        $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
        
        if (!empty($student_course_info)) {
            $my_final_progress = self::getStudentFinalProgress($authId);
            $data['hasAnyImprovements'] = $hasAnyImprovements = $my_final_progress <= 60 ? true : false;
            if ($hasAnyImprovements) {
                $all_assign_class_ids = EduAssignBatchClasses_User::valid()
                    ->where('batch_id', $student_course_info->batch_id)
                    ->where('course_id', $student_course_info->course_id)
                    ->where('complete_status', 1)
                    ->get()->pluck('id');
                // CLASS QUIZES
                // WHICH CLASS HAVE EXAM
                $had_exam_config_ids = EduExamConfig_User::valid()
                    ->whereIn('assign_batch_class_id', $all_assign_class_ids)
                    ->where('batch_id', $student_course_info->batch_id)
                    ->get()->pluck('id');
                // WHICH EXAM HAVE GIVEN
                $given_exam_config_ids = EduStudentExam_User::valid()
                    ->whereIn('exam_config_id', $had_exam_config_ids)
                    ->where('student_id', $authId)
                    ->get()->pluck('exam_config_id');

                $need_give_exam_ids = $had_exam_config_ids->diff($given_exam_config_ids);
                $data['need_improve_quiz_assign_class_ids'] = EduExamConfig_User::valid()
                    ->whereIn('id', $need_give_exam_ids)
                    ->where('batch_id', $student_course_info->batch_id)
                    ->get()->pluck('assign_batch_class_id');

                // CLASS ASSIGNMENTS
                // WHICH CLASS HAVE ASSIGNMENTS
                $had_assignment_ids = EduClassAssignments_User::valid()
                    ->whereIn('assign_batch_class_id', $all_assign_class_ids)
                    ->where('batch_id', $student_course_info->batch_id)
                    ->get()->pluck('id');
                // WHICH ASSIGNMENTS HAVE GIVEN
                $given_assignment_ids = EduAssignmentSubmission_User::valid()
                    ->whereIn('assignment_id', $had_assignment_ids)
                    ->where('created_by', $authId)
                    ->get()->pluck('assignment_id');

                $need_give_assignment_ids = $had_assignment_ids->diff($given_assignment_ids);
                $data['need_improve_assignment_assign_class_ids'] = EduClassAssignments_User::valid()
                    ->whereIn('id', $need_give_assignment_ids)
                    ->where('batch_id', $student_course_info->batch_id)
                    ->get()->pluck('assign_batch_class_id');

                // echo "<pre>";
                // print_r($data['need_improve_quiz_assign_class_ids']);
                // echo "<br>";
                // print_r($need_give_assignment_ids);
                // die();
            }
            

        } else {
            $data['hasAnyImprovements'] = false;
        }
        
        return view('student.classroom.improveScore.classList', $data);
    }

    public static function getStudentFinalProgress($student_id)
    {
        $student_course_info = EduAssignBatchStudent_Global::valid()->where('student_id', $student_id)->where('is_running', 1)->where('active_status', 1)->first();
        $student_progress = EduStudentProgress_User::valid()->where('type', 1)->first();
        $all_assign_class_ids = EduAssignBatchClasses_User::valid()
            ->where('batch_id', $student_course_info->batch_id)
            ->where('course_id', $student_course_info->course_id)
            ->where('complete_status', 1)
            ->pluck('id')->toArray();

        $studentPerforms = EduStudentPerformance_View::where('batch_id', $student_course_info->batch_id)
            ->where('course_id', $student_course_info->course_id)
            ->whereIn('assign_batch_class_id', $all_assign_class_ids)
            ->where('student_id', $student_id)
            ->get();
            
        if(count($studentPerforms) > 0){
            $config_practice_time    = (($studentPerforms->avg('practice_time') != null ? $studentPerforms->avg('practice_time') : 0) * $student_progress->practice_time)/100;
            $config_video_watch_time = (($studentPerforms->avg('video_watch_time') != null ? $studentPerforms->avg('video_watch_time') : 0) * $student_progress->video_watch_time)/100;
            $config_attendence       = (($studentPerforms->avg('attendence') != null ? $studentPerforms->avg('attendence') : 0) * $student_progress->attendence)/100;
            $config_class_mark       = (($studentPerforms->avg('class_mark') != null ? $studentPerforms->avg('class_mark') : 0) * $student_progress->class_mark)/100;
            $config_assignment       = (($studentPerforms->avg('assignment') != null ? $studentPerforms->avg('assignment') : 0) * $student_progress->assignment)/100;
            $config_quiz             = (($studentPerforms->avg('quiz') != null ? $studentPerforms->avg('quiz') : 0) * $student_progress->quiz)/100;
        } else {
            $config_practice_time    = 0;
            $config_video_watch_time = 0;
            $config_attendence       = 0;
            $config_class_mark       = 0;
            $config_assignment       = 0;
            $config_quiz             = 0;
        }
        $std_course_progress= $config_practice_time+$config_video_watch_time+$config_attendence+$config_class_mark+$config_assignment+$config_quiz;
        $std_final_progress = round($std_course_progress,2);
        return $std_final_progress;
    }
}
