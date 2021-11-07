<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduZoomAccount_Teacher extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_zoom_accounts';
    protected $fillable = ['id', 'account_type', 'name', 'email', 'password', 'token', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        $authId = Auth::guard('teacher')->id();
        return $query->where('account_type', 1)->where('created_by', $authId)->where('valid', 1);
    }
    public static function boot()
    {
        parent::EmployeeBoot(0);
    }
}
