<?php

namespace App\Http\Controllers\Teacher;

use DB;
use Str;
use Auth;
use File;
use Helper;
use Validator;
use Illuminate\Http\Request;
use App\Models\EduStudent_Teacher;
use App\Http\Controllers\Controller;
use App\Models\EduAssignBatch_Teacher;
use App\Models\EduActivityNotify_Teacher;
use App\Models\EduClassAssignments_Teacher;
use App\Models\EduAssignmentComment_Teacher;
use App\Models\EduStudentPerformance_Teacher;
use App\Models\EduAssignmentSubmission_Teacher;

class ImproveAssignmentController extends Controller
{
    public function index()
    {
        $data['improveStudents'] = EduAssignmentSubmission_Teacher::join('edu_class_assignments', 'edu_class_assignments.id', '=', 'edu_assignment_submissions.assignment_id')
            ->leftJoin('edu_assignment_submission_attachments', 'edu_assignment_submission_attachments.assignment_submission_id', '=', 'edu_assignment_submissions.id')
            ->join('edu_assign_batch_classes', 'edu_assign_batch_classes.id', '=', 'edu_class_assignments.assign_batch_class_id')
            ->join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
            ->join('edu_assign_batches', 'edu_assign_batches.id', '=', 'edu_class_assignments.batch_id')
            ->join('edu_courses', 'edu_courses.id', '=', 'edu_class_assignments.course_id')
            ->join('users', 'users.id', '=', 'edu_assignment_submissions.created_by')
            ->select('edu_assignment_submissions.id', 'edu_assignment_submissions.submission_date', 'edu_assignment_submission_attachments.file_name', 'edu_assignment_submission_attachments.extention', 'edu_assignment_submission_attachments.size', 'edu_class_assignments.title', 'edu_class_assignments.id as assignment_id', 'edu_course_assign_classes.class_name', 'edu_courses.course_name', 'edu_assign_batches.batch_no', 'users.name', 'users.phone')
            ->where('edu_assignment_submissions.is_improve', 1) // 1=Improvement
            ->where('edu_assignment_submissions.mark_by', 0) // Mark Not Given
            ->where('edu_assignment_submissions.valid', 1)
            ->get();
        return view('teacher.improveAssignment.listData', $data);
    }

    public function improveAssignmentMark(Request $request)
    {
        $data['submission_id'] = $submission_id = $request->submission_id;
        $data['submission_info'] = $submission_info = EduAssignmentSubmission_Teacher::valid()->find($submission_id);
        $data['student_name'] = EduStudent_Teacher::valid()->find($submission_info->created_by)->name;

        return view('teacher.improveAssignment.giveMark', $data);
    }

