<?php

namespace App\Http\Controllers\Teacher;

use DB;
use Auth;
use Validator;
use Helper;

use App\Models\EduTeachers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EduAssignBatch_Teacher;
use App\Models\EduStudentExam_Teacher;
use App\Models\EduClassAssignments_Teacher;
use App\Models\EduAssignBatchClasses_Teacher;
use App\Models\EduAssignmentSubmission_Teacher;
use App\Models\EduCourseClassMaterials_Teacher;
use App\Models\EduClassAssignmentAttachments_Teacher;
use App\Models\EduAssignmentSubmissionAttachment_Teacher;
use App\Models\EduAssignmentComplain_Teacher;
use App\Models\EduAssignmentComment_Teacher;
use App\Models\EduTakenAssignment_Teacher;
use App\Models\EduStudentPerformance_Teacher;

class MasterController extends Controller
{
    public function getLogin()
    {
        return view('teacher.login');
    }
    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:8'
        ]);
        $data = array(
            'email'          => $request->email,
            'password'       => $request->password,
            'email_verified' => 1,
            'status'         => 'Active',
            'valid'          => 1
        );
        if (Auth::guard('teacher')->attempt($data)) {
            return redirect()->route('teacher.home');
        } else {
            return redirect()->route('teacher.login')->with('error', 'Email or password is not correct.');
        }
    }
    public function logout()
    {
        Auth::guard('teacher')->logout();
        return redirect()->route('teacher.login');

    }
    public function home(){
        $authId = Auth::guard('teacher')->id();
        $data['userInfo'] = EduTeachers::where('valid', 1)->find($authId);
        return view('teacher.home', $data);

    }

    public function dashboard(){
        $authId = Auth::guard('teacher')->id();
        $data['my_assigned_batches'] = $my_assigned_batches = EduAssignBatch_Teacher::valid()->where('teacher_id', $authId)->get();

        foreach ($my_assigned_batches as $key => $batch) {
            // RUNNING CLASSES
            $batch->running_class = $running_class = EduAssignBatchClasses_Teacher::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
                ->join('edu_assign_batches', 'edu_assign_batches.id', '=', 'edu_assign_batch_classes.batch_id')
                ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name', 'edu_assign_batches.batch_fb_url')
                ->where('edu_assign_batch_classes.valid', 1)
                ->where('edu_assign_batch_classes.batch_id', $batch->id)
                ->where('edu_assign_batch_classes.complete_status', 2)
                ->orderBy('edu_assign_batch_classes.id', 'desc')
                ->first();

            if (!empty($running_class)) {
                //FOR QUIZ
                $batch->run_total_given_quiz = EduStudentExam_Teacher::valid()->where('assign_batch_class_id', $running_class->id)->count();
                // FOR ASSIGNMENT
                $running_class_assignment_ids = EduClassAssignments_Teacher::valid()->where('assign_batch_class_id', $running_class->id)->get()->pluck('id');
                $batch->run_total_submitted_assignment = EduAssignmentSubmission_Teacher::valid()->whereIn('assignment_id', $running_class_assignment_ids)->count();
            } else {
                $batch->run_total_given_quiz = 0;
                $batch->run_total_submitted_assignment = 0;
            }
            //COMPLETED CLASSES
            $batch->completed_class = $completed_class = EduAssignBatchClasses_Teacher::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
                ->join('edu_assign_batches', 'edu_assign_batches.id', '=', 'edu_assign_batch_classes.batch_id')
                ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name', 'edu_assign_batches.batch_fb_url')
                ->where('edu_assign_batch_classes.valid', 1)
                ->where('edu_assign_batch_classes.batch_id', $batch->id)
                ->where('edu_assign_batch_classes.complete_status', 1)
                ->orderBy('edu_assign_batch_classes.id', 'desc')
                ->first();

            if (!empty($completed_class)) {
                //FOR QUIZ
                $batch->com_total_given_quiz = EduStudentExam_Teacher::valid()->where('assign_batch_class_id', $completed_class->id)->count();
                // FOR ASSIGNMENT
                $completed_class_assignment_ids = EduClassAssignments_Teacher::valid()->where('assign_batch_class_id', $completed_class->id)->get()->pluck('id');
                $batch->com_total_submitted_assignment = EduAssignmentSubmission_Teacher::valid()->whereIn('assignment_id', $completed_class_assignment_ids)->count();
            } else {
                $batch->com_total_given_quiz = 0;
                $batch->com_total_submitted_assignment = 0;
            }

            // ASSIGNMENT REVIEW COMPLAIN
            $data['assignment_complain'] = EduAssignmentComplain_Teacher::join('edu_taken_assignments', 'edu_taken_assignments.id', '=', 'edu_assignment_complains.taken_assignment_id')
                ->select('edu_assignment_complains.*', 'edu_taken_assignments.course_id', 'edu_taken_assignments.batch_id', '.assign_batch_class_id', 'edu_taken_assignments.class_assignment_id', 'edu_taken_assignments.assignment_submission_id', 'edu_taken_assignments.review_status')
                ->where('edu_assignment_complains.complain_to', $authId)
                ->where('edu_assignment_complains.complain_to_type', 0) // 0 = Teacher
                ->where('edu_taken_assignments.review_status', 3) // 3 = Under Revision
                ->where('edu_assignment_complains.complain_status', 1) // 1 = Pending
                ->get();
        }
        return view('teacher.dashboard.dashboard', $data);

    }

    public function classVideos(Request $request, $class_id)
    {
        $data['class_materials'] = EduCourseClassMaterials_Teacher::valid()->where('class_id', $class_id)->get();
        return view('teacher.dashboard.showMaterial', $data);
    }

    public function classAssignment(Request $request, $assign_batch_class_id)
    {
        $data['class_assignments'] = $class_assignments = EduClassAssignments_Teacher::valid()->where('assign_batch_class_id', $assign_batch_class_id)->limit(2)->get();
        foreach ($class_assignments as $key => $assignment) {
            $assignment->attachment = EduClassAssignmentAttachments_Teacher::valid()->where('class_assignment_id',$assignment->id)->first();
        }
        return view('teacher.dashboard.showAssignments', $data);
    }

    public function viewSubmitAssignment(Request $request, $submission_id)
    {
        $data['submit_assignments'] = $submit_assignments = EduAssignmentSubmission_Teacher::valid()->find($submission_id);
        $data['assignment_title'] = EduClassAssignments_Teacher::valid()->find($submit_assignments->assignment_id)->title;
        $data['submit_attachment'] = EduAssignmentSubmissionAttachment_Teacher::valid()->where('assignment_submission_id', $submit_assignments->id)->first();

        return view('teacher.dashboard.showSubmitAssignments', $data);
    }
    public function viewReviwerSubmitAssignment(Request $request, $submission_id)
    {
        $data['reviwer_comments'] = $submit_assignments = EduAssignmentComment_Teacher::valid()->where('assignment_submission_id', $submission_id)->first();
        $data['assignment_title'] = EduClassAssignments_Teacher::valid()->find($submit_assignments->class_assignments_id)->title;

        return view('teacher.dashboard.showReviwerAssignments', $data);
    }

    //UPDATE ASSIGNMENT MARK
    public function complainAssignmentMark(Request $request)
    {
        $authId = Auth::guard('teacher')->id();
        $data['complain_id'] = $complain_id = $request->complain_id;
        $assignment_complain = EduAssignmentComplain_Teacher::valid()
            ->where('complain_to', $authId)
            ->where('complain_to_type', 0) //0=Teacher
            ->find($complain_id);
        if (!empty($assignment_complain)) {
            $data['updateAccess'] = true;
            $assignment_taken_info = EduTakenAssignment_Teacher::valid()->find($assignment_complain->taken_assignment_id);
            $data['submission_info'] = EduAssignmentSubmission_Teacher::valid()->find($assignment_taken_info->assignment_submission_id);
        } else {
            $data['updateAccess'] = false;
        }
        
        return view('teacher.dashboard.updateComplainMark', $data);
    }

    public function updateAssignmentMark(Request $request)
    {
        $complain_id = $request->complain_id;
        $authId = Auth::guard('teacher')->id();
        $validator = Validator::make($request->all(), [
            'mark' => 'required'
        ]);
        if ($validator->passes()) {
            DB::beginTransaction();
            $assignment_complain = EduAssignmentComplain_Teacher::valid()->where('complain_to', $authId)->where('complain_to_type', 0)->find($complain_id);
            if (!empty($assignment_complain)) {
                $assignment_taken_info = EduTakenAssignment_Teacher::valid()->where('review_status', 3)->find($assignment_complain->taken_assignment_id);
        
                EduAssignmentSubmission_Teacher::find($assignment_taken_info->assignment_submission_id)->update([
                    'mark'         => $request->mark,
                    'mark_by'      => $authId,
                    'mark_by_type' => 0,
                    'mark_from'    => 1, //1 = Revision
                ]);
                
                // PERFORMANCE TABLE UPDATE
                $exist_performance_data = EduStudentPerformance_Teacher::valid()
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
