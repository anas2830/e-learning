<?php

namespace App\Http\Controllers\provider;

use DB;
use Auth;
use Helper;
use Validator;
use Illuminate\Http\Request;
use App\Models\EduCourses_Provider;
use App\Http\Controllers\Controller;
use App\Models\EduAssignBatchStudent_Provider;
use App\Models\EduStudentPaymentHistory_Provider;

class StudentPaymentController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        $data['batch_student_list'] = $batch_student_list = EduAssignBatchStudent_Provider::join('edu_assign_batches', 'edu_assign_batches.id', '=', 'edu_assign_batch_students.batch_id')
            ->join('edu_courses', 'edu_courses.id', '=', 'edu_assign_batch_students.course_id')
            ->join('users', 'users.id', '=', 'edu_assign_batch_students.student_id')
            ->select('edu_assign_batch_students.*', 'edu_courses.course_name', 'edu_assign_batches.batch_no', 'users.name', 'users.phone')
            ->where('edu_assign_batch_students.active_status', 1) // 1 = This course is running.
            ->where('edu_assign_batch_students.valid', 1)
            ->get();
        if (count($batch_student_list) > 0) {
            foreach ($batch_student_list as $key => $student) {
                $student->runningPayment = EduStudentPaymentHistory_Provider::valid()
                    ->where('assign_batch_std_id', $student->id)
                    ->whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->orderBy('start_date', 'desc')
                    ->first();
            }
        }
            // Student Name, Phone, Batch,  Payment Status(Due, Done), Next Payment Amount, Last Payment Date, Done Date & Geataway name, Freez Status, Freez Action
        return view('provider.stdPayment.listData', $data);
    }

    public function courseFreez(Request $request)
    {
        $data['assign_batch_std_id'] = $assign_batch_std_id = $request->assign_batch_std_id;
        $data['assign_batch_std_info'] = EduAssignBatchStudent_Provider::valid()->where('active_status', 1)->where('is_running', 1)->find($assign_batch_std_id);
        return view('provider.stdPayment.courseFreez', $data);
    }

    public function courseFreezAction(Request $request)
    {
        $output = array();
        $assign_batch_std_id = $request->assign_batch_std_id;
        $validator = [
            'is_freez'     => 'required',
        ];
        if ($request->is_freez == 1) {
            $validator['freez_reason'] = 'required';
        }
        $validator = Validator::make($request->all(), $validator);

        if ($validator->passes()) {
            EduAssignBatchStudent_Provider::find($assign_batch_std_id)->update([
                'is_freez'     => $request->is_freez,
                'freez_reason' => $request->freez_reason
            ]);
            $status = $request->is_freez == 1 ? 'Freez' : 'Active';
            $output['messege'] = 'Students Course has been '.$status;
            $output['msgType'] = 'success';
            return redirect()->back()->with($output);
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    // student payment update
    public function updatePayment(Request $request)
    {
        $data['assign_batch_std_id'] = $assign_batch_std_id = $request->assign_batch_std_id;
        $data['assign_batch_std_info'] = EduAssignBatchStudent_Provider::valid()->where('active_status',1)->find($assign_batch_std_id);
        $data['payment_systems'] = DB::table('edu_payment_systems')->get();
        return view('provider.stdPayment.updatePayment', $data);
    }

    public function updatePaymentAction(Request $request)
    {
        $data['assign_batch_std_id'] = $assign_batch_std_id = $request->assign_batch_std_id;
        $assignme_batch_std_info =  EduAssignBatchStudent_Provider::find($assign_batch_std_id);
        $course_info = EduCourses_Provider::valid()->find($assignme_batch_std_info->course_id);
        $payment_system_id = $request->payment_system_id;
        $std_batch_start_date = date("Y-m-d", strtotime($assignme_batch_std_info->created_at));

        $validator = Validator::make($request->all(), [
            'payment_system_id' => 'required'
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();

            $assignme_batch_std_info->update([
                'payment_system_id'     => $payment_system_id,
            ]);

            $exist_history = EduStudentPaymentHistory_Provider::valid()
                ->where('assign_batch_std_id', $assign_batch_std_id)
                ->first();

            if(!empty($exist_history)){
                if($exist_history->payment_system_id != $payment_system_id){

                    DB::table('edu_stu_payment_histories')->where('assign_batch_std_id', $assign_batch_std_id)->delete();

                    switch ($payment_system_id) {
                        case "1":
                            $end_date = date ("Y-m-d", strtotime ($std_batch_start_date ."+180 days"));
                            $pay_amount = $course_info->full_payment_price;
                            EduStudentPaymentHistory_Provider::create([
                                'payment_system_id'   => $payment_system_id,
                                'serial_no'           => 1,
                                'amount'              => $pay_amount,
                                'assign_batch_std_id' => $assign_batch_std_id,
                                'is_running'          => 1,
                                'paid_from'           => 2, // 2 = Manual
                                'payment_date'        => $std_batch_start_date,
                                'start_date'          => $std_batch_start_date,
                                'end_date'            => $end_date,
                            ]);
                            break;
        
                        case "2":
                            $pay_amount = $course_info->installment_price;
                            for($i=1; $i<=2; $i++) {
        
                                if($i == 1){
        
                                    $end_date = date ("Y-m-d", strtotime ($std_batch_start_date ."+90 days"));
                                    EduStudentPaymentHistory_Provider::create([
                                        'payment_system_id'   => $payment_system_id,
                                        'serial_no'           => 1,
                                        'amount'              => $pay_amount,
                                        'assign_batch_std_id' => $assign_batch_std_id,
                                        'is_running'          => 1,
                                        'paid_from'           => 2, // 2 = Manual
                                        'payment_date'        => $std_batch_start_date,
                                        'start_date'          => $std_batch_start_date,
                                        'end_date'            => $end_date,
                                    ]);
        
                                } else {
                                    $after_3_month = date ("Y-m-d", strtotime ($std_batch_start_date ."+90 days"));
                                    $end_date = date ("Y-m-d", strtotime ($after_3_month ."+90 days"));
        
                                    EduStudentPaymentHistory_Provider::create([
                                        'payment_system_id'   => $payment_system_id,
                                        'serial_no'           => 2,
                                        'amount'              => $pay_amount,
                                        'assign_batch_std_id' => $assign_batch_std_id,
                                        'is_running'          => 2,
                                        'start_date'          => $after_3_month,
                                        'end_date'            => $end_date,
                                    ]);
                                }
        
                            }
                            break;
        
                        case "3":
                            $pay_amount = $course_info->monthly_price;
                            for($i=1; $i<=6; $i++) {
        
                                if($i == 1){
        
                                    $end_date = date ("Y-m-d", strtotime ($std_batch_start_date ."+30 days"));
                                    EduStudentPaymentHistory_Provider::create([
                                        'payment_system_id'   => $payment_system_id,
                                        'serial_no'           => 1,
                                        'amount'              => $pay_amount,
                                        'assign_batch_std_id' => $assign_batch_std_id,
                                        'is_running'          => 1,
                                        'paid_from'           => 2, // 2 = Manual
                                        'payment_date'        => $std_batch_start_date,
                                        'start_date'          => $std_batch_start_date,
                                        'end_date'            => $end_date,
                                    ]);
        
                                } else if($i == 2){
        
                                    $after_30_days = date ("Y-m-d", strtotime ($std_batch_start_date ."+30 days"));
                                    $end_date = date ("Y-m-d", strtotime ($after_30_days ."+30 days"));
        
                                    EduStudentPaymentHistory_Provider::create([
                                        'payment_system_id'   => $payment_system_id,
                                        'serial_no'           => 2,
                                        'amount'              => $pay_amount,
                                        'assign_batch_std_id' => $assign_batch_std_id,
                                        'is_running'          => 2,
                                        'payment_date'        => null,
                                        'start_date'          => $after_30_days,
                                        'end_date'            => $end_date,
                                    ]);
        
                                } else if($i == 3){
        
                                    $after_60_days = date ("Y-m-d", strtotime ($std_batch_start_date ."+60 days"));
                                    $end_date = date ("Y-m-d", strtotime ($after_60_days ."+30 days"));
        
                                    EduStudentPaymentHistory_Provider::create([
                                        'payment_system_id'   => $payment_system_id,
                                        'serial_no'           => 3,
                                        'amount'              => $pay_amount,
                                        'assign_batch_std_id' => $assign_batch_std_id,
                                        'is_running'          => 3,
                                        'payment_date'        => null,
                                        'start_date'          => $after_60_days,
                                        'end_date'            => $end_date,
                                    ]);
        
                                } else if($i == 4){
        
                                    $after_90_days = date ("Y-m-d", strtotime ($std_batch_start_date ."+90 days"));
                                    $end_date = date ("Y-m-d", strtotime ($after_90_days ."+30 days"));
        
                                    EduStudentPaymentHistory_Provider::create([
                                        'payment_system_id'   => $payment_system_id,
                                        'serial_no'           => 4,
                                        'amount'              => $pay_amount,
                                        'assign_batch_std_id' => $assign_batch_std_id,
                                        'is_running'          => 3,
                                        'payment_date'        => null,
                                        'start_date'          => $after_90_days,
                                        'end_date'            => $end_date,
                                    ]);
        
                                } else if($i == 5){
        
                                    $after_120_days = date ("Y-m-d", strtotime ($std_batch_start_date ."+120 days"));
                                    $end_date = date ("Y-m-d", strtotime ($after_120_days ."+30 days"));
        
                                    EduStudentPaymentHistory_Provider::create([
                                        'payment_system_id'   => $payment_system_id,
                                        'serial_no'           => 5,
                                        'amount'              => $pay_amount,
                                        'assign_batch_std_id' => $assign_batch_std_id,
                                        'is_running'          => 3,
                                        'payment_date'        => null,
                                        'start_date'          => $after_120_days,
                                        'end_date'            => $end_date,
                                    ]);
        
                                } else{
        
                                    $after_150_days = date ("Y-m-d", strtotime ($std_batch_start_date ."+150 days"));
                                    $end_date = date ("Y-m-d", strtotime ($after_150_days ."+30 days"));
        
                                    EduStudentPaymentHistory_Provider::create([
                                        'payment_system_id'   => $payment_system_id,
                                        'serial_no'           => 6,
                                        'amount'              => $pay_amount,
                                        'assign_batch_std_id' => $assign_batch_std_id,
                                        'is_running'          => 3,
                                        'payment_date'        => null,
                                        'start_date'          => $after_150_days,
                                        'end_date'            => $end_date,
                                    ]);
                                }
                            }
                        break;
        
                        default:
                            echo "something wrong !";
                            break;
                    }
                }
            } else{

                switch ($payment_system_id) {
                    case "1":
                        $end_date = date ("Y-m-d", strtotime ($std_batch_start_date ."+180 days"));
                        $pay_amount = $course_info->full_payment_price;
                        EduStudentPaymentHistory_Provider::create([
                            'payment_system_id'   => $payment_system_id,
                            'serial_no'           => 1,
                            'amount'              => $pay_amount,
                            'assign_batch_std_id' => $assign_batch_std_id,
                            'is_running'          => 1,
                            'paid_from'           => 2, // 2 = Manual
                            'payment_date'        => $std_batch_start_date,
                            'start_date'          => $std_batch_start_date,
                            'end_date'            => $end_date,
                        ]);
                        break;
    
                    case "2":
                        $pay_amount = $course_info->installment_price;
                        for($i=1; $i<=2; $i++) {
    
                            if($i == 1){
    
                                $end_date = date ("Y-m-d", strtotime ($std_batch_start_date ."+90 days"));
                                EduStudentPaymentHistory_Provider::create([
                                    'payment_system_id'   => $payment_system_id,
                                    'serial_no'           => 1,
                                    'amount'              => $pay_amount,
                                    'assign_batch_std_id' => $assign_batch_std_id,
                                    'is_running'          => 1,
                                    'paid_from'           => 2, // 2 = Manual
                                    'payment_date'        => $std_batch_start_date,
                                    'start_date'          => $std_batch_start_date,
                                    'end_date'            => $end_date,
                                ]);
    
                            }else{
                                $after_3_month = date ("Y-m-d", strtotime ($std_batch_start_date ."+90 days"));
                                $end_date = date ("Y-m-d", strtotime ($after_3_month ."+90 days"));
    
                                EduStudentPaymentHistory_Provider::create([
                                    'payment_system_id'   => $payment_system_id,
                                    'serial_no'           => 2,
                                    'amount'              => $pay_amount,
                                    'assign_batch_std_id' => $assign_batch_std_id,
                                    'is_running'          => 2,
                                    'start_date'          => $after_3_month,
                                    'end_date'            => $end_date,
                                ]);
                            }
    
                        }
                        break;
    
                    case "3":
                        $pay_amount = $course_info->monthly_price;
                        for($i=1; $i<=6; $i++) {
    
                            if($i == 1){
    
                                $end_date = date ("Y-m-d", strtotime ($std_batch_start_date ."+30 days"));
                                EduStudentPaymentHistory_Provider::create([
                                    'payment_system_id'   => $payment_system_id,
                                    'serial_no'           => 1,
                                    'amount'              => $pay_amount,
                                    'assign_batch_std_id' => $assign_batch_std_id,
                                    'is_running'          => 1,
                                    'paid_from'           => 2, // 2 = Manual
                                    'payment_date'        => $std_batch_start_date,
                                    'start_date'          => $std_batch_start_date,
                                    'end_date'            => $end_date,
                                ]);
    
                            } else if($i == 2){
    
                                $after_30_days = date ("Y-m-d", strtotime ($std_batch_start_date ."+30 days"));
                                $end_date = date ("Y-m-d", strtotime ($after_30_days ."+30 days"));
    
                                EduStudentPaymentHistory_Provider::create([
                                    'payment_system_id'   => $payment_system_id,
                                    'serial_no'           => 2,
                                    'amount'              => $pay_amount,
                                    'assign_batch_std_id' => $assign_batch_std_id,
                                    'is_running'          => 2,
                                    'payment_date'        => null,
                                    'start_date'          => $after_30_days,
                                    'end_date'            => $end_date,
                                ]);
    
                            } else if($i == 3){
    
                                $after_60_days = date ("Y-m-d", strtotime ($std_batch_start_date ."+60 days"));
                                $end_date = date ("Y-m-d", strtotime ($after_60_days ."+30 days"));
    
                                EduStudentPaymentHistory_Provider::create([
                                    'payment_system_id'   => $payment_system_id,
                                    'serial_no'           => 3,
                                    'amount'              => $pay_amount,
                                    'assign_batch_std_id' => $assign_batch_std_id,
                                    'is_running'          => 3,
                                    'payment_date'        => null,
                                    'start_date'          => $after_60_days,
                                    'end_date'            => $end_date,
                                ]);
    
                            } else if($i == 4){
    
                                $after_90_days = date ("Y-m-d", strtotime ($std_batch_start_date ."+90 days"));
                                $end_date = date ("Y-m-d", strtotime ($after_90_days ."+30 days"));
    
                                EduStudentPaymentHistory_Provider::create([
                                    'payment_system_id'   => $payment_system_id,
                                    'serial_no'           => 4,
                                    'amount'              => $pay_amount,
                                    'assign_batch_std_id' => $assign_batch_std_id,
                                    'is_running'          => 3,
                                    'payment_date'        => null,
                                    'start_date'          => $after_90_days,
                                    'end_date'            => $end_date,
                                ]);
    
                            } else if($i == 5){
    
                                $after_120_days = date ("Y-m-d", strtotime ($std_batch_start_date ."+120 days"));
                                $end_date = date ("Y-m-d", strtotime ($after_120_days ."+30 days"));
    
                                EduStudentPaymentHistory_Provider::create([
                                    'payment_system_id'   => $payment_system_id,
                                    'serial_no'           => 5,
                                    'amount'              => $pay_amount,
                                    'assign_batch_std_id' => $assign_batch_std_id,
                                    'is_running'          => 3,
                                    'payment_date'        => null,
                                    'start_date'          => $after_120_days,
                                    'end_date'            => $end_date,
                                ]);
    
                            } else{
    
                                $after_150_days = date ("Y-m-d", strtotime ($std_batch_start_date ."+150 days"));
                                $end_date = date ("Y-m-d", strtotime ($after_150_days ."+30 days"));
    
                                EduStudentPaymentHistory_Provider::create([
                                    'payment_system_id'   => $payment_system_id,
                                    'serial_no'           => 6,
                                    'amount'              => $pay_amount,
                                    'assign_batch_std_id' => $assign_batch_std_id,
                                    'is_running'          => 3,
                                    'payment_date'        => null,
                                    'start_date'          => $after_150_days,
                                    'end_date'            => $end_date,
                                ]);
                            }
                        }
                    break;
    
                    default:
                        echo "something wrong !";
                        break;
                }
            }

            DB::commit();

            $output['messege'] = 'Update Payment Successfully';
            $output['msgType'] = 'success';

            return redirect()->back()->with($output);
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    public function paymentHistory(Request $request)
    {
        $data['assign_batch_std_id'] = $assign_batch_std_id = $request->assign_batch_std_id;
        $data['student_name'] = EduAssignBatchStudent_Provider::join('users', 'users.id', '=', 'edu_assign_batch_students.student_id')
            ->select('users.name')
            ->where('edu_assign_batch_students.id', $assign_batch_std_id)
            ->first()->name;
        $data['payment_histories'] = EduStudentPaymentHistory_Provider::valid()
            ->where('assign_batch_std_id', $assign_batch_std_id)
            ->get();
        return view('provider.stdPayment.paymentHistory.listData', $data);
    }

    public function stdPaymentManual(Request $request)
    {
        $data['pay_history_id'] = $pay_history_id = $request->pay_history_id;
        $data['payment_history_info'] = EduStudentPaymentHistory_Provider::valid()->find($pay_history_id);
        return view('provider.stdPayment.paymentHistory.manualPayment', $data);
    }
    
    public function stdPaymentManualAction(Request $request)
    {
        $output = array();
        $today = date('Y-m-d');
        $pay_history_id = $request->pay_history_id;
        $validator = Validator::make($request->all(), [
            'is_running' => 'required'
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            $running_payment_history = EduStudentPaymentHistory_Provider::find($pay_history_id);

            if ($running_payment_history->start_date <= $today && $running_payment_history->end_date >= $today) {
                $running_payment_history->update([
                    'is_running'   => $request->is_running,
                    'payment_date' => $today,
                    'paid_from'    => 2 // 2 = Manual
                ]);
                $next_serial_no = $running_payment_history->serial_no + 1;
                $next_payment_history = EduStudentPaymentHistory_Provider::valid()
                    ->where('assign_batch_std_id', $running_payment_history->assign_batch_std_id)
                    ->where('serial_no', $next_serial_no)
                    ->first();
                if (!empty($next_payment_history)) {
                    $next_payment_history->update([
                        'is_running' => 2
                    ]);
                }
            } else {
                $output['messege'] = 'This Payment is not Live Yet!!';
                $output['msgType'] = 'danger';
            }
            // 2021-05-07
            // 2021-06-06
            DB::commit();
            $output['messege'] = 'Students installment has been Paid';
            $output['msgType'] = 'success';
            return redirect()->back()->with($output);
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }
}
