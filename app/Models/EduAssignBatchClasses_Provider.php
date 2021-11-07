<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;


class EduAssignBatchClasses_Provider extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_assign_batch_classes';
    protected $fillable = ['id', 'batch_id', 'course_id', 'class_id', 'start_date', 'start_time', 'end_date', 'complete_status', 'drive_link', 'created_by', 'valid'];

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