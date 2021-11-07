<?php

namespace App\Http\Controllers\Student;

use DB;
use Auth;
use Helper;
use DateTime;
use Validator;
use DateInterval;
use Illuminate\Http\Request;
use App\Models\EduAnswer_User;
use App\Models\EduExamConfig_User;
use App\Models\EduStudentExam_User;
use App\Http\Controllers\Controller;
use App\Models\EduArchiveQuestion_User;
use App\Models\EduCourseAssignClass_User;
use App\Models\EduAssignBatchClasses_User;
use App\Models\EduStudentPerformance_User;
use App\Models\EduStudentExamQuestion_User;
use App\Http\Controllers\Student\ClassroomController;

class ClassExamController extends Controller
{
    public function classExam(Request $request, $batch_class_id)
    {
        $data['assign_batch_class_id'] = $batch_class_id;
        $assignBatchClassInfo = EduAssignBatchClasses_User::valid()->find($batch_class_id);
        $data['courseClassInfo'] = EduCourseAssignClass_User::valid()->find($assignBatchClassInfo->class_id);
        $authId = Auth::id();
        $data['examConfig'] = $examConfig = EduExamConfig_User::valid()
            ->where('batch_id', $assignBatchClassInfo->batch_id)
            ->where('assign_batch_class_id', $assignBatchClassInfo->id)
            ->first();

        $my_final_progress = ClassroomController::getStudentFinalProgress($authId);
        if ($my_final_progress <= 60) {
            $data['classCompleteStatus'] = $classCompleteStatus = false;
        } else {
            $data['classCompleteStatus'] = $classCompleteStatus = $assignBatchClassInfo->complete_status == 1 ? true : false;
        }

        if (!empty($examConfig)) {
            if (!$classCompleteStatus) {
                $examAlreadyGiven = EduStudentExam_User::valid()
                    ->where('exam_config_id', $examConfig->id)
                    ->where('batch_id', $assignBatchClassInfo->batch_id)
                    ->where('assign_batch_class_id', $assignBatchClassInfo->id)
                    ->where('course_id', $assignBatchClassInfo->course_id)
                    ->where('course_class_id', $assignBatchClassInfo->class_id)
                    ->where('student_id', $authId)
                    ->first();
    
                if (empty($examAlreadyGiven)) { //Exam Not Given
                    $data['examQuestions'] = $examQuestions = EduArchiveQuestion_User::valid()->whereIn('id', json_decode($examConfig->questions))->get();
                    foreach ($examQuestions as $key => $question) {
                        $question->answers = EduAnswer_User::valid()->where('question_id', $question->id)->get();
                    }
            
                    $exam_duration = ($examConfig->exam_duration>0) ? $examConfig->exam_duration : 0;
                    $data['total_exam_dur'] = $exam_duration * 60;
                    return view('student.classroom.class.classExam', $data);
    
                } else { //Exam Already Given
                    $output['messege'] = ' Exam is Already Given!!!';
                    $output['back_route'] = 'home';
                    return view('examError', $output);
                }
            } else {
                $output['messege'] = ' Class already Completed!!!';
                $output['back_route'] = 'home';
                return view('examError', $output);
            }
            
        } else {
            $output['messege'] = ' Exam is Not Configured!!!';
            $output['back_route'] = 'home';
            return view('examError', $output);
        }
    }

