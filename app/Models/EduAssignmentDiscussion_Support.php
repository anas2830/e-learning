<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduAssignmentDiscussion_Support extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_assignment_discussions';
    protected $fillable = ['id', 'taken_assignment_id', 'msg_sl', 'message', 'msg_by_type', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::EmployeeBoot(1);
    }
}
