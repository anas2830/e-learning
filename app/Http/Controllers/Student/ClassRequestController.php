<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Helper;
use File;
use Auth;
use DateTime;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SupportRequestMail;
use App\Models\EduAssignBatchStudent_User;
use App\Models\EduClassRequest_User;
use App\Models\EduAssignBatchClasses_User;
use App\Models\EduAssignBatch_User;
use App\Models\EduTeacher_User;

class ClassRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authId = Auth::id();
        $student_batch_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
        if (!empty($student_batch_info)) {
            $data['requested_classes'] = EduClassRequest_User::join('edu_assign_batch_classes', 'edu_assign_batch_classes.id', 'edu_class_requests.assign_batch_class_id')
                ->join('edu_course_assign_classes', 'edu_course_assign_classes.id', 'edu_assign_batch_classes.class_id')
                ->select('edu_class_requests.*', 'edu_course_assign_classes.class_name')
                ->where('edu_class_requests.batch_id', $student_batch_info->batch_id)
                ->where('edu_class_requests.created_by', $authId)
                ->where('edu_class_requests.valid', 1)
                ->orderBy('edu_class_requests.id', 'desc')
                ->get();
        } else {
            $data['requested_classes'] = [];
        }
        return view('student.classroom.classRequest.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $student_batch_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
        $data['done_classes'] = EduAssignBatchClasses_User::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
            ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name')
            ->where('edu_assign_batch_classes.batch_id', $student_batch_info->batch_id)
            ->where('edu_assign_batch_classes.complete_status', 1)
            ->where('edu_assign_batch_classes.valid', 1)
            ->get();
        return view('student.classroom.classRequest.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assign_batch_class_id' => 'required',
            'request_reasons'       => 'required'
        ]);

        if ($validator->passes()) {

            $student_batch_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
            $batch_class_info = EduAssignBatchClasses_User::valid()->find($request->assign_batch_class_id);
            if (isset($batch_class_info->drive_link)) {
                $batch_class_drive_link = $batch_class_info->drive_link;
            } else {
                $batch_class_drive_link = '#';
            }
            if(!empty($student_batch_info)){

                EduClassRequest_User::create([
                    'batch_id'              => $student_batch_info->batch_id,
                    'assign_batch_class_id' => $request->assign_batch_class_id,
                    'request_reasons'       => $request->request_reasons,
                    'class_link'            => $batch_class_drive_link
                ]);
                    
                $assign_teacher_id = EduAssignBatch_User::valid()->find($student_batch_info->batch_id)->teacher_id;
                $email = EduTeacher_User::valid()->find($assign_teacher_id)->email;

                $details = array(
                    'title'   => 'Class Request',
                    'details' => $request->request_reasons
                ); 
                if (!empty($email)) {
                    Mail::to($email)->send(new SupportRequestMail($details));
                }

                $output['messege'] = 'Request Class has been created';
                $output['msgType'] = 'success';
            }else{
                $output['messege'] = 'You are not allow for support';
                $output['msgType'] = 'danger';
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
        $data['request_info'] = $request_info = EduClassRequest_User::valid()->find($id);
        $data['done_classes'] = EduAssignBatchClasses_User::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
            ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name')
            ->where('edu_assign_batch_classes.batch_id', $request_info->batch_id)
            ->where('edu_assign_batch_classes.complete_status', 1)
            ->where('edu_assign_batch_classes.valid', 1)
            ->get();
        return view('student.classroom.classRequest.update', $data);
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
            'assign_batch_class_id' => 'required',
            'request_reasons'       => 'required'
        ]);

        if ($validator->passes()) {

            $student_batch_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();

            if(!empty($student_batch_info)){

                EduClassRequest_User::find($id)->update([
                    'batch_id'              => $student_batch_info->batch_id,
                    'assign_batch_class_id' => $request->assign_batch_class_id,
                    'request_reasons'       => $request->request_reasons
                ]);

                $output['messege'] = 'Request Class has been updated';
                $output['msgType'] = 'success';
            }else{
                $output['messege'] = 'You are not allow for support';
                $output['msgType'] = 'danger';
            }

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
        EduClassRequest_User::valid()->find($id)->delete();
    }
}
