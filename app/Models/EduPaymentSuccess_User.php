<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduPaymentSuccess_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_payment_success';
    protected $fillable = ['id', 'tran_id', 'payment_history_id', 'course_id', 'std_id','amount','store_amount', 'currency', 'payment_gateway', 'payment_method', 'post_return', 'payment_response', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
