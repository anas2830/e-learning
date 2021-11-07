<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduStudentWidget_Teacher extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_student_widget_teachers';
    protected $fillable = ['id', 'title', 'overview', 'type', 'batch_id', 'course_id', 'student_id', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        $authId = Auth::guard('teacher')->id();
        return $query->where('created_by', $authId)->where('valid', 1);
    }
    public static function boot()
    {
        parent::EmployeeBoot(0);
    }
}
