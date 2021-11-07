<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduAssingedSupportCategory_Support extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_assinged_support_categories';
    protected $fillable = ['id', 'support_id', 'support_category_id', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
    public static function boot()
    {
        parent::EmployeeBoot(1);
    }
}
