<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduBatchStudentRemark_Provider extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'edu_batch_student_remarks';
    protected $fillable = ['id', 'batch_id', 'course_id', 'student_id', 'remark', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        $authId = Auth::guard('provider')->id();
        return $query->where('created_by', $authId)->where('valid', 1);
    }
    public static function boot()
    {
        parent::providerBoot();
    }
}
