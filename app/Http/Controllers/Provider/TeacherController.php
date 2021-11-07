<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EduTeacher_Provider;
use Validator;
use Helper;
use File;
use Hash;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['all_teachers'] = EduTeacher_Provider::valid()->orderBy('id', 'desc')->get();
        return view('provider.teacher.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('provider.teacher.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'email'      => 'unique:App\Models\EduTeachers,email',
            'phone'      => 'required',
            'password'   => 'required',
        ]);
        if ($validator->passes()) {
            EduTeacher_Provider::create([
                'teacher_id'  => $request->teacher_id,
                'name'        => $request->name,
                'address'     => $request->address,
                'address'     => 1,
                'email'       => $request->email,
                'password'    => Hash::make($request->password),
                'phone'       => $request->phone,
            ]);
            $output['messege'] = 'Teacher has been created';
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
        $data['teacher'] = EduTeacher_Provider::valid()->find($id);
        return view('provider.teacher.update', $data);
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
            'name'       => 'required',
            'email'      => 'unique:App\Models\User,email,'.$id,
            'phone'      => 'required'
            // 'email'      => 'sometimes|required|email|unique:App\Models\User' . $id,
        ]);

        if ($validator->passes()) {

            if(!empty($request->password)){

                EduTeacher_Provider::find($id)->update([
                    'teacher_id'  => $request->teacher_id,
                    'name'        => $request->name,
                    'address'     => $request->address,
                    'email'       => $request->email,
                    'password'    => Hash::make($request->password),
                    'phone'       => $request->phone,
               ]);

            }else{
                EduTeacher_Provider::find($id)->update([
                    'teacher_id'  => $request->teacher_id,
                    'name'        => $request->name,
                    'address'     => $request->address,
                    'email'       => $request->email,
                    'phone'       => $request->phone,
               ]);
            }
            
            $output['messege'] = 'Teacher has been updated';
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
        EduTeacher_Provider::valid()->find($id)->delete();
    }
}
