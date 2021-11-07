<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EduTraineeUser_Web;
use Auth;
use Hash;
use Validator;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth' => 'verified']);
    }
    public function index()
    {
        $authId = Auth::id();
        $data['myProfile'] = EduTraineeUser_Web::valid()->find($authId);
        return view('web.userProfile.profile', $data);
    }
    public function profileInfo()
    {
        $authId = Auth::id();
        $data['profileInfo'] = EduTraineeUser_Web::valid()->find($authId);
        return view('web.userProfile.profileInfo', $data);
    }
    public function updateProfile()
    {
        $authId = Auth::id();
        $data['profileInfo'] = EduTraineeUser_Web::valid()->find($authId);
        return view('web.userProfile.updateProfile', $data);
    }
    public function updateProfileStore(Request $request)
    {
        $output = array();
        $authId = Auth::id();
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'phone'   => 'required'
        ]);
        if ($validator->passes()) {
            EduTraineeUser_Web::find($authId)->update([
                'name'    => $request->name,
                'phone'   => $request->phone,
            ]);
            $output['messege'] = 'Profile has been Updated';
            $output['msgType'] = 'success';
            $output['status'] = 1;
        } else {
            $output['messege'] = 'Failed! All Fields are Required';
            $output['msgType'] = 'danger';
            $output['status'] = 0;
        }
        return response($output);
    }
    
    public function changePassword()
    {
        return view('web.userProfile.changePassword');
    }
    public function savePassword(Request $request)
    {
        $output = array();
        $authId = Auth::id();
        $oldPassword = $request->oldPassword;
        $newPassword = $request->newPassword;

        $userInfo = EduTraineeUser_Web::valid()->find($authId);

        if(Hash::check($oldPassword, $userInfo->password)) {
            EduTraineeUser_Web::find($authId)->update(['password' => Hash::make($newPassword)]);
            
            $output['status'] = 'success';
            $output['message'] = 'Password successfully updated';
        } else {
            $output['status'] = 'danger';
            $output['message'] = 'Old password is not correct';
        }
        return response($output);
    }
}
