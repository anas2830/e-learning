<?php

namespace App\Http\Controllers\Support;

use DB;
use Str;
use Auth;
use File;
use Helper;
use Validator;
use Illuminate\Http\Request;
use App\Models\EduFilePond_Global;
use App\Http\Controllers\Controller;
use App\Models\EduAssignBatch_Support;
use App\Models\EduActivityNotify_Support;
use App\Models\EduTakenAssignment_Support;
use App\Models\EduClassAssignments_Support;
use App\Models\EduAssignmentComment_Support;
use App\Models\EduAssignmentComplain_Support;
use App\Models\EduStudentPerformance_Support;
use App\Models\EduAssignmentDiscussion_Support;
use App\Models\EduAssignmentSubmission_Support;
use App\Models\EduAssignmentSubmission_Reviewer;
use App\Models\EduClassAssignmentAttachments_Support;
use App\Models\EduAssignmentCommentAttachments_Support;
use App\Models\EduAssignmentSubmissionAttachment_Support;

class CheckAssignmentController extends Controller
{
    public function checkAssignmentList()
    {
        $authId = Auth::guard('support')->id();

        $data['taken_assignments'] = $taken_assignments = EduTakenAssignment_Support::leftJoin('edu_assignment_complains', 'edu_assignment_complains.taken_assignment_id', '=', 'edu_taken_assignments.id')
            ->select('edu_taken_assignments.*', 'edu_assignment_complains.id as complain_id', 'edu_assignment_complains.complain_status')
            ->where('edu_taken_assignments.taken_by_type', 1)
            ->where('edu_taken_assignments.created_by', $authId)
            ->latest()->get();
        foreach ($taken_assignments as $key => $assignment) {
            $assignment->total_discussion = EduAssignmentDiscussion_Support::valid()->where('taken_assignment_id', $assignment->id)->count();
        }
        $already_taken_submission_ids = EduTakenAssignment_Support::valid()->get()->pluck('assignment_submission_id');
        $running_batch_ids = EduAssignBatch_Support::valid()->where('complete_status', 0)->where('active_status', 1)->pluck('id');
        $data['available_submitted_assignment_qty'] = EduAssignmentSubmission_Support::join('edu_class_assignments', 'edu_class_assignments.id', '=', 'edu_assignment_submissions.assignment_id')
            ->join('edu_assign_batch_classes', 'edu_assign_batch_classes.id', '=', 'edu_class_assignments.assign_batch_class_id')
            ->join('edu_assign_batch_students', 'edu_assign_batch_students.student_id', '=', 'edu_assignment_submissions.created_by')
            ->select('edu_assignment_submissions.*')
            ->whereNotIn('edu_assignment_submissions.id', $already_taken_submission_ids)
            ->whereIn('edu_class_assignments.batch_id', $running_batch_ids)
            ->where('edu_assign_batch_classes.complete_status', 1) //1=Completed
            ->where('edu_assignment_submissions.mark_by', 0)
            ->where('edu_assignment_submissions.valid', 1)
            ->where('edu_assign_batch_students.valid', 1)
            ->where('edu_class_assignments.valid', 1)
            ->count();
        
        return view('support.checkAssignment.takenAssignmentList', $data);
    }
    public function takeStdAssignments(Request $request)
    {
        $data['total_qty'] = $request->total_qty;
        return view('support.checkAssignment.assignmentTakingForm', $data);
    }

    public function getRunningBatch(Request $request)
    {
        $data['running_batches'] = $running_batches = EduAssignBatch_Support::valid()->where('complete_status', 0)->where('active_status', 1)->get();
        if (count($running_batches) > 0) {
            foreach ($running_batches as $key => $batch) {
                $batch->avail_assignment_qty = self::batchWiseAvailableAssignmentQty($batch->id);
            }
        }
        return view('support.checkAssignment.getRunningBatch', $data);
    }

