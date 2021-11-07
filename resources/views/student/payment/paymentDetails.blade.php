@extends('layouts.default')

@section('content')
<!-- Content area -->
<div class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="panel invoice-grid border-left-lg border-left-primary">
                <div class="panel-body">
                    <form type="create" id="paymentConfirmForm" action="#" callback="callFormRefresh" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 control-label">Currency</label>
                            <div class="col-lg-7 col-md-6">
                                <input name="currency" placeholder="Currency" class="form-control" readonly="" value="BDT(৳)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 control-label">Amount</label>
                            <div class="col-lg-7 col-md-6">
                                <input name="amount" placeholder="Amount" class="form-control" readonly="" value="{{ $payment_history_details->amount }}">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="ssl_ing">
                    <img src="{{ asset('web/img/SSLCommerz-Pay-With-logo-All-Size-05.png') }}" alt="" width="100%" style="width: 100%">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel invoice-grid border-left-lg border-left-primary">
                <div class="panel-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td width="30%">Course Name</td>
                                <td width="5%">:</td>
                                <td>{{ $course_info->course_name }}</td>
                            </tr>
                            <tr>
                                <td width="30%">Batch Name</td>
                                <td width="5%">:</td>
                                <td>{{ $batch_no }}</td>
                            </tr>        
                            <tr>
                                <td width="30%">Installment Type</td>
                                <td width="5%">:</td>
                                <td>
                                    @if($payment_history_details->payment_system_id == 1)
                                        Full Payment
                                    @elseif($payment_history_details->payment_system_id == 2)
                                        Installment Payment
                                    @else
                                        Monthly Payment
                                    @endif
                                </td>
                            </tr>        
                            <tr>
                                <td width="30%">Start Date</td>
                                <td width="5%">:</td>
                                <td>{{ date("jS F, Y", strtotime($payment_history_details->start_date)) }}</td>
                            </tr>        
                            <tr>
                                <td width="30%">End Date</td>
                                <td width="5%">:</td>
                                <td>{{ date("jS F, Y", strtotime($payment_history_details->end_date)) }}</td>
                            </tr> 
                            <tr>
                                <td width="30%">Total Amount</td>
                                <td width="5%">:</td>
                                <td style="font-size: 18px;font-weight: 600;">{{ $payment_history_details->amount }}</td>
                            </tr>          
                        </tbody>
                    </table>
                    <form id="payment_gw" name="payment_gw" method="POST" action="{{ route('paymentNotify') }}">
                        @csrf
                        <div class="form-group"> 
                            <div class="col-lg-12 col-md-12 pl0 mb10">
                                <input type="checkbox" data-fv-icon="false" name="terms_condition" id="terms_condition" value=1 style="opacity:initial;">
                                <label class="control-label mr5" for="terms_condition">I agreed with <a href="https://www.lfwfacademy.com/terms-of-service" target="_blank" style="color: #0c89f5;">Terms of use </a>and<a href="https://www.lfwfacademy.com/privacy-policy" target="_blank" style="color: #0c89f5;"> Privacy Policy</a></label>
                            </div>
                        </div>
                        
                        <input name="total_amount" value="{{$payment_history_details->amount}}" type="hidden">
                        <input name="currency" value="BDT" type="hidden">
                        <input name="tran_id" value="{{$tran_id}}" type="hidden">
                        <input name="cus_name" value="{{ $user_info->name }}" type="hidden">
                        <input name="cus_email" value="{{ $user_info->email }}" type="hidden">
                        <input name="cus_add1" value="Dhaka" type="hidden">
                        <input name="cus_phone" value="{{ $user_info->phone  }}" type="hidden">

                        <button disabled href="payment_confirm" type="submit" id="make_confirm" class="btn btn-success btn-block" style="padding: 10px 5px; font-weight: bold; margin-top: 20px;">Make Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /content area -->
@endsection
@push('javascript')
<script>
    $(document).ready(function () {
        $('#terms_condition').on('click', function(){
            if (!$(this).is(':checked')) {
                $("#make_confirm").attr('disabled', true);
            } else {
                $("#make_confirm").attr('disabled', false);
            }
        });
    });
</script>
@endpush