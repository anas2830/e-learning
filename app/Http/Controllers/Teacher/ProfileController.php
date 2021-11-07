<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EduTeacherUsers_Teacher;
use Illuminate\Support\Facades\Hash;

use Auth;
use Validator;
use Helper;
use File;


class ProfileController extends Controller
{
    public function index()
    {
    	$authId = Auth::guard('teacher')->id();
    	$data['teacher_info'] = EduTeacherUsers_Teacher::valid()->find($authId);
        return view('teacher.profile')->with($data);
    }

    public function updateProfile(Request $request,$id){

    	$validator = Validator::make($request->all(), [
            'name'     => 'required',
        ]);
        if ($validator->passes()) {
            $teacher_user = EduTeacherUsers_Teacher::find($id);

            if (isset($request->teacher_image)) {

                if ($request->teacher_image != $teacher_user->image) {
                    $mainFile = $request->teacher_image;
                    $imgPath = 'uploads/teacherProfile';
                    $uploadResponse = Helper::getUploadedFileName($mainFile, $imgPath, 640, 426);
                    
                    if ($uploadResponse['status'] == 1) {
                        File::delete(public_path($imgPath.'/').$teacher_user->image);
                        File::delete(public_path($imgPath.'/thumb/').$teacher_user->image);
                        
                        EduTeacherUsers_Teacher::find($id)->update([
                            'image'    => $uploadResponse['file_name'],
                            'name'     => $request->name,
	                        'address'  => $request->address,
	                        'phone'    => $request->phone
                        ]);


                        $output['messege'] = 'Teacher has been updated';
                        $output['msgType'] = 'success';
                    } else {
                        $output['messege'] = $uploadResponse['errors'];
                        $output['msgType'] = 'danger';
                    }
                } else {
                    EduTeacherUsers_Teacher::find($id)->update([
                        'name'     => $request->name,
                        'address'  => $request->address,
                        'phone'    => $request->phone
                    ]);
                    $output['messege'] = 'Teacher has been updated';
                    $output['msgType'] = 'success';
                }
            } else {

                EduTeacherUsers_Teacher::find($id)->update([
                        'name'     => $request->name,
                        'address'  => $request->address,
                        'phone'    => $request->phone
                    ]);
                $output['messege'] = 'Teacher has been updated';
                $output['msgType'] = 'success';
            }
            return redirect()->back()->with($output);
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    public function updatePassword(Request $request,$id){

    	$validator = Validator::make($request->all(), [
            'password'   => 'required',
        ]);

        if ($validator->passes()) {

            if(!empty($request->password)){

                EduTeacherUsers_Teacher::find($id)->update([
                    'password'    => Hash::make($request->password),
               ]);

            }
            
            $output['messege'] = 'Teacher has been updated';
            $output['msgType'] = 'success';

            return redirect()->back()->with($output);

        } else {
            return redirect()->back()->withErrors($validator);
        }
    }
}