    public function getAvailableAssignments(Request $request)
    {
        $batch_id = $request->batch_id;
        $already_taken_submission_ids = EduTakenAssignment_Support::valid()->get()->pluck('assignment_submission_id');

        $data['available_submitted_assignments'] = EduAssignmentSubmission_Support::join('edu_class_assignments', 'edu_class_assignments.id', '=', 'edu_assignment_submissions.assignment_id')
                ->join('users', 'users.id', '=', 'edu_assignment_submissions.created_by')
                ->join('edu_assign_batch_classes', 'edu_assign_batch_classes.id', '=', 'edu_class_assignments.assign_batch_class_id')
                ->join('edu_assign_batch_students', 'edu_assign_batch_students.student_id', '=', 'edu_assignment_submissions.created_by')
                ->select('edu_assignment_submissions.*', 'users.name as student_name', 'edu_class_assignments.assign_batch_class_id')
                ->whereNotIn('edu_assignment_submissions.id', $already_taken_submission_ids)
                ->where('edu_assignment_submissions.mark_by', 0)
                ->where('edu_assignment_submissions.valid', 1)
                ->where('edu_assign_batch_students.valid', 1)
                ->where(function($query) use ($batch_id){
                    if($batch_id != 0){
                        $query->where('edu_class_assignments.batch_id', $batch_id);
                    }
                })
                ->where('edu_assign_batch_classes.complete_status', 1) //1=Completed
                ->where('edu_class_assignments.valid', 1)
                ->orderBy('edu_assignment_submissions.created_by', 'asc')
                ->get();
        return view('support.checkAssignment.getAvailableAssignments', $data);
    }

    public static function batchWiseAvailableAssignmentQty($batch_id)
    {
        $already_taken_submission_ids = EduTakenAssignment_Support::valid()->get()->pluck('assignment_submission_id');

        $available_submitted_assignments_qty = EduAssignmentSubmission_Support::join('edu_class_assignments', 'edu_class_assignments.id', '=', 'edu_assignment_submissions.assignment_id')
                ->join('users', 'users.id', '=', 'edu_assignment_submissions.created_by')
                ->join('edu_assign_batch_classes', 'edu_assign_batch_classes.id', '=', 'edu_class_assignments.assign_batch_class_id')
                ->join('edu_assign_batch_students', 'edu_assign_batch_students.student_id', '=', 'edu_assignment_submissions.created_by')
                ->select('edu_assignment_submissions.*', 'users.name as student_name', 'edu_class_assignments.assign_batch_class_id')
                ->whereNotIn('edu_assignment_submissions.id', $already_taken_submission_ids)
                ->where('edu_assignment_submissions.mark_by', 0)
                ->where('edu_assignment_submissions.valid', 1)
                ->where('edu_assign_batch_students.valid', 1)
                ->where(function($query) use ($batch_id){
                    if($batch_id != 0){
                        $query->where('edu_class_assignments.batch_id', $batch_id);
                    }
                })
                ->where('edu_assign_batch_classes.complete_status', 1) //1=Completed
                ->where('edu_class_assignments.valid', 1)
                ->count();
        return $available_submitted_assignments_qty;
    }

