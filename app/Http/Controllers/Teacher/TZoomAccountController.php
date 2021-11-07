<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use File;
use Helper;
use Validator;
use App\Models\EduZoomAccount_Teacher;

class TZoomAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function teacherZoomAcc()
    {
        $data['zoom_acc_info'] = EduZoomAccount_Teacher::valid()->first();
        return view('teacher.zoomAccount.update', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveTeacherZoomAcc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required',
            'token'     => 'required'
        ]);
        if ($validator->passes()) {
            $zoomAccInfo = EduZoomAccount_Teacher::valid()->first();
            if (empty($zoomAccInfo)) {
                EduZoomAccount_Teacher::create([
                    'account_type' => 1, //1=Teacher
                    'name'         => $request->name,
                    'email'        => $request->email,
                    'password'     => $request->password,
                    'token'        => $request->token
                ]);

                $output['messege'] = 'Account has been created';
                $output['msgType'] = 'success';
            } else {
                $zoomAccInfo->update([
                    'name'         => $request->name,
                    'email'        => $request->email,
                    'password'     => $request->password,
                    'token'        => $request->token,
                ]);

                $output['messege'] = 'Account has been Updated';
                $output['msgType'] = 'success';
            }
            return redirect()->back()->with($output);
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

}
