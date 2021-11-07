<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduClassRequest_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_class_requests';
    protected $fillable = ['id', 'batch_id', 'assign_batch_class_id', 'request_reasons', 'approve_status', 'supported_by', 'response', 'class_link', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        $authId = Auth::id();
        return $query->where('created_by', $authId)->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
