<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduStudentAttendence_Provider extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'edu_student_attendences';
    protected $fillable = ['id', 'batch_id', 'course_id', 'class_id', 'student_id', 'is_attend', 'mark', 'remark', 'user_type', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::providerBoot();
    }
}
