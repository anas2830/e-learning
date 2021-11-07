<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduStudentPracticeTime_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_student_practice_time';
    protected $fillable = ['id','student_id', 'course_id','batch_id', 'date', 'total_time','resume_time', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        $authId = Auth::id();
        return $query->where('student_id', $authId)->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
