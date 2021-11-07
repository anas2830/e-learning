<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EduStudentSupportRequest_Support extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_student_support_requests';
    protected $fillable = ['id', 'category_id', 'batch_id', 'course_id', 'request_title', 'request_details', 'created_by', 'approve_status', 'supported_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::EmployeeBoot(1);
    }
}
