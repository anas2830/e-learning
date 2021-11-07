<?php

namespace App\Http\Controllers\Provider;

use DB;
use Auth;
use File;
use Helper;
use Validator;
use Illuminate\Support\Str;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Models\EduStudent_Provider;
use App\Http\Controllers\Controller;
use App\Models\EduEventSms_Provider;


use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\EduStudentExam_Provider;
use App\Models\EduClassAssignments_Provider;
use App\Models\EduStudentAttendence_Provider;
use App\Models\EduAssignBatchStudent_Provider;
use App\Models\EduStudentPerformance_Provider;
use App\Models\EduStudentPracticeTime_Provider;
use App\Models\EduAssignmentSubmission_Provider;
use App\Models\EduStudentVideoWatchInfo_Provider;
use App\Models\EduAssignmentSubmissionAttachment_Provider;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['all_students'] = EduStudent_Provider::valid()->orderBy('id', 'asc')->get();
        return view('provider.student.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('provider.student.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input =  $request->all();
        $addStuType = $request->addStuType;
        if($addStuType == 1){ //Single Input
            $validator['name'] = 'required';
            $validator['email'] = 'unique:App\Models\User,email';
            $validator['phone'] = 'unique:App\Models\User,phone';
        } else{ 
            $validator['csvFile'] = 'required';
        }
        $validator = Validator::make($input, $validator);

        if ($validator->passes()) {
            if ($addStuType == 1) { //Single Input
                //send sms
                $event_message = EduEventSms_Provider::valid()->where('type', 1)->where('status',1)->first();
                if(!empty($event_message)){
                    $message = $event_message->message;
                    if(preg_match("~\@"."name"."\@~", $message)){
                        $message = str_replace("@name@", $request->name , $message);
                    }
                    else{
                        $message = $message;
                    }

                    $msisdn = $request->phone;
                    $messageBody = $message;
                    $csmsId = Str::random(12);
                    Helper::singleSms($msisdn, $messageBody, $csmsId);
                }
                //end send sms
                EduStudent_Provider::create([
                    'student_id'   => $request->student_id,
                    'sur_name'     => $request->sur_name,
                    'name'         => $request->name,
                    'address'      => $request->address,
                    'email'        => $request->email,
                    'password'     => Hash::make(123456789),
                    'phone'        => $request->phone,
                    'backup_phone' => $request->backup_phone,
                    'fb_profile'   => $request->fb_profile,
                ]);
                $output['messege'] = 'Student has been created';
                $output['msgType'] = 'success';
            } else {
                $file = $request->csvFile;
                $path = public_path('uploads/csv');
                $fileOriginalName = $file->getClientOriginalName();
                $file->move($path, $fileOriginalName);

                if(strtolower($file->guessClientExtension())=='xls' || strtolower($file->guessClientExtension())=='csv') {
                    Excel::import(new UsersImport, $path.'/'.$fileOriginalName);
                    $output['messege'] = 'Student has been created';
                    $output['msgType'] = 'success';

                } else {
                    if(strtolower($file->guessClientExtension())=='bin') {
                        $output['messege'] = 'Give maximum 8000 row at a file.';
                        $output['msgType'] = 'danger';
                    } else {
                        echo json_encode(['errorMsg' => 'File is not CSV. This file format is '.$file->guessClientExtension().'.']);
                        $output['messege'] = 'File is not CSV. This file format is '.$file->guessClientExtension();
                        $output['msgType'] = 'danger';
                    }
                }
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
        $data['student'] = EduStudent_Provider::valid()->find($id);
        return view('provider.student.update', $data);
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

        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'email'      => 'unique:App\Models\User,email,'.$id,
            'phone'      => 'unique:App\Models\User,phone,'.$id,
        ]);

        if ($validator->passes()) {

            if(!empty($request->password)){

                EduStudent_Provider::find($id)->update([
                    'student_id'  => $request->student_id,
                    'sur_name'    => $request->sur_name,
                    'name'        => $request->name,
                    'address'     => $request->address,
                    'email'       => $request->email,
                    'password'    => Hash::make($request->password),
                    'phone'       => $request->phone,
               ]);

            }else{
                EduStudent_Provider::find($id)->update([
                    'student_id'  => $request->student_id,
                    'sur_name'    => $request->sur_name,
                    'name'        => $request->name,
                    'address'     => $request->address,
                    'email'       => $request->email,
                    'phone'       => $request->phone,
               ]);
            }
            
            $output['messege'] = 'Student has been updated';
            $output['msgType'] = 'success';

            return redirect()->back()->with($output);

        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student_batch_info = EduAssignBatchStudent_Provider::valid()
            ->where('active_status',1)
            ->where('student_id',$id)
            ->first();
        if (!empty($student_batch_info)) {
            $course_id = $student_batch_info->course_id;
            $batch_id = $student_batch_info->batch_id;
    
            //delete all activity this student
            DB::beginTransaction();
    
            $student_practice = EduStudentPracticeTime_Provider::valid()
                ->where('course_id',$course_id)
                ->where('batch_id',$batch_id)
                ->where('student_id',$id)
                ->get();
    
            foreach ($student_practice as $key => $practice) {
                EduStudentPracticeTime_Provider::find($practice->id)->delete();
            }
    
            $student_watch_infos = EduStudentVideoWatchInfo_Provider::valid()
                ->where('course_id',$course_id)
                ->where('batch_id',$batch_id)
                ->where('student_id',$id)
                ->get();
    
            foreach ($student_watch_infos as $key => $w_info) {
                EduStudentVideoWatchInfo_Provider::find($w_info->id)->delete();
            }
    
    
            $student_attendence= EduStudentAttendence_Provider::valid()
                ->where('course_id',$course_id)
                ->where('batch_id',$batch_id)
                ->where('student_id',$id)
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
                ->where('created_by',$id)
                ->get();
    
            foreach ($submit_assignment as $key => $assignment) {
                EduAssignmentSubmissionAttachment_Provider::valid()->where('assignment_submission_id', $assignment->id)->delete();
                EduAssignmentSubmission_Provider::find($assignment->id)->delete();
            }
    
            $student_exams =  EduStudentExam_Provider::valid()
                ->where('course_id',$course_id)
                ->where('batch_id',$batch_id)
                ->where('student_id',$id)
                ->get();
    
            foreach ($student_exams as $key => $exam) {
                EduStudentExam_Provider::find($exam->id)->delete();
            }

            $studentPerforms = EduStudentPerformance_Provider::valid()
                ->where('batch_id', $batch_id)
                ->where('course_id', $course_id)
                ->where('student_id', $id)
                ->get();
            foreach ($studentPerforms as $key => $perform) {
                EduStudentPerformance_Provider::find($perform->id)->delete();
            }
            
            $student_batch_info->delete();
            // EduStudent_Provider::valid()->find($id)->delete();
            DB::commit();

        } else {
            EduStudent_Provider::valid()->find($id)->delete();
        }
        
    }

    //TRAINEE USER LOGIN
    public function traineeUserLogin(Request $request)
    {
        $userId = $request->id;
        $data = array(
            'id'            => $userId,
            // 'active_status' => 1,
            'valid'         => 1
        );
        $user = EduStudent_Provider::where('valid', 1)->find($userId);
        
        $output = array();
        
        if (!empty($user)) {
            Auth::loginUsingId($userId);
            $output["result"] = true;
        } else {
            $output["result"] = false;
            $output["msg"] = "Id is not valid or verified.";
        }
        return json_encode($output);
    }
}
