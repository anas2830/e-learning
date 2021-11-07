<?php

namespace App\Http\Controllers\Student;

use File;
use Helper;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EduAssignBatchStudent_User;
use App\Models\EduStudentSuccessStory_User;

class SuccessStoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
        $data['all_stories'] = EduStudentSuccessStory_User::valid()->where('batch_id', $student_course_info->batch_id)->orderBy('id', 'desc')->get();
        return view('student.successStory.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('student.successStory.create');
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
            'marketplace_name' => 'required',
            'work_amount'      => 'required',
            'work_screenshort' => 'required'
        ]);

        if ($validator->passes()) {
            $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
            $mainFile = $request->work_screenshort;
            $imgPath = 'uploads/studentStory';
            $uploadResponse = Helper::getFixedUpFileName($mainFile, $imgPath, true, 639, 326);
            if ($uploadResponse['status'] == 1) {
                EduStudentSuccessStory_User::create([
                    'course_id'        => $student_course_info->course_id,
                    'batch_id'         => $student_course_info->batch_id,
                    'marketplace_name' => $request->marketplace_name,
                    'work_amount'      => $request->work_amount,
                    'work_screenshort' => $uploadResponse['file_name'],
                    'own_comment'      => $request->own_comment
                ]);
                $output['messege'] = 'Success Story has been created';
                $output['msgType'] = 'success';
            } else {
                
                $output['messege'] = $uploadResponse['errors'];
                $output['msgType'] = 'danger';
            }
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
        $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
        $data['success_story_info'] = EduStudentSuccessStory_User::valid()->where('batch_id', $student_course_info->batch_id)->find($id);
        return view('student.successStory.update', $data);
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
            'marketplace_name' => 'required',
            'work_amount'      => 'required',
            // 'work_screenshort' => 'required'
        ]);
        if ($validator->passes()) {
            $success_story_info = EduStudentSuccessStory_User::find($id);
            if (isset($request->work_screenshort)) {
                if ($request->work_screenshort != $success_story_info->work_screenshort) {
                    $mainFile = $request->work_screenshort;
                    $imgPath = 'uploads/studentStory';
                    $uploadResponse = Helper::getFixedUpFileName($mainFile, $imgPath, true, 639, 326);
                    
                    if ($uploadResponse['status'] == 1) {
                        File::delete(public_path($imgPath.'/').$success_story_info->work_screenshort);
                        File::delete(public_path($imgPath.'/usedImg/').$success_story_info->work_screenshort);
                        File::delete(public_path($imgPath.'/thumb/').$success_story_info->work_screenshort);
                        
                        EduStudentSuccessStory_User::find($id)->update([
                            'marketplace_name' => $request->marketplace_name,
                            'work_amount'      => $request->work_amount,
                            'work_screenshort' => $uploadResponse['file_name'],
                            'own_comment'      => $request->own_comment
                        ]);
                        $output['messege'] = 'Success Story has been updated';
                        $output['msgType'] = 'success';
                    } else {
                        $output['messege'] = $uploadResponse['errors'];
                        $output['msgType'] = 'danger';
                    }
                } else {
                    EduStudentSuccessStory_User::find($id)->update([
                        'marketplace_name' => $request->marketplace_name,
                        'work_amount'      => $request->work_amount,
                        'own_comment'      => $request->own_comment
                    ]);
                    $output['messege'] = 'Success Story has been updated';
                    $output['msgType'] = 'success';
                }
            } else {
                EduStudentSuccessStory_User::find($id)->update([
                    'marketplace_name' => $request->marketplace_name,
                    'work_amount'      => $request->work_amount,
                    'own_comment'      => $request->own_comment
                ]);
                $output['messege'] = 'Success Story has been updated';
                $output['msgType'] = 'success';
            }
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
        $success_story_info = EduStudentSuccessStory_User::valid()->find($id);

        if($success_story_info->approve_status == 1) {
            return  "Already Approved!!!";
        }else{
            File::delete(public_path('uploads/studentStory/').$success_story_info->work_screenshort);
            File::delete(public_path('uploads/studentStory/usedImg/').$success_story_info->work_screenshort);
            File::delete(public_path('uploads/studentStory/thumb/').$success_story_info->work_screenshort);
            EduStudentSuccessStory_User::valid()->find($id)->delete();
        }
        
    }
}
