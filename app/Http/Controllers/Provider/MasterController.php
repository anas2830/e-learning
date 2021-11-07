<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Validator;
use App\Models\EduProviderUsers;

class MasterController extends Controller
{
    public function getLogin()
    {
        return view('provider.login');
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
        if (Auth::guard('provider')->attempt($data)) {
            return redirect()->route('provider.home');
        } else {
            return redirect()->route('provider.login')->with('error', 'Email or password is not correct.');
        }
    }
    public function logout()
    {
        Auth::guard('provider')->logout();
        return redirect()->route('provider.login');

    }
    public function home(){
        $authId = Auth::guard('provider')->id();
        $data['userInfo'] = EduProviderUsers::where('valid', 1)->find($authId);
        return view('provider.home', $data);

    }

}
