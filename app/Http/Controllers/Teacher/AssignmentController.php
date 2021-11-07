<?php

namespace App\Http\Controllers\Teacher;

use DB;
use File;
use Helper;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EduClassAssignments_Teacher;
use App\Models\EduAssignmentArchive_Teacher;
use App\Models\EduAssignBatchClasses_Teacher;
use App\Models\EduAssignBatchStudent_Teacher;
use App\Models\EduAssignmentSubmission_Teacher;
use App\Models\EduAssignmentArchiveAttach_Teacher;
use App\Models\EduClassAssignmentAttachments_Teacher;
use App\Models\EduStudentWiseClassAssignment_Teacher;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['batch_class_id'] = $batch_class_id = $request->batch_class_id;
        $data['batch_class_info'] = $batch_class_info = EduAssignBatchClasses_Teacher::valid()->find($batch_class_id);
        $data['assignments'] = $assignments = EduClassAssignments_Teacher::valid()
            ->where('batch_id', $batch_class_info->batch_id)
            ->where('course_id', $batch_class_info->course_id)
            ->where('assign_batch_class_id', $batch_class_id)
            ->orderBy('id', 'desc')
            ->get();
        $data['is_thrown'] = EduStudentWiseClassAssignment_Teacher::valid()->where('assign_batch_classes_id', $batch_class_id)->first();

        return view('teacher.assignment.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['batch_class_id'] = $batch_class_id = $request->batch_class_id;
        $data['batch_class_info'] = $batch_class_info = EduAssignBatchClasses_Teacher::valid()->find($batch_class_id);
        $data['archive_assignments'] = EduAssignmentArchive_Teacher::valid()
            ->where('course_id', $batch_class_info->course_id)
            ->where('course_class_id', $batch_class_info->class_id)
            ->get();
        return view('teacher.assignment.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $batch_class_id = $request->batch_class_id;
        $batch_class_info = EduAssignBatchClasses_Teacher::valid()->find($batch_class_id);
        $assignment_archive_id = $request->assignment_archive_id;

        $validator = Validator::make($request->all(), [
            'assignment_archive_id' => 'required',
            'due_date'              => 'required',
            'due_time'              => 'required'
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            $archive_assignment_info = EduAssignmentArchive_Teacher::valid()->find($assignment_archive_id);
            $archive_assignment_attach_info = EduAssignmentArchiveAttach_Teacher::valid()->where('assignment_archive_id', $assignment_archive_id)->first();

            if(!empty($archive_assignment_attach_info)){
                
                $fromPath = 'uploads/archiveAssignmentAttachment';
                $toPath = 'uploads/assignment/teacherAttachment';
                $file_name = $archive_assignment_attach_info->file_name;
                File::copy(public_path($fromPath.'/'.$file_name), public_path($toPath.'/'.$file_name));

                $assignment = EduClassAssignments_Teacher::create([
                    'batch_id'              => $batch_class_info->batch_id,
                    'course_id'             => $batch_class_info->course_id,
                    'assign_batch_class_id' => $batch_class_id,
                    'assignment_archive_id' => $assignment_archive_id,
                    'title'                 => $archive_assignment_info->title,
                    'overview'              => $archive_assignment_info->overview,
                    'start_date'            => date('Y-m-d'),
                    'due_date'              => Helper::dateYMD($request->due_date),
                    'due_time'              => Helper::timeHi24($request->due_time),
                ]);

                EduClassAssignmentAttachments_Teacher::create([
                    'class_assignment_id' => $assignment->id,
                    'archive_attach_id'   => $archive_assignment_attach_info->id,
                    'file_name'           => $file_name,
                    'file_original_name'  => $archive_assignment_attach_info->file_original_name,
                    'size'                => $archive_assignment_attach_info->size,
                    'extention'           => $archive_assignment_attach_info->extention
                ]);

                $output['messege'] = 'Assignment has been uploaded';
                $output['msgType'] = 'success';
            }else{
                EduClassAssignments_Teacher::create([
                    'batch_id'              => $batch_class_info->batch_id,
                    'course_id'             => $batch_class_info->course_id,
                    'assign_batch_class_id' => $batch_class_id,
                    'assignment_archive_id' => $assignment_archive_id,
                    'title'                 => $archive_assignment_info->title,
                    'overview'              => $archive_assignment_info->overview,
                    'start_date'            => date('Y-m-d'),
                    'due_date'              => Helper::dateYMD($request->due_date),
                    'due_time'              => Helper::timeHi24($request->due_time),
                ]);

                $output['messege'] = 'Assignment has been uploaded';
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['assignment_info'] = $assignment_info =  EduClassAssignments_Teacher::valid()->find($id);
        $data['attachment_info'] = EduClassAssignmentAttachments_Teacher::valid()->where('class_assignment_id', $assignment_info->id)->first();
        $data['archive_assignments'] = EduAssignmentArchive_Teacher::valid()->get();
        $batch_class_info = EduAssignBatchClasses_Teacher::valid()->find($assignment_info->assign_batch_class_id);
        $data['archive_assignments'] = EduAssignmentArchive_Teacher::valid()
            ->where('course_id', $batch_class_info->course_id)
            ->where('course_class_id', $batch_class_info->class_id)
            ->get();
        return view('teacher.assignment.update', $data);
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
        $assignment_info = EduClassAssignments_Teacher::valid()->find($assignment_id);
        $attachment_info = EduClassAssignmentAttachments_Teacher::valid()->where('class_assignment_id',$assignment_id)->first();
        $assignment_archive_id = $request->assignment_archive_id;

        $validator = Validator::make($request->all(), [
            'assignment_archive_id' => 'required',
            'due_date'              => 'required',
            'due_time'              => 'required'
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            if ($assignment_info->assignment_archive_id == $assignment_archive_id) { //Archive id same
                EduClassAssignments_Teacher::find($assignment_id)->update([
                    'due_date' => Helper::dateYMD($request->due_date),
                    'due_time' => Helper::timeHi24($request->due_time)
                ]);
            } else { //Archive id change/not same
                $archive_assignment_info = EduAssignmentArchive_Teacher::valid()->find($assignment_archive_id);
                $archive_assignment_attach_info = EduAssignmentArchiveAttach_Teacher::valid()->where('assignment_archive_id', $assignment_archive_id)->first();

                EduClassAssignments_Teacher::find($assignment_id)->update([
                    'assignment_archive_id' => $assignment_archive_id,
                    'title'                 => $archive_assignment_info->title,
                    'overview'              => $archive_assignment_info->overview,
                    'due_date'              => Helper::dateYMD($request->due_date),
                    'due_time'              => Helper::timeHi24($request->due_time)
                ]);

                if (!empty($archive_assignment_attach_info)) { //Have to update/create attachment
                    $fromPath = 'uploads/archiveAssignmentAttachment';
                    $validPath = 'uploads/assignment/teacherAttachment';
                    $file_name = $archive_assignment_attach_info->file_name;
                    File::copy(public_path($fromPath.'/'.$file_name), public_path($validPath.'/'.$file_name));

                    if (!empty($attachment_info)) { //Have to update previous one
                        // UPDATE
                        File::delete(public_path($validPath.'/').$attachment_info->file_name); //delete previous file
                        EduClassAssignmentAttachments_Teacher::find($attachment_info->id)->update([
                            'archive_attach_id'   => $archive_assignment_attach_info->id,
                            'file_name'           => $file_name,
                            'file_original_name'  => $archive_assignment_attach_info->file_original_name,
                            'size'                => $archive_assignment_attach_info->size,
                            'extention'           => $archive_assignment_attach_info->extention
                        ]);
                    } else { //Have to create new one
                        // CREATE
                        EduClassAssignmentAttachments_Teacher::create([
                            'class_assignment_id' => $assignment_id,
                            'archive_attach_id'   => $archive_assignment_attach_info->id,
                            'file_name'           => $file_name,
                            'file_original_name'  => $archive_assignment_attach_info->file_original_name,
                            'size'                => $archive_assignment_attach_info->size,
                            'extention'           => $archive_assignment_attach_info->extention
                        ]);
                    }
                } else { //Have to Delete attachment
                    if (!empty($attachment_info)) {
                        $attachment_info->delete();
                    } 
                }
            }
            $output['messege'] = 'Assignment has been Updated';
            $output['msgType'] = 'success';
            DB::commit();
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
        $assignment_info = EduClassAssignments_Teacher::valid()->find($id);
        $exits_attach = EduClassAssignmentAttachments_Teacher::valid()->where('class_assignment_id',$id)->first();
        //Assignment submit status check
        $check_submission_qty = EduAssignmentSubmission_Teacher::valid()->where('assignment_id',$id)->count();

        if($check_submission_qty > 0){
            return  "Assignment already submitted by student !!";
        }else{
            if(!empty($exits_attach)){
                $validPath = 'uploads/assignment/teacherAttachment';
                File::delete(public_path($validPath.'/').$exits_attach->file_name);
                EduClassAssignmentAttachments_Teacher::valid()->find($exits_attach->id)->delete();
            }
            DB::table('edu_student_wise_class_assignments')
                ->where('batch_id', $assignment_info->batch_id)
                ->where('assign_batch_classes_id', $assignment_info->assign_batch_class_id)
                ->delete();
            $assignment_info->delete();
        }
    }

    public function assignmentThrow(Request $request){
        $assign_batch_class_id = $request->assign_batch_id;
        $assign_batch_class_info = EduAssignBatchClasses_Teacher::valid()->find($assign_batch_class_id);
        
        $class_assignment_ids = EduClassAssignments_Teacher::valid()
            ->where('assign_batch_class_id',$assign_batch_class_id)
            ->pluck('id')->toArray();

        
        $batch_students_ids = EduAssignBatchStudent_Teacher::valid()
            ->where('batch_id',$assign_batch_class_info->batch_id)
            ->where('course_id',$assign_batch_class_info->course_id)
            ->where('active_status',1)
            ->pluck('student_id')->toArray();

        
        $exits_data = EduStudentWiseClassAssignment_Teacher::valid()->where('assign_batch_classes_id',$assign_batch_class_id)->first();

        if(!empty($exits_data)){
            $output['status'] = "alert-danger";
            $output['msg'] = "Already Thrown Assignment !!";
        }else{
            if(count($batch_students_ids) > 0 && count($class_assignment_ids) > 0){
                foreach($batch_students_ids as $key => $student){
                    $random_assignment_index = array_rand($class_assignment_ids);
                    $class_assignment_id =  $class_assignment_ids[$random_assignment_index];
        
                    EduStudentWiseClassAssignment_Teacher::create([
                        "student_id"              => $student,
                        "batch_id"                => $assign_batch_class_info->batch_id,
                        "course_id"               => $assign_batch_class_info->course_id,
                        "assign_batch_classes_id" => $assign_batch_class_id,
                        "class_assignment_id"     => $class_assignment_id,
                    ]);
                }
                $output['status'] = "alert-success";
                $output['msg'] = "Throw Assignment Successfully !!";
    
            }else{
                $output['status'] = "alert-danger";
                $output['msg'] = "Something Wrong !!";
            }
        }

        return response($output);
    }
}
