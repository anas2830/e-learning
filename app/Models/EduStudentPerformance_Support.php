<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduStudentPerformance_Support extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_student_performances';
    protected $fillable = ['id', 'student_id', 'course_id', 'course_class_id', 'batch_id', 'assign_batch_classes_id', 'practice_time', 'video_watch_time', 'attendence', 'class_mark', 'assignment', 'quiz','created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::EmployeeBoot(1);
    }
}
