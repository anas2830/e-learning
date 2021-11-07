<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduExamConfig_Teacher extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'edu_exam_configs';
    protected $fillable = ['id', 'batch_id', 'assign_batch_class_id', 'exam_overview', 'exam_duration', 'questions', 'total_question', 'per_question_mark', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::EmployeeBoot(0);
    }
}
