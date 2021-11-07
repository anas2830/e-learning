<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\EduStudent_User;
use App\Models\EduStudentPracticeTime_User;
use App\Models\EduAssignBatchStudent_User;
use Illuminate\Support\Facades\Hash;

use Auth;
use Validator;
use Helper;
use File;
use Image;

class ProfileController extends Controller
{
    public function index()
    {
    	$authId = Auth::id();
    	$data['student_info'] = EduStudent_User::valid()->find($authId);
        return view('student.profile')->with($data);
    }

    public function changeProfile()
    {
    	$authId = Auth::id();
    	$data['student_info'] = EduStudent_User::valid()->find($authId);
        return view('student.changeProfile')->with($data);
    }
    public function changeProfileUpdate(Request $request)
    {
        $authId = Auth::id();
        
        $data = $request->image;

 
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);

        $data = base64_decode($data);
        $imageName = time().'.png';
        $path = public_path('uploads/studentProfile/');
        file_put_contents($path.$imageName, $data);
        //create instance
        $img = Image::make($path.$imageName);
        //resize image
        $img->resize(80, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path.'thumb/'.$imageName); //save the same file as thumb
        $userInfo = EduStudent_User::find($authId);
        if(!empty($userInfo->image)) {
            File::delete(public_path($path.'/').$userInfo->image);
            File::delete(public_path($path.'thumb/').$userInfo->image);
        }
        //UPDATE NAME TO TRAINEE USERS TABLE
        $userInfo->update(['image'=>$imageName]);

        $output['messege'] = 'Your Profile has been updated';
        $output['msgType'] = 'success';
        
        return response($output);
    }
    
    public function updateProfile(Request $request,$id){

    	$validator = Validator::make($request->all(), [
            'name'     => 'required',
            'sur_name' => 'required',
            'phone'    => 'required',
        ]);
        if ($validator->passes()) {

            EduStudent_User::find($id)->update([
                'name'       => $request->name,
                'sur_name'   => $request->sur_name,
                'address'    => $request->address,
                'phone'      => $request->phone,
                'fb_profile' => $request->fb_profile,
            ]);

            $output['messege'] = 'Your Profile has been updated';
            $output['msgType'] = 'success';

            return redirect()->back()->with($output);
        }else {
            return redirect()->back()->withErrors($validator);
        }
    }

    public function updatePassword(Request $request,$id){

    	$validator = Validator::make($request->all(), [
            'password'   => 'required',
        ]);

        if ($validator->passes()) {

            if(!empty($request->password)){

                EduStudent_User::find($id)->update([
                    'password'    => Hash::make($request->password),
               ]);

            }
            
            $output['messege'] = 'Your password has been updated';
            $output['msgType'] = 'success';

            return redirect()->back()->with($output);

        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    public function updatePracticeTime(Request $request){

        $type = $request->type; // 1 = stop , 2 = resume
        $hours = $request->hours;
        $minutes = $request->minutes;
        $seconds = $request->seconds;

        $current_date = (isset($request->storageDate) ? $request->storageDate : date('Y-m-d'));
        $authId = Auth::id();
        $student_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
        $duration_of_sec = (int)$hours*60*60+(int)$minutes*60+$seconds; 
        $practice_info = EduStudentPracticeTime_User::where('student_id',$authId)
            ->where('course_id',$student_info->course_id)
            ->where('batch_id',$student_info->batch_id)
            ->whereDate('date', '=', $current_date)
            ->first();

        $output = [];

        if(!empty($practice_info)) {
            EduStudentPracticeTime_User::find($practice_info->id)->update([
                'total_time' => $duration_of_sec,
            ]);
            
            $output['msg'] = 'total time update';
        } else {
            EduStudentPracticeTime_User::create([
                'student_id' => $authId,
                'course_id'  => $student_info->course_id,
                'batch_id'   => $student_info->batch_id,
                'date'       => $current_date,
                'total_time' => $duration_of_sec,
                'resume_time'=> 0
            ]);

            $output['msg'] = 'total time create';
        }
        return response($output);

    }
}
