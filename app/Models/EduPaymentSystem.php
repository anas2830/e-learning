<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduPaymentSystem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_payment_systems';
}
