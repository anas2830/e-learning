<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduAssignmentSubmission_Provider extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_assignment_submissions';
    protected $fillable = ['id', 'assignment_id', 'comment', 'submission_date','submission_time', 'late_submit', 'is_improve', 'mark', 'mark_from', 'mark_by', 'mark_by_type', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::providerBoot();
    }

}
