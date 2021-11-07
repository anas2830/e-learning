<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class EduClassAssignmentAttachments_User extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_class_assignment_attachments';
    protected $fillable = ['id', 'class_assignment_id', 'archive_attach_id', 'file_name', 'file_original_name', 'size', 'extention', 'created_by', 'valid'];

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
