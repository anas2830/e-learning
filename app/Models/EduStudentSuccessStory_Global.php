<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduStudentSuccessStory_Global extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_student_success_stories';
    protected $fillable = ['id','course_id', 'batch_id','work_amount', 'marketplace_name', 'own_comment','work_screenshort', 'approve_status', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
}
