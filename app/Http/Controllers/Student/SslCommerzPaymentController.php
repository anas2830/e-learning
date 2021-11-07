<?php

namespace App\Http\Controllers\Student;

use DB;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EduStudentPaymentHistory_User;
use App\Models\EduAssignBatchStudent_User;
use App\Models\EduCourses_User;
use App\Models\EduAssignBatch_User;
use App\Models\EduPaymentTemp_User;
use App\Models\EduStudent_User;
use App\Models\EduPaymentSuccess_User;
use App\libraries\SslCommerz\SslCommerzNotification;

class SslCommerzPaymentController extends Controller
{
    public function myDuePayments()
    {
        $authId = Auth::id();
        $data['assign_batch_student_info'] = $assign_batch_student_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->first();
        $data['course_info'] = EduCourses_User::valid()->find($assign_batch_student_info->course_id);
        $data['payment_histories'] = EduStudentPaymentHistory_User::valid()
            ->where('assign_batch_std_id', $assign_batch_student_info->id)
            ->where('is_running', '!=', 3)
            ->orderBy('id', 'desc')
            ->get();
        $data['total_payable'] = EduStudentPaymentHistory_User::valid()
            ->where('assign_batch_std_id', $assign_batch_student_info->id)
            ->sum('amount');
        $data['total_paid'] = EduStudentPaymentHistory_User::valid()
            ->where('assign_batch_std_id', $assign_batch_student_info->id)
            ->where('is_running', 1) //1=Paid
            ->sum('amount');
        $data['total_due'] = EduStudentPaymentHistory_User::valid()
            ->where('assign_batch_std_id', $assign_batch_student_info->id)
            ->where('is_running', '!=', 1) //1=Paid, [2,3 = Due]
            ->sum('amount');
    	$data['student_info'] = EduStudent_User::valid()->find($authId);
        return view('student.payment.myPaymentList', $data);
    }
    public function viewInvoice(Request $request)
    {
        $payment_history_id = $request->payment_history_id;
        $assign_batch_student_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->first();
        $data['batch_no'] = EduAssignBatch_User::valid()->find($assign_batch_student_info->batch_id)->batch_no;
        $data['user_info'] = EduStudent_User::valid()->find($assign_batch_student_info->student_id);

        $data['course_info'] = EduCourses_User::valid()->find($assign_batch_student_info->course_id);
        $data['payment_history_details'] = $payment_history_details = EduStudentPaymentHistory_User::valid()->where('assign_batch_std_id', $assign_batch_student_info->id)->find($payment_history_id);
        $data['payment_success'] = EduPaymentSuccess_User::valid()->where('payment_history_id', $payment_history_id)->first();
        return view('student.payment.viewInvoice', $data);
    }
    public function paymentDetails(Request $request)
    {
        $payment_history_id = $request->payment_history_id;
        $assign_batch_student_info = EduAssignBatchStudent_User::valid()->where('is_running', 1)->first();
        $data['batch_no'] = EduAssignBatch_User::valid()->find($assign_batch_student_info->batch_id)->batch_no;
        $data['user_info'] = EduStudent_User::valid()->find($assign_batch_student_info->student_id);

        $data['course_info'] = EduCourses_User::valid()->find($assign_batch_student_info->course_id);
        $data['payment_history_details'] = $payment_history_details = EduStudentPaymentHistory_User::valid()->where('assign_batch_std_id', $assign_batch_student_info->id)->find($payment_history_id);

        //payment
        $tran_id = EduPaymentTemp_User::orderBy('id', 'desc')->first();
        $tran_id = (!empty($tran_id)) ? $tran_id->tran_id + 1 : 100001;
        $data['tran_id'] = $tran_id;
        EduPaymentTemp_User::create([
            "tran_id"            => $tran_id,
            "course_id"          => $assign_batch_student_info->course_id,
            "payment_history_id" => $payment_history_id,
            "std_id"             => $assign_batch_student_info->student_id,
            "local_amount"       => $payment_history_details->amount,
            "work_status"        => 0
        ]);

        return view('student.payment.paymentDetails', $data);
    }

