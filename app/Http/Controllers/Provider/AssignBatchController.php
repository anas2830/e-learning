<?php

namespace App\Http\Controllers\Provider;

use DB;
use Helper;
use Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EduCourses_Provider;
use App\Models\EduAssignBatch_Provider;
use App\Models\EduCourseAssignClass_Provider;
use App\Models\EduAssignBatchClasses_Provider;
use App\Models\EduAssignBatchSchedule_Provider;
use App\Models\EduAssignBatchTeacher_Provider;
use App\Models\EduAssignBatchStudent_Provider;
use App\Models\EduStudentAttendence_Provider;
use App\Models\EduTeacher_Provider;
use App\Models\EduZoomAccount_Provider;
use App\Models\EduEventSms_Provider;

class AssignBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['assign_batches'] = $assign_batches = EduAssignBatch_Provider::join('edu_courses', 'edu_courses.id', '=', 'edu_assign_batches.course_id')
            ->select('edu_assign_batches.*', 'edu_courses.course_name')
            ->where('edu_assign_batches.valid', 1)
            ->get();
        
        foreach ($assign_batches as $key => $batch) {
            $batch->done_activity = EduStudentAttendence_Provider::valid()
                ->where('batch_id',$batch->id)
                ->count();
            $batch->is_schedule = EduAssignBatchSchedule_Provider::valid()
                ->where('batch_id', $batch->id)
                ->count();
            $batch->class_complete = EduAssignBatchClasses_Provider::valid()
                ->where('batch_id', $batch->id)
                ->where('complete_status','!=',1)
                ->count();
        }
        return view('provider.assignBatch.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['courses'] = EduCourses_Provider::valid()->get();
        return view('provider.assignBatch.create', $data);
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
            'course_id'         => 'required',
            'batch_no'          => 'required',
            'start_date'        => 'required',
            'start_time'        => 'required',
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();

            $classes = EduCourseAssignClass_Provider::valid()->where('course_id',$request->course_id)->get();

            if(count($classes) > 0){

                $batch_info = EduAssignBatch_Provider::create([
                        'batch_no'        => $request->batch_no,
                        'course_id'       => $request->course_id,
                        'start_date'      => Helper::dateYMD($request->start_date),
                        'start_time'      => Helper::timeHi24($request->start_time),
                        'batch_fb_url'    => $request->batch_fb_url,
                    ]);
    
    
                foreach ($classes as $key => $class) {
                    if($key == 0){
                        EduAssignBatchClasses_Provider::create([
                            'batch_id'        => $batch_info->id,
                            'course_id'       => $batch_info->course_id,
                            'class_id'        => $class->id,
                            'start_date'      => Helper::dateYMD($request->start_date),
                            'start_time'      => Helper::timeHi24($request->start_time),
                            'complete_status' => 2, //2==running
                        ]);
                    }else{
                        EduAssignBatchClasses_Provider::create([
                            'batch_id'        => $batch_info->id,
                            'course_id'       => $batch_info->course_id,
                            'class_id'        => $class->id,
                        ]);
                    }
                }
    
                $output['messege'] = 'Assign Batch has been created';
                $output['msgType'] = 'success';
            }else {
                $output['messege'] = 'Please at first create Archive class !!!';
                $output['msgType'] = 'danger';
            }

            DB::commit();
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
        $data['courses'] = EduCourses_Provider::valid()->get();
        $data['assign_batch'] = EduAssignBatch_Provider::valid()->find($id);
        return view('provider.assignBatch.update', $data);
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
            'course_id'         => 'required',
            'batch_no'          => 'required',
            'start_date'        => 'required',
            'start_time'        => 'required'
        ]);

        if ($validator->passes()) {
            EduAssignBatch_Provider::find($id)->update([
                'course_id'       => $request->course_id,
                'batch_no'        => $request->batch_no,
                'start_date'      => Helper::dateYMD($request->start_date),
                'start_time'      => Helper::timeHi24($request->start_time),
                'batch_fb_url'    => $request->batch_fb_url,
            ]);
            $output['messege'] = 'Assign Batche has been updated';
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
        $exits_teacher = EduAssignBatch_Provider::valid()->where('id',$id)->where('teacher_id','!=',null)->count();
        $exits_student = EduAssignBatchStudent_Provider::valid()->where('batch_id',$id)->count();
        $attendence    = EduStudentAttendence_Provider::valid()->where('batch_id',$id)->count();

        if($exits_teacher>0 || $exits_student>0){
            return  "This Batch already assigned Teacher or Students";
        }else if($attendence > 0){
            return  "This Batch already take attendence";
        }else{
            EduAssignBatch_Provider::valid()->find($id)->delete();
        }
    }


    public function schedule($id){
        $data['batch_id'] = $batch_id = $id;
        $total_days = DB::table('edu_schedule_days')->get();
        foreach($total_days as $day) {
            $schedule = EduAssignBatchSchedule_Provider::valid()
                            ->where('batch_id', $batch_id)
                            ->where('day_dt', $day->dt)
                            ->first();

            if(!empty($schedule)) {
                $day->schedule = $schedule;
            }
        }
        $data['total_days'] = $total_days;

        return view('provider.assignBatch.updateSchedule', $data);
    }

    public function updateSchedule(Request $request, $id){
        $batch_id = $id;
        $days = $request->days;
        $start_times = $request->start_times;
        $students = EduAssignBatchStudent_Provider::join('users', 'users.id', '=', 'edu_assign_batch_students.student_id')
            ->select('users.id','users.phone','users.name','edu_assign_batch_students.course_id')
            ->where('edu_assign_batch_students.batch_id', $batch_id)
            ->where('edu_assign_batch_students.valid', 1)
            ->get();

        $batch_no = EduAssignBatch_Provider::valid()->find($batch_id)->batch_no;
        $course_id = EduAssignBatchStudent_Provider::valid()->where('batch_id', $batch_id)->first()->course_id;
        $course_name = EduCourses_Provider::valid()->find($course_id)->course_name;

        $updateSchedule = false;

        if(!empty($days)) {
            $validation = true;
            foreach($days as $day) {
                if( empty($start_times[$day]) ){
                    $validation = false;
                    $validator['time'] = "Please Select Proper day and time";
                }
            }

            if($validation) {
                $schedule_db = EduAssignBatchSchedule_Provider::valid()
                                ->get()
                                ->pluck('day_dt')
                                ->all();

                $schedule_diff = array_diff($schedule_db, $days);

                //DELETE DIFFRENT DATA
                $oldSchedules = EduAssignBatchSchedule_Provider::valid()->where('batch_id', $batch_id)->whereIn('day_dt', $schedule_diff)->get();
                foreach ($oldSchedules as $key => $oldSchedule) {
                    EduAssignBatchSchedule_Provider::find($oldSchedule->id)->delete();
                }
                

                foreach($days as $day) {
                    $start_time = Helper::timeHi24($start_times[$day]);
                    $data['batch_id'] = $batch_id;
                    $data['day_dt'] = $day;
                    $data['start_time'] = $start_time;

                    $schedule = EduAssignBatchSchedule_Provider::valid()
                                    ->where('batch_id', $batch_id)
                                    ->where('day_dt', $day)
                                    ->first();
                                    
                    if(!empty($schedule)) {
                        EduAssignBatchSchedule_Provider::valid()
                                    ->where('batch_id', $batch_id)
                                    ->where('day_dt', $day)
                                    ->update($data);

                        $updateSchedule = true;
                        $output['messege'] = 'Batch schedule has been updated';
                    } else {
                        EduAssignBatchSchedule_Provider::create($data);
                        $output['messege'] = 'Batch schedule has been created';
                    }
                }
                // SMS SEND
                if($updateSchedule){
                    $event_message = EduEventSms_Provider::valid()->where('type', 3)->where('status',1)->first();
                    if(!empty($event_message)){
                        $messageData = array();
                        foreach($students as $key => $student){

                            $message = $event_message->message;
                            $studentName = $student->name;
                            $studentPhone = $student->phone;

                            if(preg_match("~\@"."name"."\@~", $message)){
                                $message = str_replace("@name@", $studentName , $message);
                            }
                            if(preg_match("~\@"."batch"."\@~", $message)){
                                $message = str_replace("@batch@", $batch_no , $message);
                            }
                            if(preg_match("~\@"."course"."\@~", $message)){
                                $message = str_replace("@course@", $course_name , $message);
                            }

                            $messageData[$key]['msisdn'] =$student->phone;
                            $messageData[$key]['text'] = $message;
                            $messageData[$key]['csms_id'] = Str::random(12);
                        }

                        if(count($messageData) > 0){
                            Helper::dynamicSms($messageData);
                        }
                    }
                }

                $output['msgType'] = 'success';
                return redirect()->back()->with($output);

            } else {
                $output['messege'] = 'Please input time correctly';
                $output['msgType'] = 'danger';
                return redirect()->back()->withErrors($validator);
            }
        } else {
            $output['messege'] = 'Please select a day';
            $output['msgType'] = 'danger';
            return redirect()->back()->withErrors($validator);
        } 
    }


    // menu assign student and teacher list
    public function assignBatchList()
    {
    $data['assign_batches'] = EduAssignBatch_Provider::join('edu_courses', 'edu_courses.id', '=', 'edu_assign_batches.course_id')
        ->select('edu_assign_batches.*', 'edu_courses.course_name')
        ->where('edu_assign_batches.valid', 1)
        ->where('edu_courses.valid', 1)
        // ->orderBy('edu_assign_batches.id', 'desc')
        ->get();
        
    return view('provider.assignBatch.assignBatchListData', $data);
    }

    public function assignTeacher(Request $request)
    {
        $data['batch_id'] = $batch_id = $request->id;
        $data['batch_info'] = EduAssignBatch_Provider::valid()->find($batch_id);
        $data['teachers'] = EduTeacher_Provider::valid()->get();
        return view('provider.assignBatch.assignTeacher', $data);
    }

    public function updateTeacher(Request $request)
    {
        $data['batch_id'] = $batch_id = $request->id;
        $validator = Validator::make($request->all(), [
            'teacher_id'         => 'required',
        ]);
        
        if ($validator->passes()) {
            $haveActivity = EduStudentAttendence_Provider::valid()
                ->where('batch_id', $batch_id)
                ->where('created_by', $request->teacher_id)
                ->where('user_type', 1)
                ->get();

            if (count($haveActivity) == 0) {
                EduAssignBatch_Provider::find($batch_id)->update([
                    'teacher_id'   => $request->teacher_id,
                ]);
                $output['messege'] = 'Teacher has been Assigned';
                $output['msgType'] = 'success';
            } else {
                $output['messege'] = 'Ops! Teacher has activities in this batch!!';
                $output['msgType'] = 'danger';
            }
            return redirect()->back()->with($output);
        }else{
            return redirect()->back()->withErrors($validator);
        }
    }

    //assign captain
    public function assignCaptain(Request $request)
    {
        $data['batch_id'] = $batch_id = $request->id;
        $data['batch_info'] = $batch_info  = EduAssignBatch_Provider::valid()->find($batch_id);


        if(!empty($batch_info->captain_ids)){
            $data['selected_captain'] =  json_decode($batch_info->captain_ids);
        }else{
            $data['selected_captain'] = [];
        }

        $data['batch_students'] = EduAssignBatchStudent_Provider::join('users', 'users.id', '=', 'edu_assign_batch_students.student_id')
            ->select('users.id','users.name','users.email')
            ->where('edu_assign_batch_students.batch_id',$batch_id)
            ->where('edu_assign_batch_students.active_status',1)
            ->where('edu_assign_batch_students.is_freez',0)
            ->where('edu_assign_batch_students.valid',1)
            ->get();

        return view('provider.assignBatch.assignCaptain', $data);
    }

    public function updateCaptain(Request $request)
    {
        $data['batch_id'] = $batch_id = $request->id;
        $validator = Validator::make($request->all(), [
            'captains'         => 'required',
        ]);
        
        if ($validator->passes()) {
            $captais_ids = array_unique($request->captains);
            EduAssignBatch_Provider::find($batch_id)->update([
                'captain_ids'   => $captais_ids,
            ]);
            $output['messege'] = 'Captain has been Assigned';
            $output['msgType'] = 'success';
        
            return redirect()->back()->with($output);
        }else{
            return redirect()->back()->withErrors($validator);
        }
    }
    
    // complete batch
    public function batchComplete($id){
        $data['batch_id'] = $batch_id = $id;
        return view('provider.assignBatch.batchComplete', $data);
    }

    public function batchCompleteAction(Request $request, $id){
        $batch_id = $id;
        $complete_status = $request->complete_status;
        $batch_course_id = EduAssignBatch_Provider::valid()->find($id)->course_id;

        $validator = Validator::make($request->all(), [
            'complete_status'         => 'required',
        ]);

        if ($validator->passes()) {
            if($batch_id != null){

                DB::beginTransaction();
                EduAssignBatch_Provider::find($batch_id)->update([
                    'complete_status'   => 1,
                ]);

                EduAssignBatchStudent_Provider::valid()->where('batch_id', $batch_id)->where('course_id',$batch_course_id)->where('active_status',1)->update([
                    'is_running'   => 0,
                    'active_status' => 0,
                ]);
                DB::commit();
                
                $output['msgType'] = 'success';
                $output['messege'] = 'Batch is completed successfully !!';
            }else{
                $output['messege'] = 'Batch is not found !!';
                $output['msgType'] = 'danger';
            }
            return redirect()->back()->with($output);
        }else{
            $output['msgType'] = 'danger';
            return redirect()->back()->withErrors($validator);
        }   
    }

    public function getCourseWiseBatch(Request $request){
        $course_id = $request->course_id;
        $data['batches'] = EduAssignBatch_Provider::valid()->where('course_id', $course_id)->get();
        return view('provider.assignBatch.coursenBatch', $data);
    }

    public function getBatchWiseStudents(Request $request){
        $batch_id = $request->batch_id;
        $course_id = $request->course_id;
        $data['students'] = EduAssignBatchStudent_Provider::join('users','users.id', '=','edu_assign_batch_students.student_id')
            ->select('users.name','users.id','users.email')
            ->where('edu_assign_batch_students.course_id', $course_id)
            ->where('edu_assign_batch_students.batch_id', $batch_id)
            ->get();

        return view('provider.assignBatch.coursenBatchStudents', $data);
    }

    // ASSIGN INSTRUCTOR
    public function assignInstructor(Request $request)
    {
        $data['batch_id'] = $batch_id = $request->batch_id;
        $data['batch_info'] = EduAssignBatch_Provider::valid()->find($batch_id);
        return view('provider.assignBatch.assignInstructor', $data);
    }
    public function updateInstructor(Request $request)
    {
        $data['batch_id'] = $batch_id = $request->batch_id;
        $validator = Validator::make($request->all(), [
            'instructor_chat_link'         => 'required',
        ]);
        
        if ($validator->passes()) {
            EduAssignBatch_Provider::find($batch_id)->update([
                'instructor_chat_link'   => $request->instructor_chat_link,
            ]);
            $output['messege'] = 'Instructor has been Assigned';
            $output['msgType'] = 'success';
            
            return redirect()->back()->with($output);
        }else{
            return redirect()->back()->withErrors($validator);
        }
    }
}
