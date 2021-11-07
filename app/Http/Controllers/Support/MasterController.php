<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Validator;
use App\Models\EduSupports;

class MasterController extends Controller
{
    public function getLogin()
    {
        return view('support.login');
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
        if (Auth::guard('support')->attempt($data)) {
            return redirect()->route('support.home');
        } else {
            return redirect()->route('support.login')->with('error', 'Email or password is not correct.');
        }
    }
    public function logout()
    {
        Auth::guard('support')->logout();
        return redirect()->route('support.login');

    }
    public function home(){
        $authId = Auth::guard('support')->id();
        $data['userInfo'] = EduSupports::where('valid', 1)->find($authId);
        return view('support.home', $data);

    }

}
