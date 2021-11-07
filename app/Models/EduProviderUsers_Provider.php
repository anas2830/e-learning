<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduProviderUsers_Provider extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_provider_users';
    protected $fillable = ['id', 'name', 'email', 'password', 'address', 'phone', 'image', 'valid'];

    public function scopeValid($query)
    {
        $authId = Auth::guard('provider')->id();
        return $query->where('id', $authId)->where('valid', 1);
    }
    public static function boot()
    {
        parent::providerBoot();
    }
}
