<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduAssignBatchStudent_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_assign_batch_students';
    protected $fillable = ['id', 'batch_id', 'course_id', 'student_id', 'payment_system_id', 'is_running', 'active_status', 'is_freez', 'freez_reason', 'created_by', 'valid'];

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
