<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Helper;
use File;
use Auth;
use DateTime;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SupportRequestMail;
use App\Models\EduSupportCategory_User;
use App\Models\EduStudentSupportRequest_User;
use App\Models\EduAssignBatchStudent_User;
use App\Models\EduSupportLiveClassSchedules_User;
use App\Models\EduSupport_User;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authId = Auth::id();
        $currentDate = date('Y-m-d H:i:s');
        $data['all_requests'] = $all_request =  EduStudentSupportRequest_User::join('edu_support_categories', 'edu_support_categories.id', 'edu_student_support_requests.category_id')
            ->select('edu_student_support_requests.*', 'edu_support_categories.category_name')
            ->where('edu_student_support_requests.valid', 1)
            ->where('edu_student_support_requests.created_by', $authId)
            ->orderBy('edu_student_support_requests.id', 'desc')
            ->get();

        foreach($all_request as $key => $request){
            $request->liveSchedule = EduSupportLiveClassSchedules_User::valid()
                ->where('std_support_req_id', $request->id)
                ->where('student_id', $authId)
                ->where(DB::raw('TIMESTAMP(start_date, end_time)'), '>=', $currentDate)
                ->first();
        }

        return view('student.classroom.support.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categories'] = EduSupportCategory_User::valid()->latest()->get();
        return view('student.classroom.support.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authId = Auth::id();
        $validator = Validator::make($request->all(), [
            'category_id'     => 'required',
            'request_title'   => 'required',
            'request_details' => 'required'
        ]);

        if ($validator->passes()) {

            $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();

            if(!empty($student_course_info)){

                EduStudentSupportRequest_User::create([
                    'category_id'    => $request->category_id,
                    'batch_id'       => $student_course_info->batch_id,
                    'course_id'      => $student_course_info->course_id,
                    'request_title'  => $request->request_title,
                    'request_details'=> $request->request_details,
                ]);
                    
                $emails = EduSupport_User::join('edu_assinged_support_categories', 'edu_assinged_support_categories.support_id', '=', 'edu_supports.id')
                    ->select('edu_supports.email')
                    ->where('edu_assinged_support_categories.support_category_id', $request->category_id)
                    ->where('edu_supports.valid', 1)
                    ->get()->pluck('email')->toArray();

                $details = array(
                    'title'   =>   $request->request_title,
                    'details' => $request->request_details
                ); 
                if (!empty($emails)) {
                    Mail::to($emails)->send(new SupportRequestMail($details));
                }

                $output['messege'] = 'Support Request has been created';
                $output['msgType'] = 'success';
            }else{
                $output['messege'] = 'You are not allow for support';
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
        $data['request_info'] = EduStudentSupportRequest_User::valid()->find($id);
        $data['categories'] = EduSupportCategory_User::valid()->latest()->get();
        return view('student.classroom.support.update', $data);
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
            'category_id'=> 'required',
            'request_title'       => 'required',
            'request_details'     => 'required'
        ]);

        if ($validator->passes()) {

            $request_info = EduStudentSupportRequest_User::find($id);

            if($request_info->approve_status == 0){
                $request_info->update([
                    'category_id'    => $request->category_id,
                    'request_title'  => $request->title,
                    'request_details'=> $request->details,
                ]);
                
                $output['messege'] = 'Support has been updated';
                $output['msgType'] = 'success';
            }else{
                $output['messege'] = 'Your request already approved';
                $output['msgType'] = 'info';
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
        EduStudentSupportRequest_User::valid()->find($id)->delete();
    }
}
