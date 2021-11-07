<?php

namespace App\Http\Controllers\Student;

use Auth;
use Helper;
use Validator;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EduCourses_User;
use App\Models\EduAssignBatch_User;
use App\Models\EduStudentExam_User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\EduActivityNotify_User;
use App\Models\EduStudentWidgets_User;
use App\Models\EduClassAssignments_User;
use App\Models\EduNotifySeenHistory_User;
use App\Models\EduStudentAttendence_User;
use App\Models\EduAssignBatchClasses_User;
use App\Models\EduAssignBatchStudent_User;
use App\Models\EduStudentPerformance_User;
use App\Models\EduStudentPerformance_View;
use App\Models\EduStudentPracticeTime_User;
use App\Models\EduAssignmentSubmission_User;
use App\Models\EduCourseClassMaterials_User;
use App\Models\EduStudentWidgetTeacher_User;
use App\Models\EduStudentSuccessStory_Global;
use App\Models\EduStudentVideoWatchInfo_User;
use App\Models\EduStudentWidgetsProvider_User;
use App\Models\EduStdSuccessStoryReactions_User;
use App\Models\EduStdSuccessStoryReactions_Global;

class MasterController extends Controller
{
    public function logout()
    {
        Auth::guard('student')->logout();
        return redirect()->route('student.login');

    }
    // first time show all courses
    public function courses(Request $request)
    {
        $authId = Auth::id();
        $data['all_courses'] = $all_courses = EduAssignBatchStudent_User::valid()->where('student_id',$authId)->where('active_status',1)->get();
        if(count($all_courses) > 0){
            foreach($all_courses as $Key => $course){
                $course->courseInfo = EduCourses_User::valid()->where('id',$course->course_id)->first();
            }
        }
        return view('layouts.courses', $data);

    }