    public function takeStdAssignmentAction(Request $request)
    {
        $output = array();
        $authId = Auth::guard('support')->id();
        $my_taken_assignmens_qty = EduTakenAssignment_Support::valid()
            ->where('taken_by_type', 1)
            ->where('review_status', 1) //1=pending
            ->where('created_by', $authId)
            ->count();
        if ($my_taken_assignmens_qty == 0) {
            $batch_filter_type = $request->batch_filter_type;
            $student_selection_type = $request->student_selection_type;
            if (isset($batch_filter_type) && $batch_filter_type == 1) { //1=Selected Batch
                $batch_id = $request->batch_id;
            } else { //2=All Batch
                $batch_id = 0;
            }
            $limit_quantity = $request->quantity;

            if (isset($student_selection_type) && $student_selection_type == 2) { //2=Selected submission ids
                $std_assignment_submission_ids = $request->std_assignment_submission_ids;
            } else { //1=All submission ids
                $std_assignment_submission_ids = [];
            }
            
            $already_taken_submission_ids = EduTakenAssignment_Support::valid()->get()->pluck('assignment_submission_id');
        
            $available_submitted_assignments_qry = EduAssignmentSubmission_Support::join('edu_class_assignments', 'edu_class_assignments.id', '=', 'edu_assignment_submissions.assignment_id')
                ->join('edu_assign_batch_students', 'edu_assign_batch_students.student_id', '=', 'edu_assignment_submissions.created_by')
                ->join('edu_assign_batch_classes', 'edu_assign_batch_classes.id', '=', 'edu_class_assignments.assign_batch_class_id')
                ->select('edu_assignment_submissions.*', 'edu_class_assignments.batch_id', 'edu_class_assignments.course_id', 'edu_class_assignments.assign_batch_class_id')
                ->whereNotIn('edu_assignment_submissions.id', $already_taken_submission_ids)
                ->where('edu_assign_batch_classes.complete_status', 1) //1=Completed
                ->where('edu_assignment_submissions.mark_by', 0)
                ->where('edu_assignment_submissions.valid', 1)
                ->where('edu_assign_batch_students.valid', 1)
                ->where('edu_class_assignments.valid', 1)
                ->where(function($query) use ($batch_id, $std_assignment_submission_ids){
                    if($batch_id != 0){
                        $query->where('edu_class_assignments.batch_id', $batch_id);
                    }
                    if(count($std_assignment_submission_ids) > 0){
                        $query->whereIn('edu_assignment_submissions.id', $std_assignment_submission_ids);
                    }
                });
                
            if (isset($limit_quantity)) {
                $available_submitted_assignments = $available_submitted_assignments_qry->limit($limit_quantity)->get();
            } else {
                $available_submitted_assignments = $available_submitted_assignments_qry->get();
            }
            $taken_date = date("Y-m-d");
            $expire_date = date('Y-m-d', strtotime("+1 day"));
            $taken_time = date("H:i");
            $expire_time = date("H:i", strtotime("+24 hours $taken_time"));
            
            if (count($available_submitted_assignments) > 0) {
                foreach ($available_submitted_assignments as $key => $available_assignment) {
                    EduTakenAssignment_Support::create([
                        'batch_id'                 => $available_assignment->batch_id,
                        'course_id'                => $available_assignment->course_id,
                        'assign_batch_class_id'    => $available_assignment->assign_batch_class_id,
                        'class_assignment_id'      => $available_assignment->assignment_id,
                        'assignment_submission_id' => $available_assignment->id,
                        'student_id'               => $available_assignment->created_by,
                        'taken_date'               => $taken_date,
                        'taken_time'               => $taken_time,
                        'expire_date'              => $expire_date,
                        'expire_time'              => $expire_time,
                        'review_status'            => 1, //1=pending
                        'taken_by_type'            => 1, //1=Support
                    ]);
                }
                $output['messege'] = 'Assignment has been Taken';
                $output['msgType'] = 'success';
            } else {
                $output['messege'] = 'You have not any available assignment!!';
                $output['msgType'] = 'danger';
            }
        } else {
            $output['messege'] = 'Please at first Review previous assignments';
            $output['msgType'] = 'danger';
        }
        return redirect()->back()->with($output);
    }

