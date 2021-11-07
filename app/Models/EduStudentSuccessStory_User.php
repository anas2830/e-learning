<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduStudentSuccessStory_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_student_success_stories';
    protected $fillable = ['id','course_id', 'batch_id','work_amount', 'marketplace_name', 'own_comment','work_screenshort', 'approve_status', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        $authId = Auth::id();
        return $query->where('created_by', $authId)->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
