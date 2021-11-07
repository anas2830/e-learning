<?php

namespace App\Http\Controllers\Student;

use DB;
use Str;
use Auth;
use File;
use Helper;
use Validator;
use Illuminate\Http\Request;
use App\Models\EduFilePond_Global;
use App\Models\EduAssignBatch_User;
use App\Http\Controllers\Controller;
use App\Models\EduActivityNotify_User;
use App\Models\EduTakenAssignment_User;
use App\Models\EduClassAssignments_User;
use App\Models\EduAssignmentComment_User;
use App\Models\EduAssignBatchClasses_User;
use App\Models\EduAssignBatchStudent_User;
use App\Models\EduStudentPerformance_User;
use App\Models\EduAssignmentDiscussion_User;
use App\Models\EduAssignmentSubmission_User;
use App\Models\EduAssignmentSubmission_Reviewer;
use App\Models\EduClassAssignmentAttachments_User;
use App\Models\EduStudentWiseClassAssignment_User;
use App\Models\EduAssignmentCommentAttachments_User;
use App\Http\Controllers\Student\ClassroomController;
use App\Models\EduAssignmentSubmissionAttachment_User;

class AssignmentReviewController extends Controller
{
    //available assignment
    public function availableAssignment(Request $request)
    {
        $authId = Auth::id();
        $data['student_course_info'] = $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();

        if(!empty($student_course_info)){   
            $data['taken_assignments'] = $taken_assignments = EduTakenAssignment_User::valid()
                ->where('course_id', $student_course_info->course_id)
                ->where('created_by', $authId)
                ->where('taken_by_type', 0) //0=Reviewer (std)
                ->latest()
                ->get();
            foreach ($taken_assignments as $key => $assignment) {
                $assignment->total_discussion = EduAssignmentDiscussion_User::valid()->where('taken_assignment_id', $assignment->id)->count();
            }
            $my_final_progress = ClassroomController::getStudentFinalProgress($authId);
            if ($my_final_progress > 75) {
                $data['eligible_for_take'] = true;
                // FOR CHECKING HOW MANY ASSIGNMENT AVAILABLE FOR REVIEW
                $data['available_submit_assignment'] = ClassroomController::getAvailableSubmitAssignment($student_course_info->batch_id, $student_course_info->course_id);
            } else {
                $data['eligible_for_take'] = false;
            }
        } else {
            //aikahne error page dakano jabe
            $data['taken_assignments'] = [];
        }

        return view('student.assignmentReview.availableAssignment.listData', $data);
    }

    public function takenAvailableAssignment(Request $request)
    {
        $data['course_id'] = $request->course_id;
        $data['batch_id'] = $request->batch_id;
        return view('student.assignmentReview.availableAssignment.takenAssignment', $data);
    }

