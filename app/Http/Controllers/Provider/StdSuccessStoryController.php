<?php

namespace App\Http\Controllers\Provider;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EduStudentSuccessStory_Global;

class StdSuccessStoryController extends Controller
{
    public function index(Request $request)
    {
        $data['student_successes'] = EduStudentSuccessStory_Global::join('users', 'users.id', '=', 'edu_student_success_stories.created_by')
            ->join('edu_assign_batches', 'edu_assign_batches.id', '=', 'edu_student_success_stories.batch_id')
            ->select('edu_student_success_stories.*', 'edu_assign_batches.batch_no', 'users.name', 'users.email', 'users.phone')
            ->where('edu_student_success_stories.valid', 1)
            ->where('edu_assign_batches.valid', 1)
            ->where('users.valid', 1)
            ->get();

        return view('provider.stdSuccessStory.listData', $data);
    }

    public function storyApproval(Request $request)
    {
        $data['std_success_story_info'] = EduStudentSuccessStory_Global::find($request->story_id);
        return view('provider.stdSuccessStory.storyApproval', $data);
    }
    public function storyApprovalAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'approve_status' => 'required'
        ]);
        if ($validator->passes()) {
            EduStudentSuccessStory_Global::find($request->story_id)->update([
                'approve_status' => $request->approve_status
            ]);
            $output['messege'] = 'Success Story has been Approved';
            $output['msgType'] = 'success';
            return redirect()->back()->with($output);
        } else {
            return redirect()->back()->withErrors($validator);
        }

    }

}
