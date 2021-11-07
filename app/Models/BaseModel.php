<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class BaseModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    //$type = 1 means Admin Control And 2 means Provider Control
    public static function providerBoot($type=0)
    {
        if(Auth::guard('provider')->check()) {
            $authId = Auth::guard('provider')->id();
            self::bootAction($authId);
        }
    }

    public static function EmployeeBoot($type=0)
    {
        if ($type == 0) {
            if(Auth::guard('teacher')->check()) {
                $authId = Auth::guard('teacher')->id();
                self::bootAction($authId);
            } 
        } elseif ($type == 1){
            if(Auth::guard('support')->check()) {
                $authId = Auth::guard('support')->id();
                self::bootAction($authId);
            } 
        }
    }

    public static function studentBoot($type=0)
    {
        if(Auth::check()) {
            $authId = Auth::id();
            self::bootAction($authId);
        }
    }

    public static function bootAction($authId)
    {
        parent::boot();

        static::creating(function($model) use ($authId)
        {
            $model->created_by = $authId;
            $model->valid = 1;
        });

        static::deleting(function($model) use ($authId)
        {
            // $model->created_by = $authId;
            // $model->deleted_by = $authId;
            // if($type>0) { $model->updated_by_type = $type; $model->deleted_by_type = $type; }
            $model->valid = 0;
            $model->update();
        });
    }
}