    public function updateRunningCourse(Request $request){
        $course_id = $request->course_id;
        EduAssignBatchStudent_User::valid()->where('active_status',1)->update([
            "is_running" => 0
        ]);

        EduAssignBatchStudent_User::valid()->where('active_status',1)->where('course_id',$course_id)->update([
            "is_running" => 1
        ]);

        echo "Update Successfully";
    }
    /// first time all courses
    public function home(){

        $authId = Auth::id();
        $current_date = date('Y-m-d');
        $data['userInfo'] = User::where('valid', 1)->find($authId);
        $today_practice_time = EduStudentPracticeTime_User::valid()
            ->where('student_id', $authId)
            ->whereDate('date',$current_date)
            ->first();

        if(!empty($today_practice_time)){
            $today_practice_time = @Helper::secondsToTime($today_practice_time->total_time);
            $time_array = explode(':',$today_practice_time);
            $data['hour'] = $time_array[0];
            $data['minute'] = $time_array[1];
            $data['seconds'] = $time_array[2];
        }else{
            $data['today_practice_time'] = '';
            $data['hour'] = 00;
            $data['minute'] = 00;
            $data['seconds'] = 00;
        }

        $data['widgets'] = EduStudentWidgets_User::valid()->latest()->limit(3)->get();
        $data['student_course_info'] = $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
        if(!empty($student_course_info)){
            $data['running_course_info'] = EduCourses_User::valid()->find($student_course_info->course_id);
            $data['assigned_batch_info'] = EduAssignBatch_User::valid()->find($student_course_info->batch_id);

            $data['upcomming_class'] = EduAssignBatchClasses_User::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
                ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name')
                ->where('edu_assign_batch_classes.valid', 1)
                ->where('edu_assign_batch_classes.complete_status', 2)  // 2 = running/upcomming
                ->where('edu_assign_batch_classes.batch_id', $student_course_info->batch_id)
                ->where('edu_assign_batch_classes.course_id', $student_course_info->course_id)
                ->first();

            $data['completed_class'] = EduAssignBatchClasses_User::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
                ->select('edu_assign_batch_classes.*', 'edu_course_assign_classes.class_name')
                ->where('edu_assign_batch_classes.valid', 1)
                ->where('edu_assign_batch_classes.complete_status', 1)  // 1 = complete
                ->where('edu_assign_batch_classes.batch_id', $student_course_info->batch_id)
                ->where('edu_assign_batch_classes.course_id', $student_course_info->course_id)
                ->orderBy('edu_assign_batch_classes.start_date','DESC')
                ->first();

            $teacher_personal_news = EduStudentWidgetTeacher_User::valid()
                ->where('batch_id',$student_course_info->batch_id)
                ->where('course_id',$student_course_info->course_id)
                ->where('student_id',$student_course_info->student_id)
                ->where('type',3) // 2 = student wise
                ->latest()
                ->limit(3)
                ->get()->toArray();

            $provider_personal_news = EduStudentWidgetsProvider_User::valid()
                ->where('batch_id',$student_course_info->batch_id)
                ->where('course_id',$student_course_info->course_id)
                ->where('student_id',$student_course_info->student_id)
                ->where('type',3) // 2 = student wise
                ->latest()
                ->limit(3)
                ->get()->toArray();

            $data['all_personal_news'] = array_merge($teacher_personal_news,$provider_personal_news);

            $all_teacher_batch_news = EduStudentWidgetTeacher_User::valid()
                ->where('batch_id',$student_course_info->batch_id)
                ->where('course_id',$student_course_info->course_id)
                ->where('type',2)
                ->latest()
                ->limit(3)
                ->get()->toArray();

            $all_teacher_course_news = EduStudentWidgetTeacher_User::valid()
                ->where('course_id',$student_course_info->course_id)
                ->where('type',1)
                ->latest()
                ->limit(3)
                ->get()->toArray();

            $data['all_teacher_news'] = array_merge($all_teacher_batch_news,$all_teacher_course_news);

            $all_provider_batch_news = EduStudentWidgetsProvider_User::valid()
                ->where('batch_id',$student_course_info->batch_id)
                ->where('course_id',$student_course_info->course_id)
                ->where('type',2)
                ->latest()
                ->limit(3)
                ->get()->toArray();

            $all_provider_course_news = EduStudentWidgetsProvider_User::valid()
                ->where('course_id',$student_course_info->course_id)
                ->where('type',1)
                ->latest()
                ->limit(3)
                ->get()->toArray();

            $data['all_provider_news'] = array_merge($all_provider_batch_news,$all_provider_course_news);

            // graph
            $authId = Auth::id();
            $student_course_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->where('active_status', 1)->first();
            $std_batch_id = $student_course_info->batch_id;
            $std_course_id = $student_course_info->course_id;

            $all_assign_classes = EduAssignBatchClasses_User::valid()
                ->where('batch_id', $std_batch_id)
                ->where('course_id', $std_course_id)
                ->where('complete_status', 1)
                ->orderBy('id', 'DESC')
                ->limit(30)
                ->get()->toArray();

            $serialId = array_column($all_assign_classes, 'id');
            array_multisort($serialId, SORT_ASC, $all_assign_classes);

            $all_assign_classes = collect($all_assign_classes)->map(function ($item) {
                return (object) $item;
            });

            $data['all_assign_classes'] = $all_assign_classes;
            foreach($all_assign_classes as $key => $assign_class){

                $assign_class->std_class_name = Helper::className($assign_class->class_id);

                // $studentPerforms = EduStudentPerformance_View::where('batch_id', $std_batch_id)
                //     ->where('assign_batch_class_id', $assign_class->id)
                //     ->where('student_id', $authId)
                //     ->first();
                $studentPerforms = EduStudentPerformance_User::valid()
                    ->where('batch_id', $std_batch_id)
                    ->where('assign_batch_classes_id', $assign_class->id)
                    ->where('student_id', $authId)
                    ->first();
                    
                if(!empty($studentPerforms)){
                    $assign_class->std_class_practiceTime = $studentPerforms->practice_time != null ? $studentPerforms->practice_time : 0;
                    $assign_class->std_class_video        = $studentPerforms->video_watch_time != null ? $studentPerforms->video_watch_time : 0;
                    $assign_class->std_class_attend       = $studentPerforms->attendence != null ? $studentPerforms->attendence : 0;
                    $assign_class->std_class_mark         = $studentPerforms->class_mark != null ? $studentPerforms->class_mark : 0;
                    $assign_class->std_class_assignment   = $studentPerforms->assignment != null ? $studentPerforms->assignment : 0;
                    $assign_class->std_class_exam         = $studentPerforms->quiz != null ? $studentPerforms->quiz : 0;
                } else {
                    $assign_class->std_class_practiceTime = 0;
                    $assign_class->std_class_video        = 0;
                    $assign_class->std_class_attend       = 0;
                    $assign_class->std_class_mark         = 0;
                    $assign_class->std_class_assignment   = 0;
                    $assign_class->std_class_exam         = 0;
                }
            }
            // end graph
        } else {
            $data['assigned_batch_info'] = '';
            $data['upcomming_class'] = [];
            $data['completed_class'] = [];
            $data['all_personal_news'] = [];
            $data['all_batch_news'] = [];
            $data['all_provider_news'] = [];
            $data['all_teacher_news'] = [];
            $data['all_assign_classes'] = [];
            $data['running_course_info'] = [];
        }

        // STUDENT SUCCESS STORY
        $data['student_success_stories'] = $student_success_stories = EduStudentSuccessStory_Global::valid()
            ->where('approve_status', 1)
            ->latest()->limit(15)->get();
        foreach ($student_success_stories as $key => $story_item) {
            $story_item->total_reaction = EduStdSuccessStoryReactions_Global::valid()
            ->where('success_story_id', $story_item->id)
            ->where('react_status', 1)
            ->count();
        }
        $data['student_total_success'] = EduStudentSuccessStory_Global::valid()
            ->where('approve_status', 1)
            ->count();

        return view('student.home', $data);

    }

