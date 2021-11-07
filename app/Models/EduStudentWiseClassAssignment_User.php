<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduStudentWiseClassAssignment_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_student_wise_class_assignments';
    protected $fillable = ['id', 'student_id', 'batch_id', 'course_id', 'assign_batch_classes_id', 'class_assignment_id', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
