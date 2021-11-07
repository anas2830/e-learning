<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduArchiveQuestion_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_archive_questions';
    protected $fillable = ['id', 'question', 'course_id', 'class_id', 'answer_type', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
