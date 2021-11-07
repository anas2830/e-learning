<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduAssignmentArchiveAttach_Teacher extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_assignment_archive_attaches';
    protected $fillable = ['id', 'assignment_archive_id', 'file_name', 'file_original_name', 'size', 'extention', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
}
