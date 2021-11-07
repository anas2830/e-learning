<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduPaymentFailed_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_payment_failed';
    protected $fillable = ['id', 'tran_id', 'payment_history_id', 'course_id','std_id','payment_gateway','failed_reason', 'payment_response', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        $authId = Auth::id();
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
