<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduCourseAssignClass_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'edu_course_assign_classes';
    protected $fillable = ['id', 'course_id', 'class_name', 'class_overview', 'class_resource', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
