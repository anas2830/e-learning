<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduAssignBatch_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_assign_batches';
    protected $fillable = ['id', 'course_id', 'teacher_id', 'captain_ids', 'instructor_chat_link', 'batch_no', 'start_date', 'start_time', 'batch_fb_url', 'active_status', 'complete_status', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