    public function notifySeen(Request $request){
        $authId = Auth::id();
        $notify_id = $request->notify_id;
        $notify_info = EduActivityNotify_User::valid()->find($notify_id);

        if($notify_info->assign_batch_class_id != null){
            $assign_batch_class_id = $notify_info->assign_batch_class_id;
        }else{
            $assign_batch_class_id = null;
        }

        $check_seen = EduNotifySeenHistory_User::valid()->where('notify_id',$notify_id)->where('created_by',$authId)->first();
        if(empty($check_seen)){
            EduNotifySeenHistory_User::create([
                'notify_id'    => $notify_id,
                'assign_batch_class_id' => $assign_batch_class_id
            ]);
            $output['message'] = "Seen status update successfully";
        }else{
            $output['message'] = 'alerady seen';
        }

        return response($output);
    }
    
    // SUCCESS STORY REACTION UPDATE
    public function storyReactUpdate(Request $request)
    {
        $output = [];
        $output['auth'] = Auth::id();
        $success_story_id = $request->success_story_id;
        $isReactionExist = EduStdSuccessStoryReactions_User::valid()->where('success_story_id', $success_story_id)->first();
        
        if (!empty($isReactionExist)) {
            $isReactionExist->update([
                "react_status" => $isReactionExist->react_status == 1 ? 0 : 1
            ]);
            $total_reactions = EduStdSuccessStoryReactions_Global::valid()
                ->where('success_story_id', $success_story_id)
                ->where('react_status', 1)
                ->get()->count();
            $output['total_reactions'] = $total_reactions > 0 ? $total_reactions : 0;
            $output['status'] = 1;
            $output['message'] = 'React Updated';
        } else {
            EduStdSuccessStoryReactions_User::create([
                "success_story_id" => $success_story_id,
                "react_status"     => 1
            ]);
            $total_reactions = EduStdSuccessStoryReactions_Global::valid()
                ->where('success_story_id', $success_story_id)
                ->where('react_status', 1)
                ->get()->count();
            $output['total_reactions'] = $total_reactions > 0 ? $total_reactions : 0;
            $output['status'] = 1;
            $output['message'] = 'React Added';
        }
        
        return response($output);
    }

    public function stdSuccessIndex(Request $request)
    {
        return view('student.successStoryIndex');
    }

    public function stdSuccessListAjax(Request $request)
    {
        $items_per_group = $request->items_per_group;
        $group_number = $request->group_number;
        $group_number = (empty($group_number)) ? 0 : $group_number;
        $position = ($group_number * $items_per_group);
        // STUDENT SUCCESS STORY
        $student_success_stories = EduStudentSuccessStory_Global::valid()
            ->where('approve_status', 1)
            ->orderBy('id', 'desc');
        $result_count = $student_success_stories->count();
        $student_success_stories = $student_success_stories->skip($position)->take($items_per_group)->get();

        foreach ($student_success_stories as $key => $story_item) {
            $story_item->total_reaction = EduStdSuccessStoryReactions_Global::valid()
            ->where('success_story_id', $story_item->id)
            ->where('react_status', 1)
            ->count();
        }
        $data['student_success_stories'] = $student_success_stories;
        return $result_count . '/~.~/' . view('student.successStoryAjaxList', $data);
    }

}
