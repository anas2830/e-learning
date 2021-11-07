<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduStudentWidgetTeacher_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_student_widget_teachers';
    protected $fillable = ['id', 'title', 'overview', 'type', 'batch_id', 'course_id', 'student_id', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
