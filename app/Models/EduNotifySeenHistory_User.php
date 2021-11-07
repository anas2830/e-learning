<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduNotifySeenHistory_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_notify_seen_histories';
    protected $fillable = ['id', 'notify_id', 'assign_batch_class_id', 'created_by', 'valid'];

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
