<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduSupportCategory_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_support_categories';
    protected $fillable = ['id', 'category_name', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::studentBoot();
    }
}
