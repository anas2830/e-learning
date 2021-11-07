<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EduAssignBatch_Provider;
use App\Models\EduTeacher_Provider;
use Validator;
use Helper;
use DB;

class AssignTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function assignTeacher(Request $request)
    {
        $data['batch_id'] = $batch_id = $request->id;
        $data['batch_info'] = EduAssignBatch_Provider::valid()->find($batch_id);
        $data['teachers'] = EduTeacher_Provider::valid()->get();
        return view('provider.assignBatch.assignTeacher', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateTeacher(Request $request)
    {
        $data['batch_id'] = $batch_id = $request->id;
        $validator = Validator::make($request->all(), [
            'teacher_id'         => 'required',
        ]);

        if ($validator->passes()) {
                EduAssignBatch_Provider::find($batch_id)->update([
                    'teacher_id'   => $request->teacher_id,
                ]);
                $output['messege'] = 'Teacher has been Assigned';
                $output['msgType'] = 'success';
            return redirect()->back()->with($output);
        }else{
            return redirect()->back()->withErrors($validator);
        }
    }


}
