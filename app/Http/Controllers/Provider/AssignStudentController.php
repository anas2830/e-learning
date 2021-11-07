<?php

namespace App\Http\Controllers\Provider;

use DB;
use Helper;

use Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EduPaymentSystem;
use App\Models\EduCourses_Provider;
use App\Models\EduStudent_Provider;
use App\Http\Controllers\Controller;
use App\Models\EduEventSms_Provider;
use App\Models\EduAssignBatch_Provider;
use App\Models\EduStudentExam_Provider;
use App\Models\EduClassAssignments_Provider;
use App\Models\EduStudentAttendence_Provider;
use App\Models\EduAssignBatchStudent_Provider;
use App\Models\EduStudentPerformance_Provider;
use App\Models\EduStudentPracticeTime_Provider;
use App\Models\EduAssignmentSubmission_Provider;
use App\Models\EduStudentPaymentHistory_Provider;
use App\Models\EduStudentVideoWatchInfo_Provider;

class AssignStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['batch_id'] = $batch_id = $request->batch_id;
        $data['batch_info'] = $batch_info =  EduAssignBatch_Provider::valid()->find($batch_id);
        $data['assign_students'] = EduAssignBatchStudent_Provider::join('users', 'users.id', '=', 'edu_assign_batch_students.student_id')
            ->select('edu_assign_batch_students.*', 'users.name','users.email','users.phone')
            ->where('edu_assign_batch_students.valid', 1)
            ->where('users.valid', 1)
            ->where('edu_assign_batch_students.batch_id', $batch_id)
            ->where('edu_assign_batch_students.course_id', $batch_info->course_id)
            ->orderBy('edu_assign_batch_students.id', 'desc')
            ->get();

        return view('provider.assignStudent.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['batch_id'] = $batch_id = $request->batch_id;
        $assign_batch_info = EduAssignBatch_Provider::valid()->find($batch_id);
        $data['course_id'] = $course_id = $assign_batch_info->course_id;

        $exits_course_student = EduAssignBatchStudent_Provider::valid()
            ->where('course_id',$course_id)
            ->where('batch_id',$batch_id)
            ->where('active_status', 1)
            ->pluck('student_id')
            ->toArray();

        $all_students = EduStudent_Provider::valid()->pluck('id')->toArray();
        $filter_students = array_diff($all_students,$exits_course_student);
        $data['sutdents'] =  EduStudent_Provider::valid()->whereIn('id',$filter_students)->get();
        return view('provider.assignStudent.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $students_arr = $request->students;
        $validator = Validator::make($request->all(), [
            'students'         => 'required',
        ]);
        
        if ($validator->passes()) {
            $batch_no = EduAssignBatch_Provider::valid()->find($request->batch_id)->batch_no;
            $course_name = EduCourses_Provider::valid()->find($request->course_id)->course_name;

            $filter_students_id = array_filter($students_arr);
            $event_message = EduEventSms_Provider::valid()->where('type', 2)->where('status',1)->first();
            $messageData = array();
            foreach($filter_students_id as $key => $student_id) 
            {
                if(!empty($event_message)){
                    
                    $message = $event_message->message;
                    $studentName = Helper::studentInfo($student_id)->name;
                    $studentPhone = Helper::studentInfo($student_id)->phone;
                    if(preg_match("~\@"."name"."\@~", $message)){
                        $message = str_replace("@name@", $studentName , $message);
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

                EduAssignBatchStudent_Provider::create([
                    'batch_id'   => $request->batch_id,
                    'course_id'  => $request->course_id,
                    'student_id' => $student_id,
                ]);
            }
            if(count($messageData) > 0){
                Helper::dynamicSms($messageData);
            }

            $output['messege'] = 'Students has been Assigned';
            $output['msgType'] = 'success';

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
    // public function destroy($id)
    // {
    //     EduAssignBatchStudent_Provider::valid()->find($id)->delete();
    // }

    public function destroy($id)
    {
        $student_batch_info = EduAssignBatchStudent_Provider::valid()
        ->where('active_status',1)
        ->find($id);

        $course_id = $student_batch_info->course_id;
        $batch_id = $student_batch_info->batch_id;
        $student_id = $student_batch_info->student_id;

        //delete all activity this student
        DB::beginTransaction();

        EduStudentPaymentHistory_Provider::valid()->where('assign_batch_std_id',$id)->delete();

        $student_practice = EduStudentPracticeTime_Provider::valid()
            ->where('course_id',$course_id)
            ->where('batch_id',$batch_id)
            ->where('student_id',$student_id)
            ->get();

        foreach ($student_practice as $key => $practice) {
            EduStudentPracticeTime_Provider::find($practice->id)->delete();
        }

        $student_watch_infos = EduStudentVideoWatchInfo_Provider::valid()
            ->where('course_id',$course_id)
            ->where('batch_id',$batch_id)
            ->where('student_id',$student_id)
            ->get();

        foreach ($student_watch_infos as $key => $w_info) {
            EduStudentVideoWatchInfo_Provider::find($w_info->id)->delete();
        }


        $student_attendence= EduStudentAttendence_Provider::valid()
            ->where('course_id',$course_id)
            ->where('batch_id',$batch_id)
            ->where('student_id',$student_id)
            ->get();

        foreach ($student_attendence as $key => $attendence) {
            EduStudentAttendence_Provider::find($attendence->id)->delete();
        }


        $class_assignment_ids = EduClassAssignments_Provider::valid()
            ->where('course_id',$course_id)
            ->where('batch_id',$batch_id)
            ->pluck('id')->toArray();

        $submit_assignment =  EduAssignmentSubmission_Provider::valid()
            ->whereIn('assignment_id',$class_assignment_ids)
            ->where('created_by',$student_id)
            ->get();

        foreach ($submit_assignment as $key => $assignment) {
            EduAssignmentSubmission_Provider::find($assignment->id)->delete();
        }

        $student_exams =  EduStudentExam_Provider::valid()
            ->where('course_id',$course_id)
            ->where('batch_id',$batch_id)
            ->where('student_id',$student_id)
            ->get();

        foreach ($student_exams as $key => $exam) {
            EduStudentExam_Provider::find($exam->id)->delete();
        }

        $studentPerforms = EduStudentPerformance_Provider::valid()
            ->where('batch_id', $batch_id)
            ->where('course_id', $course_id)
            ->where('student_id', $student_id)
            ->get();
        foreach ($studentPerforms as $key => $perform) {
            EduStudentPerformance_Provider::find($perform->id)->delete();
        }
        $student_batch_info->delete();

        DB::commit();
    }

}
