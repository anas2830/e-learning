<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduAnswer_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_answers';
    protected $fillable = ['id', 'question_id', 'answer', 'true_answer', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
