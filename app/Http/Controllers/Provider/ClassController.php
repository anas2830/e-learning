<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EduCourses_Provider;
use App\Models\EduCourseAssignClass_Provider;
use App\Models\EduCourseClassMaterials_Provider;
use App\Models\EduAssignBatchClasses_Provider;
use Validator;
use Helper;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['course_id'] = $course_id = $request->course_id;
        $data['course_info'] = EduCourses_Provider::valid()->find($course_id);
        $data['assign_classes'] = EduCourseAssignClass_Provider::valid()->where('course_id', $course_id)->get();
        return view('provider.course.class.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['course_id'] = $course_id = $request->course_id;
        $data['course_info'] = EduCourses_Provider::valid()->find($course_id);
        return view('provider.course.class.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input_videos = $request->video_id;
        $validator = Validator::make($request->all(), [
            'class_name'     => 'required',
            'class_overview' => 'required',
            'class_resource' => 'required',
            'video_id'       => 'required'
        ]);
        
        if ($validator->passes()) {
            $classInfo = EduCourseAssignClass_Provider::create([
                'course_id'      => $request->course_id,
                'class_name'     => $request->class_name,
                'class_overview' => $request->class_overview,
                'class_resource' => $request->class_resource
            ]);
            
            $filter_video_id = array_filter($input_videos);
            foreach($filter_video_id as $key => $video_id) 
            {
                EduCourseClassMaterials_Provider::create([
                    'course_id'      => $request->course_id,
                    'class_id'       => $classInfo->id,
                    'video_id'       => $video_id,
                    'video_title'    => Helper::getYoutubeVideoTitle($video_id),
                    'video_duration' => Helper::getYoutubeVideoDuration($video_id),
                ]);
            }

            $output['messege'] = 'Class has been created';
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
        $data['class_materials'] = EduCourseClassMaterials_Provider::valid()->where('class_id', $id)->get();
        return view('provider.course.class.showMaterial', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['class'] = $class = EduCourseAssignClass_Provider::valid()->find($id);
        $doneAttendances = EduAssignBatchClasses_Provider::valid()->where('course_id', $class->course_id)->where('class_id', $id)->get();
        if (count($doneAttendances) > 0) {
            $data['is_used'] = true;
        } else {
            $data['is_used'] = false;
        }
        $data['course_id'] = $course_id = $class->course_id;
        $data['course_info'] = EduCourses_Provider::valid()->find($course_id);
        $data['material_info'] = EduCourseClassMaterials_Provider::valid()->where('class_id',$id)->get();
        // echo "<pre>";
        // print_r($data['material_info']); exit();
        return view('provider.course.class.update', $data);
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
        $input_videos = $request->video_id;
        $old_video_ids = EduCourseClassMaterials_Provider::valid()->where('class_id',$id)->get()->keyBy('video_id')->all();
        $course_id = EduCourseAssignClass_Provider::valid()->find($id)->course_id;
        $assignClassInfo = EduCourseAssignClass_Provider::valid()->find($id);
        $doneAttendances = EduAssignBatchClasses_Provider::valid()->where('course_id', $assignClassInfo->course_id)->where('class_id', $id)->get();

        // echo "<pre>";
        // print_r($existing_video_ids); exit();

        $validator = Validator::make($request->all(), [
            'class_name'     => 'required',
            'class_overview' => 'required',
            'class_resource' => 'required',
            'video_id'       => 'required'
        ]);


        if ($validator->passes()) {

            // if(count($doneAttendances) > 0){
            //     $output['messege'] = 'This class already assigned';
            //     $output['msgType'] = 'danger';
            // } else{

                EduCourseAssignClass_Provider::find($id)->update([
                    'class_name'     => $request->class_name,
                    'class_overview' => $request->class_overview,
                    'class_resource' => $request->class_resource
                ]);
                $new_filter_video_id = array_filter($input_videos);
                foreach($old_video_ids as $key=>$oldValue) {
                    if(!in_array($key, $new_filter_video_id)) {
                        EduCourseClassMaterials_Provider::find($oldValue->id)->delete();
                    }
                }
                foreach($new_filter_video_id as $filter_video_id) {
                    if(!array_key_exists($filter_video_id, $old_video_ids)) {
                        EduCourseClassMaterials_Provider::create([
                            'course_id'      => $course_id,
                            'class_id'       => $id,
                            'video_id'       => $filter_video_id,
                            'video_title'    => Helper::getYoutubeVideoTitle($filter_video_id),
                            'video_duration' => Helper::getYoutubeVideoDuration($filter_video_id),
                        ]);
                    }
                }
                $output['messege'] = 'Class Materials has been updated';
                $output['msgType'] = 'success';
            // }
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
        $assignClassInfo = EduCourseAssignClass_Provider::valid()->find($id);
        $doneAttendances = EduAssignBatchClasses_Provider::valid()->where('course_id', $assignClassInfo->course_id)->where('class_id', $id)->get();
        if (count($doneAttendances) == 0) {
            $materials_id = EduCourseClassMaterials_Provider::valid()->where('class_id',$id)->get();
            foreach ($materials_id as $key => $value) {
               EduCourseClassMaterials_Provider::find($value->id)->delete();
            }
            EduCourseAssignClass_Provider::valid()->find($id)->delete();
        } else {
            return response($data['error'] = 'This Class already Used!!');
        }
    }
}