    public function applyAvailableAssignment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number_of_assignment'     => 'required',
        ]);

        $authId = Auth::id();

        if ($validator->passes()) {
            $course_id = $request->course_id;
            $batch_id = $request->batch_id;

            $number_of_assignment = $request->number_of_assignment;
            if ($number_of_assignment <= 5) {
                //count availabe assignments
                $my_complete_classes = EduAssignBatchClasses_User::valid()
                    ->where('batch_id',$batch_id)
                    ->where('course_id',$course_id)
                    ->where('complete_status', 1)
                    ->count();
    
            
                $another_batches = EduAssignBatch_User::valid()
                    ->where('course_id', $course_id)
                    ->where('id', '!=', $batch_id)
                    ->get(['id']);
    
                $get_assign_batch_classes = [];
                if(count($another_batches) > 0){
                    foreach($another_batches as $Key => $batches){
                        $get_assign_batch_classes[]  =  EduAssignBatchClasses_User::valid()
                            ->where('course_id', $course_id)
                            ->where('batch_id', $batches['id'])
                            ->where('complete_status', 1) //1=Completed
                            ->limit($my_complete_classes)
                            ->get(['id'])->toArray();
                    }
                }
    
                $all_assign_batch_classes = array_reduce($get_assign_batch_classes, 'array_merge', array());
                $all_assign_batch_classes_ids = [];
    
                if(count($all_assign_batch_classes) > 0){
                    foreach($all_assign_batch_classes as $batch_class){
                        $all_assign_batch_classes_ids[] = $batch_class['id'];
                    }
                }
    
                $all_taken_submission_ids = EduTakenAssignment_User::valid()->get()->pluck('assignment_submission_id');

                $take_submission_data = EduAssignmentSubmission_User::join('edu_class_assignments', 'edu_class_assignments.id', '=', 'edu_assignment_submissions.assignment_id')
                    ->join('edu_assign_batch_students', 'edu_assign_batch_students.student_id', '=', 'edu_assignment_submissions.created_by')
                    ->select('edu_assignment_submissions.*', 'edu_class_assignments.batch_id', 'edu_class_assignments.course_id', 'edu_class_assignments.assign_batch_class_id', 'edu_class_assignments.id as class_assignment_id', 'edu_assign_batch_students.student_id')
                    ->where('edu_assignment_submissions.mark_by', 0)
                    ->whereIn('edu_class_assignments.assign_batch_class_id', $all_assign_batch_classes_ids)
                    ->whereNotIn('edu_assignment_submissions.id', $all_taken_submission_ids)
                    ->where('edu_assignment_submissions.valid', 1)
                    ->where('edu_assign_batch_students.valid', 1)
                    ->limit($number_of_assignment)
                    ->orderBy('edu_assignment_submissions.id', 'asc')
                    ->get();
    
                $taken_date = date("Y-m-d");
                $expire_date = date('Y-m-d', strtotime("+1 day"));
                $taken_time = date("H:i");
                $expire_time = date("H:i", strtotime("+24 hours $taken_time"));
    
                $existing_taken_assignment = EduTakenAssignment_User::valid()
                    ->where('created_by',$authId)
                    ->where('taken_by_type', 0) //0=Reviewer (std)
                    ->where('review_status', 1) //1=Pending
                    ->count();
                // echo "<pre>";
                // print_r($take_submission_data);
                // die();
                if($existing_taken_assignment == 0){
    
                    if(count($take_submission_data) == $number_of_assignment){
                        
                        foreach($take_submission_data as $key => $take_data){

                            // $isHisAssignment = EduStudentWiseClassAssignment_User::valid()
                            //     ->where('batch_id', $take_data->batch_id)
                            //     ->where('course_id', $take_data->course_id)
                            //     ->where('student_id', $take_data->student_id)
                            //     ->where('class_assignment_id', $take_data->class_assignment_id)
                            //     ->where('assign_batch_classes_id', $take_data->assign_batch_class_id)
                            //     ->first();
                            // if (!empty($isHisAssignment)) {
                                EduTakenAssignment_User::create([
                                    'batch_id'                 => $take_data->batch_id,
                                    'course_id'                => $take_data->course_id,
                                    'assign_batch_class_id'    => $take_data->assign_batch_class_id,
                                    'class_assignment_id'      => $take_data->class_assignment_id,
                                    'assignment_submission_id' => $take_data->id,
                                    'student_id'               => $take_data->student_id,
                                    'taken_date'               => $taken_date,
                                    'taken_time'               => $taken_time,
                                    'expire_date'              => $expire_date,
                                    'expire_time'              => $expire_time,
                                    'review_status'            => 1, //1=pending
                                ]);
                            // }
                        }
                        
                        $output['messege'] = 'Assignment has been Taken';
                        $output['msgType'] = 'success';
                    
                    } else {
                        $output['messege'] = 'Input Quantity out of available assignment';
                        $output['msgType'] = 'danger';
                    }
                } else {
                    $output['messege'] = 'Please at first Review previous assignments';
                    $output['msgType'] = 'danger';
                }
            } else {
                $output['messege'] = 'You can take 5 assignment only for check!!';
                $output['msgType'] = 'danger';
            }
            return redirect()->back()->with($output);
            
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    public function reviewIndex(Request $request)
    {
        $data['assignment_subm_taken_id'] = $request->assignment_subm_taken_id;
        return view('student.assignmentReview.reviewIndex', $data);
    }

    public function reviewInstruction(Request $request)
    {
        return view('student.assignmentReview.instruction');
    }
    public function assignmentDetails(Request $request)
    {
        $authId = Auth::id();
        $data['taken_assignment_id'] = $taken_assignment_id = $request->taken_assignment_id;
        $taken_assignment_info = EduTakenAssignment_User::valid()->where('created_by', $authId)->where('taken_by_type', 0)->find($taken_assignment_id);
        if (!empty($taken_assignment_info)) {
            $data['is_assignment_taken'] = true;

            $data['submitted_assignment'] = $submitted_assignment = EduClassAssignments_User::join('edu_taken_assignments', 'edu_taken_assignments.class_assignment_id', '=', 'edu_class_assignments.id')
                ->join('edu_assignment_submissions', 'edu_assignment_submissions.id', '=', 'edu_taken_assignments.assignment_submission_id')
                ->join('edu_teachers', 'edu_teachers.id', '=', 'edu_class_assignments.created_by')
                ->select('edu_class_assignments.*', 'edu_teachers.name as teacher_name', 'edu_assignment_submissions.comment as std_comment', 'edu_assignment_submissions.mark_by', 'edu_assignment_submissions.mark', 'edu_taken_assignments.id as taken_assignment_id')
                ->where('edu_class_assignments.id', $taken_assignment_info->class_assignment_id)
                ->where('edu_class_assignments.batch_id', $taken_assignment_info->batch_id)
                ->where('edu_class_assignments.course_id', $taken_assignment_info->course_id)
                ->where('edu_class_assignments.assign_batch_class_id', $taken_assignment_info->assign_batch_class_id)
                ->where('edu_assignment_submissions.id', $taken_assignment_info->assignment_submission_id)
                ->where('edu_assignment_submissions.created_by', $taken_assignment_info->student_id)
                ->where('edu_taken_assignments.id', $taken_assignment_info->id)
                ->first();
            $data['assignment_attachment'] = EduClassAssignmentAttachments_User::valid()->where('class_assignment_id', $taken_assignment_info->class_assignment_id)->first();
            $data['submitted_attachment'] = EduAssignmentSubmissionAttachment_User::valid()->where('assignment_submission_id', $taken_assignment_info->assignment_submission_id)->first();
            // dd($data['submitted_attachment']);

            $data['assignment_comment_exits'] = EduAssignmentComment_User::valid()
                ->where('assignment_submission_id', $taken_assignment_info->assignment_submission_id)
                ->where('class_assignments_id', $taken_assignment_info->class_assignment_id)
                ->where('student_id', $taken_assignment_info->student_id)
                ->first();
            
        } else {
            $data['is_assignment_taken'] = false;
        }
        
        return view('student.assignmentReview.assignments', $data);
    }
    public function assignmentMarking(Request $request)
    {
        $authId = Auth::id();
        $today_date = date('Y-m-d');
        $current_time = date('H:i:s');
        $taken_assignment_id = $request->taken_assignment_id;
        $mainFiles = $request->attachment;
        // $mainFile = json_decode($mainFile);
        // echo "<pre>";
        // print_r($mainFile);
        // // echo $mainFile->file_name;

        // die();
        $taken_assignment_info = EduTakenAssignment_User::valid()->find($taken_assignment_id);
        $validator = Validator::make($request->all(), [
            'mark'       => 'required',
            'comment'    => 'required',
            'attachment' => 'required',
        ]);
        $assignment_comment_exits = EduAssignmentComment_User::valid()
            ->where('assignment_submission_id', $taken_assignment_info->assignment_submission_id)
            ->where('class_assignments_id', $taken_assignment_info->class_assignment_id)
            ->where('student_id', $taken_assignment_info->student_id)
            ->first();

        if ($taken_assignment_info->created_by == $authId) { 
            if ($validator->passes() && empty($assignment_comment_exits)) {
                DB::beginTransaction();
                EduAssignmentSubmission_Reviewer::valid()->find($taken_assignment_info->assignment_submission_id)->update([
                    'mark'         => $request->mark,
                    'mark_by'      => $authId,
                    'mark_by_type' => 1 //1 = Reviewer (Student ID)
                ]);

                $reviewerComment = EduAssignmentComment_User::create([
                    'assignment_submission_id'  => $taken_assignment_info->assignment_submission_id,
                    'class_assignments_id'      => $taken_assignment_info->class_assignment_id,
                    'batch_id'                  => $taken_assignment_info->batch_id,
                    'course_id'                 => $taken_assignment_info->course_id,
                    'assign_batch_class_id'     => $taken_assignment_info->assign_batch_class_id,
                    'student_id'                => $taken_assignment_info->student_id,
                    'comment'                   => $request->comment,
                    'comment_by_type'           => 1 //1 = Reviewer (Student ID)
                ]);

                if(!empty($mainFiles)){
                    foreach ($mainFiles as $key => $mainFile) {
                        $mainFile = json_decode($mainFile);
                        $temporaryUploadedFile = EduFilePond_Global::valid()->where('file_name', $mainFile->file_name)->first();
                        if ($temporaryUploadedFile) {

                            File::move(public_path($temporaryUploadedFile->folder_name.'/'.$temporaryUploadedFile->file_name), public_path('uploads/assignment/teacherComment/'.$temporaryUploadedFile->file_name));
                            File::move(public_path($temporaryUploadedFile->folder_name.'/thumb/'.$temporaryUploadedFile->file_name), public_path('uploads/assignment/teacherComment/thumb/'.$temporaryUploadedFile->file_name));
                            
                            EduAssignmentCommentAttachments_User::create([
                                'assignment_comment_id' => $reviewerComment->id,
                                'file_name'             => $temporaryUploadedFile->file_name,
                                'folder_name'           => $temporaryUploadedFile->folder_name,
                                'file_original_name'    => $temporaryUploadedFile->file_original_name,
                                'size'                  => $temporaryUploadedFile->size,
                                'extention'             => $temporaryUploadedFile->extention
                            ]);
                            DB::table('edu_file_ponds')->where('file_name', $mainFile->file_name)->delete();
                        }
                    }
                    // $temporaryUploadedFile = EduFilePond_Global::valid()->where('file_name', $mainFile->file_name)->first();
                    // if ($temporaryUploadedFile) {

                    //     File::move(public_path($temporaryUploadedFile->folder_name.'/'.$temporaryUploadedFile->file_name), public_path('uploads/assignment/teacherComment/'.$temporaryUploadedFile->file_name));
                    //     File::move(public_path($temporaryUploadedFile->folder_name.'/thumb/'.$temporaryUploadedFile->file_name), public_path('uploads/assignment/teacherComment/thumb/'.$temporaryUploadedFile->file_name));
                        
                    //     EduAssignmentComment_User::find($reviewerComment->id)->update([
                    //         'file_name'          => $temporaryUploadedFile->file_name,
                    //         'folder_name'        => $temporaryUploadedFile->folder_name,
                    //         'file_original_name' => $temporaryUploadedFile->file_original_name,
                    //         'size'               => $temporaryUploadedFile->size,
                    //         'extention'          => $temporaryUploadedFile->extention
                    //     ]);
                    //     DB::table('edu_file_ponds')->where('file_name', $mainFile->file_name)->delete();
                    // } else {
                    //     $output['messege'] = 'temporary file not found!';
                    //     $output['msgType'] = 'danger';
                    //     $output['status'] = 0;
                    // } 
                }
    
                // PERFORMANCE TABLE UPDATE
                $exist_performance_data = EduStudentPerformance_User::valid()
                    ->where('student_id', $taken_assignment_info->student_id)
                    ->where('course_id', $taken_assignment_info->course_id)
                    ->where('batch_id', $taken_assignment_info->batch_id)
                    ->where('assign_batch_classes_id', $taken_assignment_info->assign_batch_class_id)
                    ->first();
    
                if(!empty($exist_performance_data)) {
                    $exist_performance_data->update([
                        'assignment' => $request->mark
                    ]);
                }
                // END PERFORMANCE TABLE UPDATE
                EduTakenAssignment_User::find($taken_assignment_id)->update([
                    'review_status' => 2 //2=Reviewed
                ]);

                EduActivityNotify_User::create([
                    'batch_id'       => $taken_assignment_info->batch_id,
                    'course_id'      => $taken_assignment_info->course_id,
                    'student_id'     => $taken_assignment_info->student_id,
                    'notify_date'    => $today_date,
                    'notify_time'    => $current_time,
                    'notify_type'    => 2,
                    'notify_title'   => Str:: words($request->comment, 20, '.....'),
                    'notify_link'    => "class?class_id=$taken_assignment_info->assign_batch_class_id&#assignments",
                    'created_type'   => 3, //3 = Reviewer (Student ID)
                ]);
                
                $output['messege'] = 'Marking has been Submitted';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                DB::commit();
    
                return response($output);
    
            } else {
                if(!empty($assignment_comment_exits)){
                    $output['messege'] = 'Already submitted';
                    $output['msgType'] = 'danger';
                    $output['status'] = 0;
    
                    return response($output);
    
                } else {
                    $output['messege'] = 'All Field are Required!!!';
                    $output['msgType'] = 'danger';
                    $output['status'] = 0;
                    return response($output);
                }
            }
        }
    }

    public function assignmentDiscussion(Request $request)
    {
        $authId = Auth::id();
        $data['taken_assignment_id'] = $taken_assignment_id = $request->taken_assignment_id;
        $taken_assignment_info = EduTakenAssignment_User::valid()->where('created_by', $authId)->where('taken_by_type', 0)->find($taken_assignment_id);
        if (!empty($taken_assignment_info)) {
            $data['is_assignment_taken'] = true;
            $data['all_discussions'] = EduAssignmentDiscussion_User::valid()->where('taken_assignment_id', $request->taken_assignment_id)->get();
        } else {
            $data['is_assignment_taken'] = false;
        }
        return view('student.assignmentReview.discussion', $data);
    }
    public function discussionMsgAjax(Request $request)
    {
        $data['all_discussions'] = EduAssignmentDiscussion_User::valid()->where('taken_assignment_id', $request->taken_assignment_id)->get();
        return view('student.assignmentReview.discussionMsgAjax', $data);
    }
    public function discussionMsgSend(Request $request)
    {
        $taken_assignment_id = $request->taken_assignment_id;
        $output = array();
        $validator = Validator::make($request->all(), [
            'message' => 'required'
        ]);
        if ($validator->passes()) {
            $last_msg = EduAssignmentDiscussion_User::valid()->where('taken_assignment_id', $taken_assignment_id)->latest()->first();
            EduAssignmentDiscussion_User::create([
                'taken_assignment_id' => $taken_assignment_id,
                'msg_sl'              => empty($last_msg) ? 1 : $last_msg->msg_sl + 1,
                'message'             => $request->message,
                'msg_by_type'         => 2, //2 = Reviewer
            ]);
            $output['messege'] = 'Message has been Added';
            $output['msgType'] = 'success';
            $output['status'] = 1;
        } else {
            $output['messege'] = 'Failed! Message Fields are Required';
            $output['msgType'] = 'danger';
            $output['status'] = 0;
        }
        return response($output);
    }


}
