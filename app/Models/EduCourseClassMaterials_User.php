<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduCourseClassMaterials_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'edu_course_class_materials';
    protected $fillable = ['id', 'course_id', 'class_id', 'video_id', 'video_title', 'video_duration', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}