    // ASSIGNMENT REVIEW TAB START
    public function reviewIndex(Request $request)
    {
        $data['assignment_subm_taken_id'] = $request->assignment_subm_taken_id;
        return view('support.checkAssignment.assignmentReview.reviewIndex', $data);
    }
    public function reviewInstruction(Request $request)
    {
        return view('support.checkAssignment.assignmentReview.instruction');
    }
    public function assignmentDetails(Request $request)
    {
        $authId = Auth::guard('support')->id();
        $data['taken_assignment_id'] = $taken_assignment_id = $request->taken_assignment_id;
        $taken_assignment_info = EduTakenAssignment_Support::valid()->where('created_by', $authId)->where('taken_by_type', 1)->find($taken_assignment_id);
        if (!empty($taken_assignment_info)) {
            $data['is_assignment_taken'] = true;

            $data['submitted_assignment'] = $submitted_assignment = EduClassAssignments_Support::join('edu_taken_assignments', 'edu_taken_assignments.class_assignment_id', '=', 'edu_class_assignments.id')
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
            $data['assignment_attachment'] = EduClassAssignmentAttachments_Support::valid()->where('class_assignment_id', $taken_assignment_info->class_assignment_id)->first();
            $data['submitted_attachment'] = EduAssignmentSubmissionAttachment_Support::valid()->where('assignment_submission_id', $taken_assignment_info->assignment_submission_id)->first();
            // dd($data['submitted_attachment']);

            $data['assignment_comment_exits'] = EduAssignmentComment_Support::valid()
                ->where('assignment_submission_id', $taken_assignment_info->assignment_submission_id)
                ->where('class_assignments_id', $taken_assignment_info->class_assignment_id)
                ->where('student_id', $taken_assignment_info->student_id)
                ->first();
            
        } else {
            $data['is_assignment_taken'] = false;
        }
        
        return view('support.checkAssignment.assignmentReview.assignments', $data);
    }
    public function assignmentMarking(Request $request)
    {
        $authId = Auth::guard('support')->id();
        $today_date = date('Y-m-d');
        $current_time = date('H:i:s');
        $taken_assignment_id = $request->taken_assignment_id;
        $mainFiles = $request->attachment;
        // $mainFile = json_decode($mainFile);
        // echo "<pre>";
        // print_r($mainFile);
        // // echo $mainFile->file_name;

        // die();
        $taken_assignment_info = EduTakenAssignment_Support::valid()->where('taken_by_type', 1)->find($taken_assignment_id);
        $validator = Validator::make($request->all(), [
            'mark'       => 'required',
            'comment'    => 'required',
            'attachment' => 'required',
        ]);
        $assignment_comment_exits = EduAssignmentComment_Support::valid()
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
                    'mark_by_type' => 2 //2 = Support Manager
                ]);

                $reviewerComment = EduAssignmentComment_Support::create([
                    'assignment_submission_id'  => $taken_assignment_info->assignment_submission_id,
                    'class_assignments_id'      => $taken_assignment_info->class_assignment_id,
                    'batch_id'                  => $taken_assignment_info->batch_id,
                    'course_id'                 => $taken_assignment_info->course_id,
                    'assign_batch_class_id'     => $taken_assignment_info->assign_batch_class_id,
                    'student_id'                => $taken_assignment_info->student_id,
                    'comment'                   => $request->comment,
                    'comment_by_type'           => 2 //2 = Support Manager
                ]);

                if(!empty($mainFiles)){
                    foreach ($mainFiles as $key => $mainFile) {
                        $mainFile = json_decode($mainFile);
                        $temporaryUploadedFile = EduFilePond_Global::valid()->where('file_name', $mainFile->file_name)->first();
                        if ($temporaryUploadedFile) {

                            File::move(public_path($temporaryUploadedFile->folder_name.'/'.$temporaryUploadedFile->file_name), public_path('uploads/assignment/teacherComment/'.$temporaryUploadedFile->file_name));
                            File::move(public_path($temporaryUploadedFile->folder_name.'/thumb/'.$temporaryUploadedFile->file_name), public_path('uploads/assignment/teacherComment/thumb/'.$temporaryUploadedFile->file_name));
                            
                            EduAssignmentCommentAttachments_Support::create([
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
                }
    
                // PERFORMANCE TABLE UPDATE
                $exist_performance_data = EduStudentPerformance_Support::valid()
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

                $taken_assignment_info->update([
                    'review_status' => 2 //2=Reviewed
                ]);

                EduActivityNotify_Support::create([
                    'batch_id'       => $taken_assignment_info->batch_id,
                    'course_id'      => $taken_assignment_info->course_id,
                    'student_id'     => $taken_assignment_info->student_id,
                    'notify_date'    => $today_date,
                    'notify_time'    => $current_time,
                    'notify_type'    => 2,
                    'notify_title'   => Str:: words($request->comment, 20, '.....'),
                    'notify_link'    => "class?class_id=$taken_assignment_info->assign_batch_class_id&#assignments",
                    'created_type'   => 4, //4 = Support Manager
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
        } else {
            $output['messege'] = 'You have no access!!!';
            $output['msgType'] = 'danger';
            $output['status'] = 0;
            return response($output);
        }
    }

    public function assignmentDiscussion(Request $request)
    {
        $authId = Auth::guard('support')->id();
        $data['taken_assignment_id'] = $taken_assignment_id = $request->taken_assignment_id;
        $taken_assignment_info = EduTakenAssignment_Support::valid()->where('created_by', $authId)->where('taken_by_type', 1)->find($taken_assignment_id);
        if (!empty($taken_assignment_info)) {
            $data['is_assignment_taken'] = true;
            $data['all_discussions'] = EduAssignmentDiscussion_Support::valid()->where('taken_assignment_id', $request->taken_assignment_id)->get();
        } else {
            $data['is_assignment_taken'] = false;
        }
        return view('support.checkAssignment.assignmentReview.discussion', $data);
    }
    public function discussionMsgAjax(Request $request)
    {
        $data['all_discussions'] = EduAssignmentDiscussion_Support::valid()->where('taken_assignment_id', $request->taken_assignment_id)->get();
        return view('support.checkAssignment.assignmentReview.discussionMsgAjax', $data);
    }
    public function discussionMsgSend(Request $request)
    {
        $taken_assignment_id = $request->taken_assignment_id;
        $output = array();
        $validator = Validator::make($request->all(), [
            'message' => 'required'
        ]);
        if ($validator->passes()) {
            $last_msg = EduAssignmentDiscussion_Support::valid()->where('taken_assignment_id', $taken_assignment_id)->latest()->first();
            EduAssignmentDiscussion_Support::create([
                'taken_assignment_id' => $taken_assignment_id,
                'msg_sl'              => empty($last_msg) ? 1 : $last_msg->msg_sl + 1,
                'message'             => $request->message,
                'msg_by_type'         => 3, //3 = Support
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

    // COMPLAIN VIEW & UPDATE MARK
    public function viewStdComplain(Request $request)
    {
        $authId = Auth::guard('support')->id();
        $data['complain_id'] = $complain_id = $request->complain_id;
        $taken_id = $request->taken_id;
        $data['assignment_complain'] = $assignment_complain = EduAssignmentComplain_Support::valid()
            ->where('complain_to', $authId)
            ->where('complain_to_type', 1) //1=Support
            ->find($complain_id);
        if (!empty($assignment_complain)) {
            $data['updateAccess'] = true;
            $taken_assignment_info = EduTakenAssignment_Support::valid()->find($taken_id);
            $data['assignment_submission_info'] = EduAssignmentSubmission_Support::valid()->find($taken_assignment_info->assignment_submission_id);
        } else {
            $data['updateAccess'] = false;
        }
        return view('support.checkAssignment.updateComplainMark', $data);
    }
    public function updateComplaintMark(Request $request)
    {
        $complain_id = $request->complain_id;
        $authId = Auth::guard('support')->id();
        $validator = Validator::make($request->all(), [
            'mark' => 'required'
        ]);
        if ($validator->passes()) {
            DB::beginTransaction();
            $assignment_complain = EduAssignmentComplain_Support::valid()->where('complain_to', $authId)->where('complain_to_type', 1)->find($complain_id);
            if (!empty($assignment_complain)) {
                $assignment_taken_info = EduTakenAssignment_Support::valid()->where('review_status', 3)->find($assignment_complain->taken_assignment_id);
        
                EduAssignmentSubmission_Support::find($assignment_taken_info->assignment_submission_id)->update([
                    'mark'         => $request->mark,
                    'mark_by'      => $authId,
                    'mark_by_type' => 2, //2 = Support
                    'mark_from'    => 1, //1 = Revision
                ]);
                
                // PERFORMANCE TABLE UPDATE
                $exist_performance_data = EduStudentPerformance_Support::valid()
                    ->where('student_id', $assignment_taken_info->student_id)
                    ->where('course_id', $assignment_taken_info->course_id)
                    ->where('batch_id', $assignment_taken_info->batch_id)
                    ->where('assign_batch_classes_id', $assignment_taken_info->assign_batch_class_id)
                    ->first();
    
                if(!empty($exist_performance_data)) {
                    $exist_performance_data->update([
                        'assignment' => $request->mark
                    ]);
                }
                
                $assignment_complain->update([
                    'complain_status' => 2
                ]);
                $assignment_taken_info->update([
                    'review_status' => 2
                ]);

                $output['messege'] = 'Mark update successfully !!';
                $output['msgType'] = 'success';
            } else {
                $output['messege'] = 'Mark update Failed !!';
                $output['msgType'] = 'danger';
            }
            DB::commit();
    
            return redirect()->back()->with($output);
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }
}
