<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use File;
use Helper;
use Validator;
use App\Models\EduZoomAccount_Support;

class SZoomAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function supportZoomAcc()
    {
        $data['zoom_acc_info'] = EduZoomAccount_Support::valid()->first();
        return view('support.zoomAccount.update', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveSupportZoomAcc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required',
            'token'     => 'required'
        ]);
        if ($validator->passes()) {
            $zoomAccInfo = EduZoomAccount_Support::valid()->first();
            if (empty($zoomAccInfo)) {
                EduZoomAccount_Support::create([
                    'account_type' => 2, //2=Support
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
