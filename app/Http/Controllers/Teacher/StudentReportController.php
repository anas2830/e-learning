<?php

namespace App\Http\Controllers\Teacher;

use Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EduExamConfig_Teacher;
use App\Models\EduAssignBatch_Teacher;
use App\Models\EduStudentExam_Teacher;
use App\Models\EduClassAssignments_Teacher;
use App\Models\EduStudentAttendence_Teacher;
use App\Models\EduAssignBatchClasses_Teacher;
use App\Models\EduAssignBatchStudent_Teacher;
use App\Models\EduStudentPracticeTime_Teacher;
use App\Models\EduAssignmentSubmission_Teacher;
use App\Models\EduCourseClassMaterials_Teacher;
use App\Models\EduStudentVideoWatchInfo_Teacher;

class StudentReportController extends Controller
{
    public function index(Request $request)
    {
        $data['batches'] = EduAssignBatch_Teacher::valid()->where('active_status', 1)->get();
        return view('teacher.studentReport.reportForm', $data);
    }

    public function getBatchStudents(Request $request){
        $batch_id = $request->batch_id;
        $data['students'] = EduAssignBatchStudent_Teacher::join('users', 'users.id', '=', 'edu_assign_batch_students.student_id')
            ->select('users.name', 'users.id', 'users.student_id', 'users.email')
            ->where('edu_assign_batch_students.batch_id', $batch_id)
            ->get();

        return view('teacher.studentReport.batchStudents', $data);
    }

    public function studentReportOverview(Request $request)
    {
        // Attendance = attend_status
        // Class Mark = class_mark
        // Assignment Mark = assignment_mark
        // Quiz Mark = quiz_mark
        // Watched Video Duration = watched_duration
        // Practice Time = final_practice_time
        $param['batch_id']   = $batch_id   = $request->batch_id;
        $param['student_id'] = $student_id = $request->student_id;
        $param['from_date']  = $from_date  = date('Y-m-d', strtotime($request->from_date));
        $param['to_date']    = $to_date    = date('Y-m-d', strtotime($request->to_date));

        $data = self::getStudentReport($param);
        $data = array_merge($data, ['batch_id' => $batch_id, 'student_id' => $student_id, 'from_date' => $from_date, 'to_date' => $to_date]);

        return view('teacher.studentReport.reportOverview', $data);
    }

    public function studentReportPrint(Request $request)
    {
        $param['batch_id']   = $batch_id = $request->batch_id;
        $param['student_id'] = $student_id = $request->student_id;
        $param['from_date']  = $from_date = $request->from_date;
        $param['to_date']    = $to_date = $request->to_date;

        $data = self::getStudentReport($param);
        $data = array_merge($data, ['batch_id' => $batch_id, 'student_id' => $student_id, 'from_date' => $from_date, 'to_date' => $to_date]);

        return view('teacher.studentReport.reportPreview', $data);
    }

