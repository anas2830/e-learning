<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduSupport_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_supports';
    protected $fillable = ['id','support_id', 'name','address', 'email', 'phone','password','active_status','image','status','created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
