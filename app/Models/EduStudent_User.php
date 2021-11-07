<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduStudent_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'users';
    protected $fillable = ['id','student_id', 'name', 'sur_name', 'address', 'email', 'phone', 'backup_phone', 'fb_profile', 'image', 'password', 'active_status', 'valid'];

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
