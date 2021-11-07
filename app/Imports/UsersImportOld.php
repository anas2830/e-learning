<?php

namespace App\Imports;

use App\Models\EduStudent_Provider;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Helper;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        return new EduStudent_Provider([
            'student_id'   => Helper::generateAutoID('users','student_id'),
            'name'         => $row[0],
            'email'        => $row[1], 
            'phone'        => '0'.$row[2], 
            'address'      => $row[3], 
            'backup_phone' => $row[4], 
            'fb_profile'   => $row[5], 
            'password'     => Hash::make('123456789'),
        ]);
    }

}
