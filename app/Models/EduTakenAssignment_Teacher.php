<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduTakenAssignment_Teacher extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_taken_assignments';
    protected $fillable = ['id','batch_id', 'course_id','assign_batch_class_id', 'class_assignment_id', 'assignment_submission_id','student_id','taken_date','taken_time','expire_date', 'expire_time', 'review_status', 'taken_by_type', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::EmployeeBoot(0);
    }
}
