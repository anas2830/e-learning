<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\EduEventSms_Provider;
use App\Models\EduCourses_Provider;
use App\Models\EduStudent_Provider;
use App\Models\EduTeacher_Provider;
use App\Models\EduSupport_Provider;
use App\Models\EduAssignBatch_Provider;
use App\Models\EduAssignBatchStudent_Provider;


use Auth;
use Validator;
use Helper;
use File;
use Illuminate\Support\Str;

class EventSmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['allevent_sms'] = EduEventSms_Provider::valid()->get();
        return view('provider.eventSms.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        return view('provider.eventSms.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $output = array();
        $input =  $request->all();
        $user_type = $request->user_type;

        $validator = [
            'sms_type' => 'required|unique:edu_event_sms,type',
            'status'   => 'required',
            'message'  => 'required',
        ];

        $validator = Validator::make($input, $validator);

        if ($validator->passes()) {

            $message = $request->message;
            $sms_type = $request->sms_type;
            $status = $request->status;

            EduEventSms_Provider::create([
                'type'      => $sms_type,
                'message'   => $message,
                'status'    => $status,
            ]);

            $output['messege'] = 'Event Sms Save Successfully!';
            $output['msgType'] = 'success';

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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['eventSmsInfo'] = EduEventSms_Provider::valid()->find($id);
        return view('provider.eventSms.update', $data);
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
        $output = array();
        $input =  $request->all();

        $validator = Validator::make($request->all(), [
            'status'  => 'required',
            'message' => 'required',
        ]);

        if ($validator->passes()) {

            $message = $request->message;
            $status = $request->status;

            EduEventSms_Provider::find($id)->update([
                'message'   => $message,
                'status'    => $status,
            ]);

            $output['messege'] = 'Event Sms Update Successfully!';
            $output['msgType'] = 'success';

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
        //
    }
}
