<?php

namespace App\Http\Controllers\Teacher;
use File;
use Helper;
use Validator;
use DB;
use Auth;
use Str;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\EduStudentWidget_Teacher;
use App\Models\EduAssignBatch_Teacher;
use App\Models\EduAssignBatchStudent_Teacher;
use App\Models\EduStudent_Teacher;
use App\Models\EduCourses_Teacher;
use App\Models\EduActivityNotify_Teacher;

class StuedentWidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['all_widgets'] = EduStudentWidget_Teacher::valid()->latest()->limit(6)->get();
        return view('teacher.studentWidget.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teacher_id = Auth::guard('teacher')->id();
        $teacher_batch_id = EduAssignBatch_Teacher::valid()->where('teacher_id',$teacher_id)->where('active_status',1)->pluck('id');
        $teacher_course_id = EduAssignBatch_Teacher::valid()
            ->where('teacher_id',$teacher_id)
            ->where('active_status',1)
            ->groupBy('course_id')
            ->pluck('course_id');


        if(count($teacher_course_id) > 0){
            $data['course_list'] = EduCourses_Teacher::valid()->whereIn('id',$teacher_course_id)->get();
        }else {
            $data['course_list'] = [];
        }
        

        if(count($teacher_batch_id) > 0){
            $data['batch_list'] =  EduAssignBatch_Teacher::valid()->where('teacher_id',$teacher_id)->where('active_status',1)->get();
            $data['students_list'] = EduStudent_Teacher::join('edu_assign_batch_students','edu_assign_batch_students.student_id','=','users.id')
                ->select('users.*')
                ->whereIn('edu_assign_batch_students.batch_id',$teacher_batch_id)
                ->where('users.valid',1)
                ->where('edu_assign_batch_students.valid',1)
                ->get();
        }else {
            $data['batch_list'] = [];
            $data['students_list'] = [];
        }

        // echo "<pre>";
        // print_r($data['course_list']->toArray()); exit();

        return view('teacher.studentWidget.create', $data);
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
        $teacher_id = Auth::guard('teacher')->id();
        $today_date = date('Y-m-d');
        $current_time = date('H:i:s');

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
                EduStudentWidget_Teacher::create([
                    'type'       => $type,
                    'course_id'  => $request->course_id,
                    'title'      => $request->title,
                    'overview'   => $request->overview
                ]);

                EduActivityNotify_Teacher::create([
                    'course_id'  => $request->course_id,
                    'notify_date'=> $today_date,
                    'notify_time'=> $current_time,
                    'notify_type'=> 1,
                    'notify_title'=> Str::words($request->title, 20, '.....'),
                    'notify_link'=> 'home',
                    'created_type'=> 2,
                ]);

            }
            else if($type == 2){
                $batch_course_id = EduAssignBatch_Teacher::valid()->where('id',$request->batch_id)->where('active_status',1)->first()->course_id;
                EduStudentWidget_Teacher::create([
                    'type'       => $type,
                    'batch_id'   => $request->batch_id,
                    'course_id'  => $batch_course_id,
                    'title'      => $request->title,
                    'overview'   => $request->overview
                ]);
                EduActivityNotify_Teacher::create([
                    'batch_id'   => $request->batch_id,
                    'course_id'  => $batch_course_id,
                    'notify_date'=> $today_date,
                    'notify_time'=> $current_time,
                    'notify_type'=> 1,
                    'notify_title'=> Str::words($request->title, 20, '.....'),
                    'notify_link'=> 'home',
                    'created_type'=> 2,
                ]);
            }else{
                $student_batch_course_info = EduAssignBatchStudent_Teacher::valid()
                    ->where('student_id',$request->student_id)
                    ->where('active_status',1)
                    ->get();

                foreach($student_batch_course_info as $key => $student_info){

                    EduStudentWidget_Teacher::create([
                        'type'       => $type,
                        'batch_id'   => $student_info->batch_id,
                        'course_id'  => $student_info->course_id,
                        'student_id' => $request->student_id,
                        'title'      => $request->title,
                        'overview'   => $request->overview
                    ]);

                    EduActivityNotify_Teacher::create([
                        'batch_id'   => $student_info->batch_id,
                        'course_id'  => $student_info->course_id,
                        'student_id' => $request->student_id,
                        'notify_date'=> $today_date,
                        'notify_time'=> $current_time,
                        'notify_type'=> 1,
                        'notify_title'=> Str::words($request->title, 20, '.....'),
                        'notify_link'=> 'home',
                        'created_type'=> 2,
                    ]);
                }
            }
            $output['messege'] = 'Widget has been created';
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
        $data['widget_info'] = EduStudentWidget_Teacher::valid()->find($id);

        $teacher_id = Auth::guard('teacher')->id();
        $teacher_batch_id = EduAssignBatch_Teacher::valid()->where('teacher_id',$teacher_id)->where('active_status',1)->pluck('id');
        $teacher_course_id = EduAssignBatch_Teacher::valid()
            ->where('teacher_id',$teacher_id)
            ->where('active_status',1)
            ->groupBy('course_id')
            ->pluck('course_id');


        if(count($teacher_course_id) > 0){
            $data['course_list'] = EduCourses_Teacher::valid()->whereIn('id',$teacher_course_id)->get();
        }else {
            $data['course_list'] = [];
        }
        

        if(count($teacher_batch_id) > 0){
            $data['batch_list'] =  EduAssignBatch_Teacher::valid()->where('teacher_id',$teacher_id)->where('active_status',1)->get();
            $data['students_list'] = EduStudent_Teacher::join('edu_assign_batch_students','edu_assign_batch_students.student_id','=','users.id')
                ->select('users.*')
                ->whereIn('edu_assign_batch_students.batch_id',$teacher_batch_id)
                ->where('users.valid',1)
                ->where('edu_assign_batch_students.valid',1)
                ->get();
        }else {
            $data['batch_list'] = [];
            $data['students_list'] = [];
        }

        return view('teacher.studentWidget.update', $data);
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
        $teacher_id = Auth::guard('teacher')->id();

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
                EduStudentWidget_Teacher::find($id)->update([
                    'type'       => $type,
                    'course_id'  => $request->course_id,
                    'title'      => $request->title,
                    'overview'   => $request->overview
                ]);
            }
            else if($type == 2){
                $batch_course_id = EduAssignBatch_Teacher::valid()->where('id',$request->batch_id)->where('active_status',1)->first()->course_id;
                EduStudentWidget_Teacher::find($id)->update([
                    'type'       => $type,
                    'batch_id'   => $request->batch_id,
                    'course_id'  => $batch_course_id,
                    'title'      => $request->title,
                    'overview'   => $request->overview
                ]);
            }else{
                $student_batch_course_info = EduAssignBatchStudent_Teacher::valid()
                    ->where('student_id',$request->student_id)
                    ->where('active_status',1)
                    ->get();

                    foreach($student_batch_course_info as $key => $student_info){

                        EduStudentWidget_Teacher::create([
                            'type'       => $type,
                            'batch_id'   => $student_batch_course_info->batch_id,
                            'course_id'  => $student_batch_course_info->course_id,
                            'student_id' => $request->student_id,
                            'title'      => $request->title,
                            'overview'   => $request->overview
                        ]);
                    }
                    EduStudentWidget_Teacher::find($id)->delete();
                    
            }

            $output['messege'] = 'Widget has been Updated';
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
        EduStudentWidget_Teacher::valid()->find($id)->delete();
    }
}
