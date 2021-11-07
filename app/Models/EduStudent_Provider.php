<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduStudent_Provider extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'users';
    protected $fillable = ['id', 'student_id', 'sur_name', 'name', 'address', 'email','password', 'phone', 'backup_phone', 'fb_profile', 'image','active_status', 'created_by', 'valid'];

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
