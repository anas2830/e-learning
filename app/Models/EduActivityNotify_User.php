<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduActivityNotify_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_activity_notifies';
    protected $fillable = ['id', 'batch_id', 'assign_batch_class_id', 'course_id', 'notify_type', 'student_id', 'notify_date', 'notify_time', 'notify_title', 'notify_link', 'created_type', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
