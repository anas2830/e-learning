<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Validator;
use Helper;
use File;
use DB;
use Auth;

use App\Models\EduAssignBatch_Teacher;
use App\Models\EduAssignBatchSchedule_Teacher;
use App\Models\EduAssignBatchStudent_Teacher;
use App\Models\EduAssignBatchClasses_Teacher;
use App\Models\EduClassAssignments_Teacher;
use App\Models\EduAssignmentSubmission_Teacher;
use App\Models\EduStudentWiseClassAssignment_Teacher;

class AssignedBatchController extends Controller
{
	public function assignedBatch()
	{
		$authId = Auth::guard('teacher')->id();
    	$data['assign_batches'] = $assign_batches = EduAssignBatch_Teacher::join('edu_courses', 'edu_courses.id', '=', 'edu_assign_batches.course_id')
			->select('edu_assign_batches.*', 'edu_courses.course_name')
			->where('edu_assign_batches.valid', 1)
            ->where('edu_assign_batches.teacher_id', $authId)
			->orderBy('edu_assign_batches.id', 'desc')
			->get();

        foreach ($assign_batches as $key => $assign_batche) {
        	$assign_batche->schedules = EduAssignBatchSchedule_Teacher::valid()->where('batch_id',$assign_batche->id)->get();
        	$assign_batche->total_students = EduAssignBatchStudent_Teacher::valid()->where('active_status',1)->where('batch_id',$assign_batche->id)->count();
        }
        return view('teacher.assignBatch.listData', $data);

    }

	// public function allAssignmentThrow(Request $request,$batch_id){
	// 	$batch_info = EduAssignBatch_Teacher::valid()->find($batch_id);
	// 	$assign_batch_classes = EduAssignBatchClasses_Teacher::valid()->where('batch_id',$batch_id)->where('course_id',$batch_info->course_id)->where('complete_status',1)->get();

	// 	$assign_batch_students = EduAssignBatchStudent_Teacher::valid()->where('batch_id',$batch_id)->where('course_id',$batch_info->course_id)->where('active_status',1)->get();
	// 	$hasBatchData = EduStudentWiseClassAssignment_Teacher::valid()->where('batch_id', $batch_id)->get();

	// 	if(count($assign_batch_classes) > 0 && count($hasBatchData) == 0){

	// 		foreach($assign_batch_classes as $key => $assign_classes){

	// 			$class_assignment_ids = EduClassAssignments_Teacher::valid()
	// 				->where('assign_batch_class_id',$assign_classes->id)
	// 				->get()->pluck('id')->toArray();

	// 			foreach($assign_batch_students as $key => $student){
					
	// 				if(count($class_assignment_ids) == 1){
	// 					$class_assignment_id = $class_assignment_ids[0];
	// 				} else if(count($class_assignment_ids) > 1){
	// 					$submitAssignment = EduAssignmentSubmission_Teacher::valid()
	// 						->where('created_by',$student->student_id)
	// 						->whereIn('assignment_id',$class_assignment_ids)
	// 						->orderBy('mark', 'desc')
	// 						->first();

	// 					if(!empty($submitAssignment)){
	// 						$class_assignment_id = $submitAssignment->assignment_id;

	// 					}else{
	// 						$class_assignment_id = $class_assignment_ids[0];
	// 					}

	// 				}
					
	// 				if(isset($class_assignment_id)){

	// 					EduStudentWiseClassAssignment_Teacher::create([
	// 						'student_id'              => $student->student_id,
	// 						'batch_id'                => $batch_id,
	// 						'course_id'               => $batch_info->course_id,
	// 						'assign_batch_classes_id' => $assign_classes->id,
	// 						'class_assignment_id'     => $class_assignment_id,
	// 					]);
	// 				}

	// 			}

	// 		}
	// 		$output['messege'] = 'Throw has been created';
	// 		$output['msgType'] = 'success';
	// 	} else {
	// 		$output['messege'] = 'Failed';
	// 		$output['msgType'] = 'danger';
	// 	}
	// 	return redirect()->back()->with($output);
	// }

}