    public function paymentNotify(Request $request) 
    {
        
        $tran_id = $request->tran_id;
        $val_id = $request->val_id;

        $payment_temp = EduPaymentTemp_User::where('tran_id', $tran_id)->first();

        $payment_history_info = EduStudentPaymentHistory_User::valid()->find($payment_temp->payment_history_id);
        if (!empty($payment_temp)) {
            $post_data = array();
            $post_data['tran_id']            = $payment_temp->tran_id;
            $post_data['course_id']          = $payment_temp->course_id;
            $post_data['payment_history_id'] = $payment_temp->payment_history_id;
            $post_data['std_id']             = $payment_temp->std_id;
            $post_data['total_amount']       = $payment_temp->local_amount;
            $post_data['currency']           = "BDT";

            # CUSTOMER INFORMATION
            $post_data['cus_name']     = $request->cus_name;
            $post_data['cus_email']    = $request->cus_email;
            $post_data['cus_add1']     = $request->cus_add1;
            $post_data['cus_phone']    = $request->cus_phone;
            $post_data['cus_add2']     = "";
            $post_data['cus_city']     = "";
            $post_data['cus_state']    = "";
            $post_data['cus_postcode'] = "";
            $post_data['cus_country']  = "Bangladesh";
            $post_data['cus_fax']      = "";

             # SHIPMENT INFORMATION
            $post_data['ship_name'] = "Store Test";
            $post_data['ship_add1'] = "Dhaka";
            $post_data['ship_add2'] = "Dhaka";
            $post_data['ship_city'] = "Dhaka";
            $post_data['ship_state'] = "Dhaka";
            $post_data['ship_postcode'] = "1000";
            $post_data['ship_phone'] = "";
            $post_data['ship_country'] = "Bangladesh";
            $post_data['shipping_method'] = "NO";
            $post_data['product_name'] = "Computer";
            $post_data['product_category'] = "Course";
            $post_data['product_profile'] = "physical-goods";

            $sslc = new SslCommerzNotification();
            # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
            $payment_options = $sslc->makePayment($post_data, 'hosted');

            if (!is_array($payment_options)) {
                print_r($payment_options);
                $payment_options = array();
            }
        } else {
            return redirect()->route('paymentFailed')->with(['messege' => 'Transaction ID is not valid', 'back_route' => 'myDuePayments']);
        }
    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        #Check order status in order tabel against the transaction id or order id.
        $payment_temp = DB::table('edu_payment_temp')->where('tran_id', $tran_id)->first();

        if ($payment_temp->work_status == 0) {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

            if ($validation == TRUE) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                $post_return = serialize($request->all());
                DB::beginTransaction();
                $update_product = DB::table('edu_payment_temp')->where('tran_id', $tran_id)->update([
                    'work_status' => 1,
                    'post_return' => $post_return,
                ]);

                DB::table('edu_payment_success')->insert([
                    'tran_id'            => $tran_id,
                    'payment_history_id' => $payment_temp->payment_history_id,
                    'course_id'          => $payment_temp->course_id,
                    'std_id'             => $payment_temp->std_id,
                    'amount'             => $request->amount,
                    'store_amount'       => $request->store_amount,
                    'currency'           => $request->currency,
                    'payment_gateway'    => 1,
                    'payment_method'     => $request->card_type,
                    'post_return'        => $post_return,
                    'payment_response'   => $post_return,
                    'created_by'         => $payment_temp->created_by,
                    'created_at'         => date('Y-m-d H:i:s'),
                    'valid'              => 1
                ]);
                DB::table('edu_stu_payment_histories')->where('id', $payment_temp->payment_history_id)->update([
                    'is_running'   => 1,
                    'paid_from'    => 1, //1=From Student
                    'payment_date' => date('Y-m-d')
                ]);
                $running_payment_history = DB::table('edu_stu_payment_histories')->where('id', $payment_temp->payment_history_id)->first();
                $next_serial_no = $running_payment_history->serial_no + 1;
                DB::table('edu_stu_payment_histories')
                    ->where('assign_batch_std_id', $running_payment_history->assign_batch_std_id)
                    ->where('serial_no', $next_serial_no)
                    ->where('valid', 1)
                    ->update([
                        'is_running' => 2
                    ]);
                DB::commit();
                return redirect()->route('paymentSuccess')->with(['payment_history_id' =>  $payment_temp->payment_history_id ]);

                // a:30:{s:7:"tran_id";s:6:"100001";s:6:"val_id";s:26:"210421049060nSnld5weHdMvmO";s:6:"amount";s:7:"2000.00";s:9:"card_type";s:11:"BKASH-BKash";s:12:"store_amount";s:7:"1950.00";s:7:"card_no";N;s:12:"bank_tran_id";s:26:"210421049061nDUo0MwswWw57K";s:6:"status";s:5:"VALID";s:9:"tran_date";s:19:"2021-04-21 00:48:57";s:5:"error";N;s:8:"currency";s:3:"BDT";s:11:"card_issuer";s:20:"BKash Mobile Banking";s:10:"card_brand";s:13:"MOBILEBANKING";s:14:"card_sub_brand";s:7:"Classic";s:19:"card_issuer_country";s:10:"Bangladesh";s:24:"card_issuer_country_code";s:2:"BD";s:8:"store_id";s:18:"devss607d70b9bad98";s:11:"verify_sign";s:32:"f6847922f7ad6f5f154133dbb835fc72";s:10:"verify_key";s:297:"amount,bank_tran_id,base_fair,card_brand,card_issuer,card_issuer_country,card_issuer_country_code,card_no,card_sub_brand,card_type,currency,currency_amount,currency_rate,currency_type,error,risk_level,risk_title,status,store_amount,store_id,tran_date,tran_id,val_id,value_a,value_b,value_c,value_d";s:16:"verify_sign_sha2";s:64:"9cb0db172f31f56e18e8906e577e55c3b954aae80a4624f1acd9048d6a451c28";s:13:"currency_type";s:3:"BDT";s:15:"currency_amount";s:7:"2000.00";s:13:"currency_rate";s:6:"1.0000";s:9:"base_fair";s:4:"0.00";s:7:"value_a";N;s:7:"value_b";N;s:7:"value_c";N;s:7:"value_d";N;s:10:"risk_level";s:1:"0";s:10:"risk_title";s:4:"Safe";}

            } else {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                Here you need to update order status as Failed in order table.
                */
                $update_product = DB::table('edu_payment_temp')->where('tran_id', $tran_id)->update(['work_status' => 0]);
                $output['messege'] = "Validation Failed";
                $output['msgType'] = 'Failed';
                $output['back_route'] = 'myDuePayments';
                return view('student.payment.paymentFailed', $output);
            }
        } else if ($payment_temp->work_status == 1) {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
            */
            $output['messege'] = "Transaction is Aleady Completed";
            $output['msgType'] = 'Success';
            $output['back_route'] = 'home';
            return view('student.payment.paymentSuccess', $output);
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            $output['messege'] = "Invalid Transaction";
            $output['msgType'] = 'Danger';
            $output['back_route'] = 'myDuePayments';
            return view('student.payment.paymentFailed', $output);
        }
    }

    public function paymentSuccess(Request $request) {
        $payment_history_id = $request->session()->get('payment_history_id');
        $data['payment_history_info'] = $payment_history_info = EduStudentPaymentHistory_User::where("valid", 1)->where('is_running', 1)->find($payment_history_id);
        if (isset($payment_history_id)) {
            $assign_batch_student_info = EduAssignBatchStudent_User::valid()->find($payment_history_info->assign_batch_std_id);
            $data['batch_no'] = EduAssignBatch_User::valid()->find($assign_batch_student_info->batch_id)->batch_no;
            $data['user_info'] = EduStudent_User::valid()->find($assign_batch_student_info->student_id);
            $data['course_name'] = EduCourses_User::valid()->find($assign_batch_student_info->course_id)->course_name;
            $data['payment_success'] = EduPaymentSuccess_User::valid()->where('payment_history_id', $payment_history_id)->first();
            $data['messege'] = "Transaction has Successfully Completed";
            $data['msgType'] = 'Successfully Done Your Payment';
            $data['back_route'] = 'myDuePayments';
        } else {
            $data['messege'] = "Payment Not Found!!!";
            $data['msgType'] = 'Wrong';
            $data['back_route'] = 'myDuePayments';
        }
        
        return view('student.payment.paymentSuccess', $data);
    }

    public function paymentFailed(Request $request) 
    {
        $output = [];
        $tran_id = $request->input('tran_id');
        $payment_temp = DB::table('edu_payment_temp')->where('tran_id', $tran_id)->first();
        $output['back_route'] = 'myDuePayments';
        if ($payment_temp->work_status == 0) {
            DB::table('edu_payment_temp')->where('tran_id', $tran_id)->update(['work_status' => 2]);
            $output['messege'] = "Transaction is Falied";
            $output['msgType'] = 'Falied';
        } else if ($payment_temp->work_status == 1) {
            $output['messege'] = "Transaction is already Successful";
            $output['msgType'] = 'success';
        } else {
            $output['messege'] = "Transaction is Invalid";
            $output['msgType'] = 'Falied';
        }
        return view('student.payment.paymentFailed', $output);
    }

    public function paymentCancel(Request $request) 
    {
        $output = [];
        $tran_id = $request->input('tran_id');
        $payment_temp = DB::table('edu_payment_temp')->where('tran_id', $tran_id)->first();
        $output['back_route'] = 'myDuePayments';
        if ($payment_temp->work_status == 0) {
            DB::table('edu_payment_temp')->where('tran_id', $tran_id)->update(['work_status' => 3]);
            $output['messege'] = "Transaction is Falied";
            $output['msgType'] = 'danger';
        } else if ($payment_temp->work_status == 1) {
            $output['messege'] = "Transaction is already Successful";
            $output['msgType'] = 'success';
        } else {
            $output['messege'] = "Transaction is Invalid";
            $output['msgType'] = 'danger';
        }
        return view('student.payment.paymentFailed', $output);
    }
}
