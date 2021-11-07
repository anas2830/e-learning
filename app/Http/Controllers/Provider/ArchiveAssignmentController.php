<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Helper;
use File;
use DB;
use App\Models\EduCourseAssignClass_Provider;
use App\Models\EduClassAssignments_Provider;
use App\Models\EduAssignmentArchive_Provider;
use App\Models\EduAssignmentArchiveAttach_Provider;
use App\Models\EduAssignmentSubmission_Provider; 


class ArchiveAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $class_id = $request->class_id;
        $data['class_info'] = $class_info = EduCourseAssignClass_Provider::valid()->find($class_id);
        $data['assignments'] = $assignments = EduAssignmentArchive_Provider::valid()
            ->where('course_id', $class_info->course_id)
            ->where('course_class_id', $class_id)
            ->latest()
            ->get();

        foreach ($assignments as $key => $assignment) {
            $class_assignment_ids = EduClassAssignments_Provider::valid()->where('assignment_archive_id', $assignment->id)->pluck('id')->toArray();
            
            if(count($class_assignment_ids) > 0){
                $assignment->teacherUsed = TRUE;
                $studentSubmission_qty = EduAssignmentSubmission_Provider::valid()->whereIn('assignment_id', $class_assignment_ids)->count();

                if ($studentSubmission_qty > 0) {
                    $assignment->stdUsed = TRUE;
                } else {
                    $assignment->stdUsed = FALSE;
                }
            }else{
                $assignment->teacherUsed = FALSE;
            }  
        }
        return view('provider.course.class.archiveAssignment.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $class_id = $request->class_id;
        $data['class_info'] = EduCourseAssignClass_Provider::valid()->find($class_id);
        return view('provider.course.class.archiveAssignment.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $class_id = $request->class_id;
        $class_info = EduCourseAssignClass_Provider::valid()->find($class_id);
        $mainFile = $request->attachment;

        $validator = Validator::make($request->all(), [
            'title'    => 'required',
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            if(isset($mainFile)){
                $validPath = 'uploads/archiveAssignmentAttachment';
                $uploadResponse = Helper::getUploadedAttachmentName($mainFile, $validPath);

                if($uploadResponse['status'] != 0){
                    $assignment = EduAssignmentArchive_Provider::create([
                        'course_id'       => $class_info->course_id,
                        'course_class_id' => $class_id,
                        'title'           => $request->title,
                        'overview'        => $request->overview
                    ]);
    
                    EduAssignmentArchiveAttach_Provider::create([
                        'assignment_archive_id' => $assignment->id,
                        'file_name'             => $uploadResponse['file_name'],
                        'file_original_name'    => $uploadResponse['file_original_name'],
                        'size'                  => $uploadResponse['file_size'],
                        'extention'             => $uploadResponse['file_extention']
                    ]);
    
                    $output['messege'] = 'Assignment has been Archived';
                    $output['msgType'] = 'success';
                }else{
                    $output['messege'] = $uploadResponse['errors'];
                    $output['msgType'] = 'danger';
                }
            }else{
                EduAssignmentArchive_Provider::create([
                    'course_id'       => $class_info->course_id,
                    'course_class_id' => $class_id,
                    'title'           => $request->title,
                    'overview'        => $request->overview
                ]);

                $output['messege'] = 'Assignment has been Archived';
                $output['msgType'] = 'success';
            }

            DB::commit();
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
        $data['assignment_info'] = $assignment_info =  EduAssignmentArchive_Provider::valid()->find($id);
        $data['class_info'] = EduCourseAssignClass_Provider::valid()->find($assignment_info->course_class_id);
        $data['attachment_info'] = EduAssignmentArchiveAttach_Provider::valid()->where('assignment_archive_id', $assignment_info->id)->first();
        return view('provider.course.class.archiveAssignment.update', $data);
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
        $assignment_id = $id;
        $assignment_info = EduAssignmentArchive_Provider::valid()->find($assignment_id);
        $attachment_info = EduAssignmentArchiveAttach_Provider::valid()->where('assignment_archive_id',$assignment_id)->first();
        $mainFile = $request->attachment;

        $validator = Validator::make($request->all(), [
            'title'    => 'required'
        ]);

        if ($validator->passes()) {
            $class_assignment = EduClassAssignments_Provider::valid()->where('assignment_archive_id', $id)->get()->pluck('id')->toArray();
            $studentSubmission_qty = EduAssignmentSubmission_Provider::valid()->whereIn('assignment_id', $class_assignment)->count();
            // if ($studentSubmission_qty > 0) {
            //     $output['messege'] = 'Archive Assignment has already used';
            //     $output['msgType'] = 'danger';
            //     return redirect()->back()->with($output);
            // } else {
                DB::beginTransaction();
                $validPath = 'uploads/archiveAssignmentAttachment';
                if(isset($mainFile)){
                    if(isset($attachment_info) && $attachment_info->file_name != $mainFile){
                        $uploadResponse = Helper::getUploadedAttachmentName($mainFile, $validPath);
                    } else{
                        $uploadResponse['status'] = 1;
                    }
    
                    if($uploadResponse['status'] != 0){
                        $assignment = EduAssignmentArchive_Provider::find($assignment_id)->update([
                            'title'    => $request->title,
                            'overview' => $request->overview
                        ]);
                        if (isset($attachment_info) && $attachment_info->file_name != $mainFile) {
                            File::delete(public_path($validPath.'/').$attachment_info->file_name);
                            EduAssignmentArchiveAttach_Provider::find($attachment_info->id)->update([
                                'file_name'           => $uploadResponse['file_name'],
                                'file_original_name'  => $uploadResponse['file_original_name'],
                                'size'                => $uploadResponse['file_size'],
                                'extention'           => $uploadResponse['file_extention']
                            ]);
                        }
                        // if used
                        $class_assignments = EduClassAssignments_Provider::valid()->where('assignment_archive_id', $assignment_id)->get();
                        foreach($class_assignments as $key => $cls_assignment){
                            EduClassAssignments_Provider::find($cls_assignment->id)->update([
                                'title'             => $request->title,
                                'overview'          => $request->overview,
                            ]);
                        }
    
                        $output['messege'] = 'Archive Assignment has been updated';
                        $output['msgType'] = 'success';
                    } else{
                        $output['messege'] = $uploadResponse['errors'];
                        $output['msgType'] = 'danger';
                    }
                } else{
                    if(isset($attachment_info)){
                        File::delete(public_path($validPath.'/').$attachment_info->file_name);
                        EduAssignmentArchiveAttach_Provider::valid()->find($attachment_info->id)->delete();
                    }
                    EduAssignmentArchive_Provider::find($assignment_id)->update([
                        'title'                 => $request->title,
                        'overview'              => $request->overview,
                        'due_date'              => Helper::dateYMD($request->due_date),
                        'due_time'              => Helper::timeHi24($request->due_time),
                    ]);
                    // if used
                    $class_assignments = EduClassAssignments_Provider::valid()->where('assignment_archive_id', $assignment_id)->get();
                    foreach($class_assignments as $key => $cls_assignment){
                        EduClassAssignments_Provider::find($cls_assignment->id)->update([
                            'title'             => $request->title,
                            'overview'          => $request->overview,
                        ]);
                    }

                    $output['messege'] = 'Archive Assignment has been updated';
                    $output['msgType'] = 'success';
                }
                DB::commit();
                return redirect()->back()->with($output);
            // }
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
        $assignment_info = EduAssignmentArchive_Provider::valid()->find($id);
        $exits_attach = EduAssignmentArchiveAttach_Provider::valid()->where('assignment_archive_id',$id)->first();
        //Archive Assignment used/not checking
        // $assign_assignment_qty = EduClassAssignments_Provider::valid()->where('assignment_archive_id', $id)->count();
        $class_assignment = EduClassAssignments_Provider::valid()->where('assignment_archive_id', $id)->get()->pluck('id')->toArray();
        $studentSubmission_qty = EduAssignmentSubmission_Provider::valid()->whereIn('assignment_id', $class_assignment)->count();

        if($studentSubmission_qty > 0){ //Already Used.
            return  "This Assignment already Used!!";
        }else{
            if(!empty($exits_attach)){
                $validPath = 'uploads/archiveAssignmentAttachment';
                File::delete(public_path($validPath.'/').$exits_attach->file_name);
                EduAssignmentArchiveAttach_Provider::valid()->find($exits_attach->id)->delete();
            }
            $assignment_info->delete();
        }
    }
}
