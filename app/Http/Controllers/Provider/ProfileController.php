<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EduProviderUsers_Provider;
use Illuminate\Support\Facades\Hash;

use Auth;
use Validator;
use Helper;
use File;


class ProfileController extends Controller
{
    public function index()
    {
        $authId = Auth::guard('provider')->id();
    	$data['provider_info'] = EduProviderUsers_Provider::valid()->find($authId);
        return view('provider.profile', $data);
    }

    public function updateProfile(Request $request,$id){

    	$validator = Validator::make($request->all(), [
            'name'     => 'required',
        ]);
        if ($validator->passes()) {
            $provider_user = EduProviderUsers_Provider::find($id);

            if (isset($request->provider_image)) {

                if ($request->provider_image != $provider_user->image) {
                    $mainFile = $request->provider_image;
                    $imgPath = 'uploads/providerProfile';
                    $uploadResponse = Helper::getUploadedFileName($mainFile, $imgPath, 640, 426);
                    
                    if ($uploadResponse['status'] == 1) {
                        File::delete(public_path($imgPath.'/').$provider_user->image);
                        File::delete(public_path($imgPath.'/thumb/').$provider_user->image);
                        
                        EduProviderUsers_Provider::find($id)->update([
                            'image'    => $uploadResponse['file_name'],
                            'name'     => $request->name,
	                        'address'  => $request->address,
	                        'phone'    => $request->phone
                        ]);


                        $output['messege'] = 'Provider has been updated';
                        $output['msgType'] = 'success';
                    } else {
                        $output['messege'] = $uploadResponse['errors'];
                        $output['msgType'] = 'danger';
                    }
                } else {
                    EduProviderUsers_Provider::find($id)->update([
                        'name'     => $request->name,
                        'address'  => $request->address,
                        'phone'    => $request->phone
                    ]);
                    $output['messege'] = 'Provider has been updated';
                    $output['msgType'] = 'success';
                }
            } else {

                EduProviderUsers_Provider::find($id)->update([
                        'name'     => $request->name,
                        'address'  => $request->address,
                        'phone'    => $request->phone
                    ]);
                $output['messege'] = 'Provider has been updated';
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

                EduProviderUsers_Provider::find($id)->update([
                    'password'    => Hash::make($request->password),
               ]);

            }
            
            $output['messege'] = 'Provider has been updated';
            $output['msgType'] = 'success';

            return redirect()->back()->with($output);

        } else {
            return redirect()->back()->withErrors($validator);
        }
    }
}
