<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduStudentExam_Provider extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_student_exams';
    protected $fillable = ['id','exam_config_id', 'batch_id', 'assign_batch_class_id', 'course_id', 'course_class_id', 'student_id', 'exam_duration', 'taken_duration', 'total_questions','total_answer','total_correct_answer','per_question_mark', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        $authId = Auth::id();
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::providerBoot();
    }
}
