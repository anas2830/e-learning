<?php

namespace App\Http\Controllers\Provider;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EduClassAssignments_Provider;
use App\Models\EduStudentAttendence_Provider;
use App\Models\EduAssignBatchClasses_Provider;
use App\Models\EduAssignBatchStudent_Provider;
use App\Models\EduStudentPerformance_Provider;
use App\Models\EduAssignmentSubmission_Provider;

class StdMarkUpdateController extends Controller
{
    public function index(Request $request)
    {
        $data['assign_students'] = EduAssignBatchStudent_Provider::join('users', 'users.id', '=', 'edu_assign_batch_students.student_id')
            ->join('edu_assign_batches', 'edu_assign_batches.id', '=', 'edu_assign_batch_students.batch_id')
            ->select('edu_assign_batch_students.*', 'edu_assign_batches.batch_no', 'users.name','users.email','users.phone')
            ->where('edu_assign_batch_students.active_status', 1)
            ->where('edu_assign_batch_students.valid', 1)
            ->where('edu_assign_batches.valid', 1)
            ->where('users.valid', 1)
            ->get();

        return view('provider.stdMarkUpdate.listData', $data);
    }
    public function stdClassList(Request $request)
    {
        $assign_batch_std_id = $request->assign_batch_std_id;
        $data['assign_batch_student_info'] = $assign_batch_student_info = EduAssignBatchStudent_Provider::find($assign_batch_std_id);
        $data['batch_completed_classes'] = EduAssignBatchClasses_Provider::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
            ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name')
            ->where('edu_assign_batch_classes.batch_id', $assign_batch_student_info->batch_id)
            ->where('edu_assign_batch_classes.course_id', $assign_batch_student_info->course_id)
            ->where('edu_assign_batch_classes.complete_status', 1)
            ->where('edu_assign_batch_classes.valid', 1)
            ->get();

        return view('provider.stdMarkUpdate.classListData', $data);
    }
    public function stdClassMarkUpdate(Request $request)
    {
        $data['type'] = $type = $request->type;
        $data['assign_batch_std_id'] = $assign_batch_std_id = $request->assign_batch_std_id;
        $data['assign_batch_class_id'] = $assign_batch_class_id = $request->assign_batch_class_id;
        $data['assign_batch_student_info'] = $assign_batch_student_info = EduAssignBatchStudent_Provider::find($assign_batch_std_id);
        
        if ($type == 1) { //1=Class Mark
            $data['student_class_info'] = EduStudentAttendence_Provider::valid()
                ->where('batch_id', $assign_batch_student_info->batch_id)
                ->where('course_id', $assign_batch_student_info->course_id)
                ->where('class_id', $assign_batch_class_id) //class_id = assign_batch_class_id
                ->where('student_id', $assign_batch_student_info->student_id)
                ->first();
        } else { //2=Assignment Mark
            $class_assignment_ids = EduClassAssignments_Provider::valid()
                ->where('batch_id', $assign_batch_student_info->batch_id)
                ->where('course_id', $assign_batch_student_info->course_id)
                ->where('assign_batch_class_id', $assign_batch_class_id)
                ->get()->pluck('id');
            $data['student_class_info'] = EduAssignmentSubmission_Provider::valid()
                ->whereIn('assignment_id', $class_assignment_ids)
                ->where('created_by', $assign_batch_student_info->student_id)
                ->first();
        }
        return view('provider.stdMarkUpdate.markUpdate', $data);
        
    }
    public function stdClassMarkAction(Request $request)
    {
        $output = [];
        $type = $request->type;
        $assign_batch_std_id = $request->assign_batch_std_id;
        $assign_batch_class_id = $request->assign_batch_class_id;
        $assign_batch_student_info = EduAssignBatchStudent_Provider::find($assign_batch_std_id);
        
        $validator = Validator::make($request->all(), [
            'mark'        => 'required',
            'primary_key' => 'required'
        ]);
        if (isset($request->primary_key)) {
            if ($validator->passes()) {
                $student_perform_info = EduStudentPerformance_Provider::valid()
                        ->where('batch_id', $assign_batch_student_info->batch_id)
                        ->where('course_id', $assign_batch_student_info->course_id)
                        ->where('assign_batch_classes_id', $assign_batch_class_id)
                        ->where('student_id', $assign_batch_student_info->student_id)
                        ->first();
    
                if ($type == 1) { //1=Class Mark
                    $student_attendance_info = EduStudentAttendence_Provider::valid()
                        ->where('batch_id', $assign_batch_student_info->batch_id)
                        ->where('course_id', $assign_batch_student_info->course_id)
                        ->where('class_id', $assign_batch_class_id) //class_id = assign_batch_class_id
                        ->where('student_id', $assign_batch_student_info->student_id)
                        ->find($request->primary_key);
                    
                    DB::beginTransaction();
                    if (!empty($student_attendance_info)) {
                        $student_attendance_info->update([
                            'mark'      => $request->mark > 0 ? $request->mark: 0,
                            'is_attend' => $request->mark > 0 ? 1 : 0
                        ]);
                    }
                    if (!empty($student_perform_info)) {
                        $student_perform_info->update([
                            'class_mark' => $request->mark > 0 ? $request->mark : 0,
                            'attendence' => $request->mark > 0 ? 100 : 0
                        ]);
                    }
                    DB::commit();
                    $output['messege'] = 'Class mark has been Updated';
                    $output['msgType'] = 'success';
                } else { //2=Assignment Mark
                    $class_assignment_ids = EduClassAssignments_Provider::valid()
                        ->where('batch_id', $assign_batch_student_info->batch_id)
                        ->where('course_id', $assign_batch_student_info->course_id)
                        ->where('assign_batch_class_id', $assign_batch_class_id)
                        ->get()->pluck('id');
                    $student_submission_info = EduAssignmentSubmission_Provider::valid()
                        ->whereIn('assignment_id', $class_assignment_ids)
                        ->where('created_by', $assign_batch_student_info->student_id)
                        ->find($request->primary_key);
    
                    DB::beginTransaction();
                    if (!empty($student_submission_info)) {
                        $student_submission_info->update([
                            'mark'      => $request->mark > 0 ? $request->mark: 0,
                            'is_attend' => $request->mark > 0 ? 1 : 0
                        ]);
                    }
                    if (!empty($student_perform_info)) {
                        $student_perform_info->update([
                            'assignment' => $request->mark > 0 ? $request->mark : 0
                        ]);
                    }
                    DB::commit();
                    $output['messege'] = 'Assignment mark has been Updated';
                    $output['msgType'] = 'success';
                }
    
                return redirect()->back()->with($output);
            } else {
                return redirect()->back()->withErrors($validator);
            }
        } else {
            if ($type == 1) {
                $message = 'Class Attendance Not Taken!!!';
            } else {
                $message = 'Class Assignment Not Submitted by this Student!!!';
            }
            $output['messege'] = $message;
            $output['msgType'] = 'danger';
            return redirect()->back()->with($output);
        }
        
    }

}