    public function classExamSubmit(Request $request)
    {
        $exam_config_id = $request->exam_config_id;
        
        if(isset($exam_config_id)) {
            $user_id = Auth::id();
            $config_info = EduExamConfig_User::valid()->find($exam_config_id);
            $assignBatchClassInfo = EduAssignBatchClasses_User::valid()->find($config_info->assign_batch_class_id);
            $examArchQuestions = json_decode($config_info->questions);
            $given_qns_id = [];

            $batch_id  = $assignBatchClassInfo->batch_id;
            $assign_batch_class_id = $config_info->assign_batch_class_id;
            $course_id = $assignBatchClassInfo->course_id;
            $class_id  = $assignBatchClassInfo->class_id;

            $examAlreadyGiven = EduStudentExam_User::valid()
                ->where('exam_config_id', $exam_config_id)
                ->where('batch_id', $batch_id)
                ->where('assign_batch_class_id', $assign_batch_class_id)
                ->where('course_id', $course_id)
                ->where('course_class_id', $class_id)
                ->where('student_id', $user_id)
                ->first();

            if (empty($examAlreadyGiven)) { //Exam Not Given
                DB::beginTransaction();
                $config_duration = gmdate("H:i:s", $config_info->exam_duration * 60);
                $taken_duration = strtotime($request->current_time);
                $spend_exam_time =  strtotime($config_duration) - $taken_duration;
            
                // answer data
                $given_answer_array = $request->answer;
                $total_answered = count($given_answer_array);
                
                $studentExam = EduStudentExam_User::create([
                    "exam_config_id"        => $exam_config_id,
                    "batch_id"              => $batch_id,
                    "assign_batch_class_id" => $assign_batch_class_id,
                    "course_id"             => $course_id,
                    "course_class_id"       => $class_id,
                    "student_id"            => $user_id,
                    "exam_duration"         => $config_info->exam_duration,
                    "total_questions"       => $config_info->total_question,
                    "per_question_mark"     => $config_info->per_question_mark,
                    "taken_duration"        => $spend_exam_time,
                    "total_answer"          => $total_answered,
                    "total_correct_answer"  => 0
                ]);

                $correct_answer = 0;
                foreach($given_answer_array as $q_id => $answer){
                    $given_qns_id[] = $q_id;
                    $question = EduArchiveQuestion_User::find($q_id);
                    if($question->answer_type==1) { //True/False
                        $answer_db = EduAnswer_User::valid()->where('question_id', $q_id)->first();
                        $answer_db = (!empty($answer_db)) ? [$answer_db->true_answer] : [];
                    } else { //Single/Multiple MCQ
                        $answer_db = EduAnswer_User::valid()->where('question_id', $q_id)->where('true_answer', 1)->get()->pluck('id')->all();
                    }
                    // Check the given ans correct or not
                    $corrected = (self::arrayEqualityCheck($answer, $answer_db))?1:0;

                    EduStudentExamQuestion_User::create([
                        "student_exam_id" => $studentExam->id,
                        "question_id"     => $q_id,
                        "answer"          => serialize($answer),
                        "answered"        => 1,
                        "corrected"       => $corrected
                    ]);

                    $correct_answer += $corrected;
                    EduStudentExam_User::find($studentExam->id)->update(["total_correct_answer"=> $correct_answer]);
                }

                $notAnswered = array_diff($examArchQuestions, $given_qns_id);

                if (count($notAnswered) > 0) {
                    foreach ($notAnswered as $key => $question_id) {
                        EduStudentExamQuestion_User::create([
                            "student_exam_id" => $studentExam->id,
                            "question_id"     => $question_id,
                            "answer"          => '',
                            "answered"        => 0,
                            "corrected"       => 0
                        ]);
                    }
                }

                // PERFORMANCE TABLE UPDATE
                $exist_performance_data = EduStudentPerformance_User::valid()
                    ->where('student_id', $user_id)
                    ->where('course_id', $course_id)
                    ->where('batch_id', $batch_id)
                    ->where('assign_batch_classes_id', $assign_batch_class_id)
                    ->first();

                if(!empty($exist_performance_data)) {
                    if ($exist_performance_data->quiz == 0) {
                        $base_exam_mark = $config_info->total_question * $config_info->per_question_mark;
                        $gained_exam_mark = $correct_answer * $config_info->per_question_mark;
    
                        if($gained_exam_mark > 0) {
                            $exam_percentage = ($gained_exam_mark * 90) / $base_exam_mark;
                            $final_exam_progress = round($exam_percentage, 2);
                        } else {
                            $final_exam_progress = 0;
                        }
                        $exist_performance_data->update([
                            'quiz' => $final_exam_progress
                        ]);
                    }
                }
                // END PERFORMANCE TABLE UPDATE
                
                DB::commit();

                $output['messege'] = 'Your Exam has taken Succesfully!';
                $output['msgType'] = 'success';
            } else {
                $output['messege'] = 'Exam is Already Given!!!';
                $output['msgType'] = 'danger';
            }
        }else{
            $output['messege'] = 'Exam is not Configured!!!';
            $output['msgType'] = 'danger';
        }
        return response($output);
    }

    public static function arrayEqualityCheck($arrayA, $arrayB) {
        sort($arrayA);
        sort($arrayB);
        return $arrayA==$arrayB;
    }
}
