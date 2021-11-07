<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduStudentLiveClassSchedule_Provider extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_student_live_class_schedules';
    protected $fillable = ['id', 'zoom_acc_id', 'assign_batch_classes_id', 'day_dt', 'start_date', 'start_time', 'end_time', 'hour', 'min', 'duration', 'meeting_id', 'host_id', 'start_url', 'join_url', 'timezone', 'type', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::providerBoot();
    }
}
