<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduAssignmentComment_Teacher extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_assignment_comments';
    protected $fillable = ['id', 'assignment_submission_id', 'class_assignments_id', 'batch_id', 'course_id', 'assign_batch_class_id',
    'student_id', 'comment', 'file_name', 'file_original_name','size','extention', 'created_by', 'comment_by_type', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::EmployeeBoot(0);
    }
}
