<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduFilePond_Global extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'edu_file_ponds';
    protected $fillable = ['id', 'file_name', 'folder_name', 'file_original_name', 'size', 'extention', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

}
