<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduStudentPaymentHistory_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_stu_payment_histories';
    protected $fillable = ['id', 'payment_system_id', 'serial_no', 'amount', 'assign_batch_std_id', 'is_running', 'paid_from', 'payment_date', 'start_date', 'end_date', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}

