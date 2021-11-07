<?php

namespace App\Imports;

use Helper;
use Illuminate\Support\Str;
use App\Models\EduStudent_Provider;
use App\Models\EduEventSms_Provider;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable;

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

        // SEND SMS
        $event_message = EduEventSms_Provider::valid()->where('type', 1)->where('status', 1)->first();
        if(!empty($event_message)){
            $message = $event_message->message;
            if(preg_match("~\@"."name"."\@~", $message)){
                $message = str_replace("@name@", $row[0] , $message);
            }
       
            $msisdn = '0'.$row[2];
            $messageBody = $message;
            $csmsId = Str::random(12);
            Helper::singleSms($msisdn, $messageBody, $csmsId);
        }

    }
    
    public function rules(): array
    {
        return [
            '0' => 'required',
            '1' => 'unique:App\Models\User,email',
            '2' => 'unique:App\Models\User,phone',
        ];
    }

}
