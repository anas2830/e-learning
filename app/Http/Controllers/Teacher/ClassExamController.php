<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
use Helper;
use Validator;
use DB;
use Auth;
use App\Models\EduStudent_Provider;
use App\Models\EduAssignBatch_Teacher;
use App\Models\EduAssignBatchClasses_Teacher;
use App\Models\EduCourses_Teacher;
use App\Models\EduAssignBatchStudent_Teacher;
use App\Models\EduStudentAttendence_Teacher;
use App\Models\EduCourseAssignClass_Teacher;
use App\Models\EduArchiveQuestion_Teacher;
use App\Models\EduExamConfig_Teacher;
use App\Models\EduStudentExamQuestion_Teacher;
use App\Models\EduStudentExam_Teacher;
use App\Models\EduAnswer_Teacher;


class ClassExamController extends Controller
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
        return view('teacher.classExam.assignBatchListData', $data);
    }

    public function batchClassList(Request $request, $batch_id)
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
            $class->isAttendanceDone = EduStudentAttendence_Teacher::valid()->where('batch_id', $batch_id)->where('class_id', $class->class_id)->count();
        }
        return view('teacher.classExam.classListData', $data);
    }

    public function examConfig(Request $request)
    {
        $data['assign_class_id'] = $assign_class_id = $request->batch_class_id;
        $data['assignBatchClassInfo'] = $assignBatchClassInfo = EduAssignBatchClasses_Teacher::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
            ->join('edu_assign_batches', 'edu_assign_batches.id', '=', 'edu_assign_batch_classes.batch_id')
            ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name', 'edu_assign_batches.batch_no')
            ->where('edu_course_assign_classes.valid', 1)
            ->where('edu_assign_batch_classes.valid', 1)
            ->where('edu_assign_batch_classes.id', $assign_class_id)
            ->first();

        $data['course_name'] = EduCourses_Teacher::valid()->find($assignBatchClassInfo->course_id)->course_name;

        $data['questions'] = EduArchiveQuestion_Teacher::valid()
            ->where('course_id',$assignBatchClassInfo->course_id)
            ->where('class_id',$assignBatchClassInfo->class_id)
            ->get();
            
        $data['examConfig'] = EduExamConfig_Teacher::valid()
            ->where('batch_id', $assignBatchClassInfo->batch_id)
            ->where('assign_batch_class_id', $assignBatchClassInfo->id)
            ->first();
        return view('teacher.classExam.examConfig', $data);
    }

    public function saveExamConfig(Request $request)
    {
        $assign_batch_class_id = $request->assign_batch_class_id;
        $data['assign_batch_info'] = $assign_batch_info = EduAssignBatchClasses_Teacher::valid()->find($assign_batch_class_id);

        $validator = Validator::make($request->all(), [
            'exam_overview'     => 'required',
            'exam_duration'     => 'required',
            'per_question_mark' => 'required'
        ]);

        if ($validator->passes()) {
            $question = $request->question_id;
            if (isset($request->question_id)) {
                $total_question = count($question);
    
                if($total_question == 0){
                    $output['messege'] =  "Please at least select one question";
                    $output['msgType'] = 'danger';
                } else {
    
                    $existExamConfig = EduExamConfig_Teacher::valid()
                        ->where('batch_id', $assign_batch_info->batch_id)
                        ->where('assign_batch_class_id', $assign_batch_info->id)
                        ->first();
                    if (!empty($existExamConfig)) {
                        $check_student_given_exam = EduStudentExamQuestion_Teacher::valid()->where('student_exam_id',$existExamConfig->id)->first();
                        if(empty($check_student_given_exam)){
                            $existExamConfig->update([
                                'exam_overview'     => $request->exam_overview,
                                'exam_duration'     => $request->exam_duration,
                                'questions'         => json_encode($question),
                                'total_question'    => $total_question,
                                'per_question_mark' => $request->per_question_mark
                            ]);
    
                            $output['messege'] =  "Exam configuration successfull";
                            $output['msgType'] = 'success';
    
                        }else{
                            $output['messege'] =  "Student Already given this exam";
                            $output['msgType'] = 'danger';
                        }
                    } else {
                        EduExamConfig_Teacher::create([
                            'batch_id'              => $assign_batch_info->batch_id,
                            'assign_batch_class_id' => $assign_batch_class_id,
                            'exam_overview'         => $request->exam_overview,
                            'exam_duration'         => $request->exam_duration,
                            'questions'             => json_encode($question),
                            'total_question'        => $total_question,
                            'per_question_mark'     => $request->per_question_mark
                        ]);
    
                        $output['messege'] =  "Exam configuration successfull";
                        $output['msgType'] = 'success';
                    }
                   
                }
            } else {
                $output['messege'] =  "Please at least select one question";
                $output['msgType'] = 'danger';
            }
            return redirect()->back()->with($output);
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    public function examResult(Request $request){
        $data['assign_class_id'] = $assign_class_id = $request->batch_class_id;

        if(isset($assign_class_id)){

            $data['assignBatchClassInfo'] = $assignBatchClassInfo = EduAssignBatchClasses_Teacher::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
                ->join('edu_assign_batches', 'edu_assign_batches.id', '=', 'edu_assign_batch_classes.batch_id')
                ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name', 'edu_assign_batches.batch_no')
                ->where('edu_course_assign_classes.valid', 1)
                ->where('edu_assign_batch_classes.valid', 1)
                ->where('edu_assign_batch_classes.id', $assign_class_id)
                ->first();
    
            $data['course_name'] = EduCourses_Teacher::valid()->find($assignBatchClassInfo->course_id)->course_name;
    
            $data['courseBatchStu'] = $courseBatchStu = EduAssignBatchStudent_Teacher::valid()
                ->where('batch_id',$assignBatchClassInfo->batch_id)
                ->where('course_id',$assignBatchClassInfo->course_id)
                ->where('active_status',1)
                ->get();
    
            foreach($courseBatchStu as $key => $value){
                $value->done_exam = EduStudentExam_Teacher::valid()
                    ->where('assign_batch_class_id',$assign_class_id)
                    ->where('student_id',$value->student_id)
                    ->first();
    
            }
    
            return view('teacher.classExam.studentList.listData', $data);
        }

    }

    public function examResultShow(Request $request){

        $data['assign_batch_class_id'] = $assign_batch_class_id = $request->batch_class_id;
        $student_id = $request->std_id;

        $data['examConfig'] = $examConfig = EduExamConfig_Teacher::valid()
            ->where('assign_batch_class_id', $assign_batch_class_id)
            ->first();

        if (!empty($examConfig)) {
            $examAlreadyGiven = EduStudentExam_Teacher::valid()
                ->where('exam_config_id', $examConfig->id)
                ->where('assign_batch_class_id', $assign_batch_class_id)
                ->where('student_id', $student_id)
                ->first();

            if (!empty($examAlreadyGiven)) { //Exam Already Given
                $data['examQuestions'] = $examQuestions = EduStudentExamQuestion_Teacher::join('edu_archive_questions', 'edu_archive_questions.id', '=', 'edu_student_exam_questions.question_id')
                    ->select('edu_student_exam_questions.*', 'edu_archive_questions.question', 'edu_archive_questions.answer_type')
                    ->whereIn('edu_student_exam_questions.question_id', json_decode($examConfig->questions))
                    ->where('edu_student_exam_questions.student_exam_id', $examAlreadyGiven->id)
                    ->get();
                foreach ($examQuestions as $key => $question) {
                    $question->answerSet = EduAnswer_Teacher::valid()->where('question_id', $question->question_id)->get();
                }

                // echo "<pre>";
                // // print_r($data['all_questions']->toArray());
                // print_r($examQuestions);
                // die();
                
                return view('teacher.classExam.studentList.examResult', $data);
            } 
        }
    }
}
