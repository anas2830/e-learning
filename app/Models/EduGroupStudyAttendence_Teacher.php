<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduGroupStudyAttendence_Teacher extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_group_study_attendences';
    protected $fillable = ['id', 'batch_id', 'course_id', 'assign_batch_class_id', 'student_id', 'is_attend', 'remark', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::EmployeeBoot(0);
    }
}