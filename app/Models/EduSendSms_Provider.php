<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Auth;

class EduSendSms_Provider extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_send_sms';
    protected $fillable = ['id', 'batch_id', 'course_id', 'sms_receiver_id', 'sms_receiver_type', 'message', 'date', 'created_by', 'valid'];

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