    public function improveAssignmentMarkSave(Request $request)
    {
        $submission_id = $request->submission_id;
        $submission_info = EduAssignmentSubmission_Teacher::valid()->find($submission_id);
        $data['assignment_info'] = $assignment_info = EduClassAssignments_Teacher::valid()->find($submission_info->assignment_id);
        $batch_info = EduAssignBatch_Teacher::valid()->find($assignment_info->batch_id);

        $mainFile = $request->attachment;
        $mark = $request->mark;
        $comment = $request->comment;
        $authId = Auth::guard('teacher')->id();
        $today_date = date('Y-m-d');
        $current_time = date('H:i:s');

        $validator = Validator::make($request->all(), [
            'mark'    => 'required',
            'comment' => 'required',
        ]);

        $assignment_comment_exits = EduAssignmentComment_Teacher::valid()
            ->where('assignment_submission_id', $submission_id)
            ->where('class_assignments_id', $assignment_info->id)
            ->where('student_id', $submission_info->created_by)
            ->first();

        if ($batch_info->teacher_id == $authId) {
            if ($validator->passes() && empty($assignment_comment_exits)) {
                DB::beginTransaction();
                if(isset($mainFile)){
                    
                    $validPath = 'uploads/assignment/teacherComment';
                    $uploadResponse = Helper::getUploadedFileName($mainFile, $validPath);
    
                    if($uploadResponse['status'] == 1){
    
                        EduAssignmentComment_Teacher::create([
                            'assignment_submission_id'  => $submission_info->id,
                            'class_assignments_id'      => $assignment_info->id,
                            'batch_id'                  => $assignment_info->batch_id,
                            'course_id'                 => $assignment_info->course_id,
                            'assign_batch_class_id'     => $assignment_info->assign_batch_class_id,
                            'student_id'                => $submission_info->created_by,
                            'comment'                   => $comment,
                            'file_name'                 => $uploadResponse['file_name'],
                            'file_original_name'        => $uploadResponse['file_original_name'],
                            'size'                      => $uploadResponse['file_size'],
                            'extention'                 => $uploadResponse['file_extention'],
                        ]);
    
        
                        $output['messege'] = 'Marking has been Submitted';
                        $output['msgType'] = 'success';
        
                    }else{
                        $output['messege'] = $uploadResponse['errors'];
                        $output['msgType'] = 'danger';
                    }
                    
                }else{
                    EduAssignmentComment_Teacher::create([
                        'assignment_submission_id'  => $submission_info->id,
                        'class_assignments_id'      => $assignment_info->id,
                        'batch_id'                  => $assignment_info->batch_id,
                        'course_id'                 => $assignment_info->course_id,
                        'assign_batch_class_id'     => $assignment_info->assign_batch_class_id,
                        'student_id'                => $submission_info->created_by,
                        'comment'                   => $comment,
                    ]);
    
                    $output['messege'] = 'Marking has been Submitted';
                    $output['msgType'] = 'success';
                }
                // 10% MARK DEDUCTION
                if($mark > 0) {
                    $assignment_percentage = ($mark * 90) / 100;
                    $final_assignment_progress = round($assignment_percentage, 2);
                } else {
                    $final_assignment_progress = 0;
                }
                EduAssignmentSubmission_Teacher::find($submission_id)->update([
                    'mark'    => $final_assignment_progress,
                    'mark_by' => $authId
                ]);

                // PERFORMANCE TABLE UPDATE
                $exist_performance_data = EduStudentPerformance_Teacher::valid()
                    ->where('student_id', $submission_info->created_by)
                    ->where('course_id', $assignment_info->course_id)
                    ->where('batch_id', $assignment_info->batch_id)
                    ->where('assign_batch_classes_id', $assignment_info->assign_batch_class_id)
                    ->first();
    
                if(!empty($exist_performance_data)) {
                    if ($exist_performance_data->assignment == 0) {
                        $exist_performance_data->update([
                            'assignment' => $final_assignment_progress
                        ]);
                    }
                }
                // END PERFORMANCE TABLE UPDATE
    
                EduActivityNotify_Teacher::create([
                    'batch_id'   => $assignment_info->batch_id,
                    'course_id'  => $assignment_info->course_id,
                    'student_id' => $submission_info->created_by,
                    'notify_date'=> $today_date,
                    'notify_time'=> $current_time,
                    'notify_type'=> 2,
                    'notify_title'=> Str::words($comment, 20, '.....'),
                    'notify_link'=> "class?class_id=$assignment_info->assign_batch_class_id&#assignments",
                    'created_type'=> 2,
                ]);
                
                DB::commit();
                return redirect()->back()->with($output);
            } else {
                if(!empty($assignment_comment_exits)){
                    $output['messege'] = 'Already submitted';
                    $output['msgType'] = 'danger';
    
                    return redirect()->back()->with($output);
    
                } else{
                    return redirect()->back()->withErrors($validator);
                }
            }
        } else {
            $output['messege'] = 'Please Logout and Try Again!!!';
            $output['msgType'] = 'danger';
            return redirect()->back()->with($output);
        }
    }
}
