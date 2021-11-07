<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduClassLectureLinks_Teacher extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_class_lecture_links';
    protected $fillable = ['id', 'assign_batch_class_id', 'video_id', 'video_title', 'video_duration', 'created_by', 'valid'];

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