    public static function getStudentReport($param)
    {
        $batch_id   = $param['batch_id'];
        $student_id = $param['student_id'];
        $from_date  = $param['from_date'];
        $to_date    = $param['to_date'];
        $data['assign_batch_classess'] = $assign_batch_classess = EduAssignBatchClasses_Teacher::join('edu_course_assign_classes', 'edu_course_assign_classes.id', '=', 'edu_assign_batch_classes.class_id')
            ->select('edu_assign_batch_classes.id', 'edu_assign_batch_classes.class_id', 'edu_assign_batch_classes.start_date', 'edu_assign_batch_classes.end_date', 'edu_course_assign_classes.class_name')
            ->whereBetween('edu_assign_batch_classes.start_date', [$from_date, $to_date])
            ->where('edu_assign_batch_classes.batch_id', $batch_id)
            ->where('edu_assign_batch_classes.valid', 1)
            ->get();
        foreach ($assign_batch_classess as $key => $batch_class) {
            // ATTENDANCE & CLASS MARK
            $attendance_info = EduStudentAttendence_Teacher::valid()->where('student_id', $student_id)->where('class_id', $batch_class->id)->first();
            if (!empty($attendance_info)) {
                $batch_class->attend_status = $attendance_info->is_attend == 1 ? 'Present' : 'Absent';
                $batch_class->class_mark = $attendance_info->is_attend == 1 ? $attendance_info->mark : 'Absent';
            } else {
                $batch_class->attend_status = 'Not Taken';
                $batch_class->class_mark = 'Not Taken';
            }

            // ASSIGNMENTS MARK (FOR MULTIPLE)
            $batch_class->assignments = $class_assignments = EduClassAssignments_Teacher::valid()->where('batch_id', $batch_id)->where('assign_batch_class_id', $batch_class->id)->get();
            foreach ($class_assignments as $key => $assignment) {
                $assignment_submission_info = EduAssignmentSubmission_Teacher::valid()
                    ->select('mark', 'is_improve', 'mark_by')
                    ->where('assignment_id', $assignment->id)
                    ->where('created_by', $student_id)
                    ->first();
                if (!empty($assignment_submission_info)) {
                    $assignment->assignment_mark = $assignment_submission_info->mark_by != 0 ? $assignment_submission_info->mark : 'Not Checked';
                    $assignment->assignment_submit_status = $assignment_submission_info->is_improve == 1 ? 'Improve' : 'Regular';
                } else {
                    $assignment->assignment_mark = 'Not Submit';
                    $assignment->assignment_submit_status = 'Not Submit';
                }
            }

            // QUIZ MARK
            $class_exam_info = EduExamConfig_Teacher::valid()->where('assign_batch_class_id', $batch_class->id)->first();
            if (!empty($class_exam_info)) {
                $quiz_submission_info = EduStudentExam_Teacher::valid()->where('exam_config_id', $class_exam_info->id)->where('assign_batch_class_id', $batch_class->id)->where('student_id', $student_id)->first();
                if (!empty($quiz_submission_info)) {
                    $batch_class->quiz_mark = $quiz_submission_info->total_correct_answer * $quiz_submission_info->per_question_mark;
                } else {
                    $batch_class->quiz_mark = 'Not Given';
                }
            } else {
                $batch_class->quiz_mark = 'No Exam';
            }

            // CLASS VIDEO'S WATCH DURATION
            $batch_class->videos = $class_videos = EduCourseClassMaterials_Teacher::valid()->where('class_id', $batch_class->class_id)->get();
            if (!empty($class_videos)) {
                foreach ($class_videos as $key => $video) {
                    $video_watched_info = EduStudentVideoWatchInfo_Teacher::valid()->where('material_id', $video->id)->where('student_id', $student_id)->first();
                    if (!empty($video_watched_info)) {
                        $video->watched_duration = Helper::secondsToTime($video_watched_info->watch_time);
                    } else {
                        $video->watched_duration = 'Not Watched';
                    }
                }
            } else {
                $video->watched_duration = 'No Video';
            }

            // PRACTICE TIME
            $gained_practice_time = EduStudentPracticeTime_Teacher::valid()
                ->where('batch_id', $batch_id)
                ->where('created_by', $student_id)
                ->whereBetween('date', [$batch_class->start_date, $batch_class->end_date])
                ->sum('total_time');
            // CALCULATE THE TOTAL BASE TIME
            $start_class_date = strtotime($batch_class->start_date);
            $end_class_date = strtotime($batch_class->end_date);
            $total_practice_days = ceil(abs($end_class_date - $start_class_date) / 86400);
            $total_base_practice_time = $total_practice_days * 14400;
            $total_base_practice_time = round($total_base_practice_time, 2);
            
            if($gained_practice_time >= $total_base_practice_time) {
                $gained_practice_time = $total_base_practice_time;
            }

            if($gained_practice_time > 0) {
                $batch_class->base_practice_time = $total_base_practice_time;
                $batch_class->final_practice_time = $gained_practice_time;
            } else {
                $batch_class->base_practice_time = $total_base_practice_time;
                $batch_class->final_practice_time = 0;
            }
        }
        return $data;
    }
}
