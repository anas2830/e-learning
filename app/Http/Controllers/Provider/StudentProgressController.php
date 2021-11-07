<?php

namespace App\Http\Controllers\Provider;

use Validator;
use Helper;
use DB;
use Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EduStudent_Provider;
use App\Models\EduStudentProgress_Provider;



class StudentProgressController extends Controller
{
    public function stdProgress(Request $request){

        $data['studentProgress'] = EduStudentProgress_Provider::valid()->where('type',1)->first(); // 1 = progress
    
        return view('provider.progress', $data);
    }

    public function saveStdProgress(Request $request){

        $output = array();
        $input =  $request->all();

        $validator = [
            'practice_time'       => 'required',
            'video_watch_time'    => 'required',
            'attendence'          => 'required',
            'class_mark'          => 'required',
            'assignment'          => 'required',
            'quiz'                => 'required'
        ];

        $validator = Validator::make($input, $validator);

        $total_progress = $request->practice_time + $request->video_watch_time + $request->attendence + $request->class_mark + $request->assignment + $request->quiz;

        if ( $validator->passes() ) {

            if($total_progress == 100){

                $exists = EduStudentProgress_Provider::valid()->where('type',1)->first();
                if(!empty($exists)){
                    EduStudentProgress_Provider::find($exists->id)->update([
                        'practice_time'      => $request->practice_time,
                        'video_watch_time'   => $request->video_watch_time,
                        'attendence'         => $request->attendence,
                        'class_mark'         => $request->class_mark,
                        'assignment'         => $request->assignment,
                        'quiz'               => $request->quiz
                    ]);
                }else{
                    EduStudentProgress_Provider::create([
                        'practice_time'      => $request->practice_time,
                        'video_watch_time'   => $request->video_watch_time,
                        'attendence'         => $request->attendence,
                        'class_mark'         => $request->class_mark,
                        'assignment'         => $request->assignment,
                        'quiz'               => $request->quiz,
                        'type'               => 1  // 1 = progress
                    ]);
                }
    
                $output['messege'] = 'Progress has been Updated';
                $output['msgType'] = 'success';

                return redirect()->back()->with($output);

            }else{

                $output['messege'] = 'Total Progress Should be 100%';
                $output['msgType'] = 'danger';

                return redirect()->back()->with($output);
            }

        } else {
            return redirect()->back()->withErrors($validator);
        }
    }
}
