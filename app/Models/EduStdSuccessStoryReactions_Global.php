<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EduStdSuccessStoryReactions_Global extends BaseModel
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'edu_std_success_story_reactions';
    protected $fillable = ['id', 'success_story_id', 'react_status', 'created_by', 'valid'];

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
}