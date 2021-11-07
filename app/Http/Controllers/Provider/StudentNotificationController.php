<?php

namespace App\Http\Controllers\Provider;
use Validator;
use Helper;
use DB;
use Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EduStudentNotification_Provider;
use App\Models\EduCourses_Provider;
use App\Models\EduAssignBatch_Provider;
use App\Models\EduStudent_Provider;
use App\Models\EduAssignBatchStudent_Provider;

class StudentNotificationController extends Controller
{
    public function index()
    {
        $data['all_notications'] = EduStudentNotification_Provider::valid()->latest()->limit(6)->get();
        return view('provider.studentNotification.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['course_list'] = EduCourses_Provider::valid()->where('publish_status',1)->get();
        $data['batch_list'] = EduAssignBatch_Provider::valid()->where('active_status',1)->get();
        $data['students_list'] = EduStudent_Provider::valid()->where('active_status',1)->get();

        return view('provider.studentNotification.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $output = array();
        $input =  $request->all();
        $type = $request->type;

        if($type == 1){
            $validator['course_id'] = 'required';
        }elseif($type == 2){ 
            $validator['batch_id'] = 'required';
        }else{
            $validator['student_id'] = 'required';
        }

        $validator = [
            'type'     => 'required',
            'title'    => 'required',
            'overview' => 'required'
        ];

        $validator = Validator::make($input, $validator);

        if ($validator->passes()) {
            if($type == 1){
                EduStudentNotification_Provider::create([
                    'type'       => $type,
                    'course_id'  => $request->course_id,
                    'title'      => $request->title,
                    'overview'   => $request->overview
                ]);
            }
            else if($type == 2){
                $batch_course_id = EduAssignBatch_Provider::valid()->where('id',$request->batch_id)->where('active_status',1)->first()->course_id;
                EduStudentNotification_Provider::create([
                    'type'       => $type,
                    'batch_id'   => $request->batch_id,
                    'course_id'  => $batch_course_id,
                    'title'      => $request->title,
                    'overview'   => $request->overview
                ]);
            }else{
                $student_batch_course_info = EduAssignBatchStudent_Provider::valid()
                    ->where('student_id',$request->student_id)
                    ->where('active_status', 1)
                    ->get();

                foreach($student_batch_course_info as $key => $student_info){
                    EduStudentNotification_Provider::create([
                        'type'       => $type,
                        'batch_id'   => $student_info->batch_id,
                        'course_id'  => $student_info->course_id,
                        'student_id' => $request->student_id,
                        'title'      => $request->title,
                        'overview'   => $request->overview
                    ]);
                }
            }

            $output['messege'] = 'Notification has been created';
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
        $data['notification_info'] = EduStudentNotification_Provider::valid()->find($id);
        $data['course_list'] = EduCourses_Provider::valid()->where('publish_status',1)->get();
        $data['batch_list'] = EduAssignBatch_Provider::valid()->where('active_status',1)->get();
        $data['students_list'] = EduStudent_Provider::valid()->where('active_status',1)->get();
    
        return view('provider.studentNotification.update', $data);
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
        $output = array();
        $input =  $request->all();
        $type = $request->type;

        if($type == 1){
            $validator['course_id'] = 'required';
        }elseif($type == 2){ 
            $validator['batch_id'] = 'required';
        }else{
            $validator['student_id'] = 'required';
        }

        $validator = [
            'type'     => 'required',
            'title'    => 'required',
            'overview' => 'required'
        ];

        $validator = Validator::make($input, $validator);

        if ($validator->passes()) {
            if($type == 1){
                EduStudentNotification_Provider::find($id)->update([
                    'type'       => $type,
                    'course_id'  => $request->course_id,
                    'title'      => $request->title,
                    'overview'   => $request->overview
                ]);
            }
            else if($type == 2){
                $batch_course_id = EduAssignBatch_Provider::valid()->where('id',$request->batch_id)->where('active_status',1)->first()->course_id;
                EduStudentNotification_Provider::find($id)->update([
                    'type'       => $type,
                    'batch_id'   => $request->batch_id,
                    'course_id'  => $batch_course_id,
                    'title'      => $request->title,
                    'overview'   => $request->overview
                ]);
            }else{
                $student_batch_course_info = EduAssignBatchStudent_Provider::valid()
                    ->where('student_id',$request->student_id)
                    ->where('is_running',1)
                    ->where('active_status',1)
                    ->first();

                foreach($student_batch_course_info as $key => $student_info){

                EduStudentNotification_Provider::create([
                    'type'       => $type,
                    'batch_id'   => $student_info->batch_id,
                    'course_id'  => $student_info->course_id,
                    'student_id' => $request->student_id,
                    'title'      => $request->title,
                    'overview'   => $request->overview
                ]);
            }

            EduStudentNotification_Provider::find($id)->delete(); 
        }

            $output['messege'] = 'Notification has been Updated';
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
        EduStudentNotification_Provider::valid()->find($id)->delete();
    }
}
