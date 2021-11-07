<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EduCourseAssignClass_Provider;
use App\Models\EduAssignBatchClasses_Provider;
use App\Models\EduAssignBatch_Provider;
use App\Models\EduStudentAttendence_Provider;
use App\Models\EduCourseClassMaterials_Provider;
use App\Models\EduCourses_Provider;
use Validator;
use Helper;

class BatchAddClassController extends Controller
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
        $data['course_info'] = EduCourses_Provider::valid()->find($batch_info->course_id);
        $data['class_list'] = $class_list = EduCourseAssignClass_Provider::join('edu_assign_batch_classes','edu_assign_batch_classes.class_id','=','edu_course_assign_classes.id')
            ->select('edu_course_assign_classes.*','edu_assign_batch_classes.id as assign_batch_id')
            ->where('edu_assign_batch_classes.batch_id',$batch_id)
            ->where('edu_assign_batch_classes.valid',1)
            ->where('edu_course_assign_classes.valid',1)
            ->get();
        foreach ($class_list as $key => $class) {
            $class->is_attendence_done = EduStudentAttendence_Provider::valid()->where('class_id', $class->assign_batch_id)->count();
        }
        
        return view('provider.assignBatch.class.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['batch_id'] = $batch_id = $request->batch_id;
        $data['batch_info'] = $batch_info =  EduAssignBatch_Provider::valid()->find($batch_id);
        $data['course_info'] = EduCourses_Provider::valid()->find($batch_info->course_id);

        $assign_class = EduAssignBatchClasses_Provider::valid()
            ->where('course_id',$batch_info->course_id)
            ->where('batch_id',$batch_id)
            ->pluck('class_id')->toArray();

        $course_class = EduCourseAssignClass_Provider::valid()
            ->where('course_id',$batch_info->course_id)
            ->pluck('id')->toArray();

        $new_course_class = array_diff($course_class,$assign_class);

        $data['new_course_classes'] = EduCourseAssignClass_Provider::valid()->whereIn('id',$new_course_class)->get();
        
        // echo "<pre>";
        // print_r($new_course_class); exit();

        return view('provider.assignBatch.class.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $batch_id = $request->batch_id;
        $course_id = $request->course_id;
        $class_id = $request->class_id;

        $validator = Validator::make($request->all(), [
            'class_id'     => 'required',
        ]);
        
        if ($validator->passes()) {
            EduAssignBatchClasses_Provider::create([
                'batch_id'        => $batch_id,
                'course_id'       => $course_id,
                'class_id'        => $class_id,
            ]);

            $output['messege'] = 'Class has been created';
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        
        $materials_info = EduCourseClassMaterials_Provider::valid()->where('class_id',$id)->count();
        $attendence_info = EduStudentAttendence_Provider::valid()->where('class_id',$id)->count();

        if($materials_info == 0 && $attendence_info == 0){
            EduAssignBatchClasses_Provider::valid()->find($id)->delete();
        }else {
            return response($data['error'] = 'This Class already Used !!');
        }
    }

    public function showAttendence(Request $request)
    {
        $data['batch_class_id'] = $batch_class_id = $request->batch_class_id;
        $data['batch_class_info'] = EduAssignBatchClasses_Provider::valid()->find($batch_class_id);
        $data['attendenceLists'] = EduStudentAttendence_Provider::join('users','users.id','=','edu_student_attendences.student_id')
            ->select('edu_student_attendences.*','users.name','users.phone','users.student_id')
            ->where('edu_student_attendences.class_id', $batch_class_id)
            ->where('edu_student_attendences.valid', 1)
            ->get();

        return view('provider.assignBatch.class.attendentListData', $data);
    }

    public function attendenceRemark(Request $request)
    {
        $data['attendence_id'] = $attendence_id = $request->attendence_id;
        $data['attend_student'] = EduStudentAttendence_Provider::valid()->find($attendence_id);

        return view('provider.assignBatch.class.updateRemark', $data);
    }

    public function saveAttendenceRemark(Request $request)
    {
        $attendence_id = $request->attendence_id;
        $validator = Validator::make($request->all(), [
            'remark' => 'required'
        ]);

        if ($validator->passes()) {
            EduStudentAttendence_Provider::find($attendence_id)->update(['remark' => $request->remark]);
            
            $output['messege'] = 'Student Remark has been Updated';
            $output['msgType'] = 'success';
            return redirect()->back()->with($output);
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }
}
