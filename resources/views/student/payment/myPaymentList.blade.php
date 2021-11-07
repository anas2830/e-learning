@extends('layouts.default')

@push('styles')
<style>
    .data-list li a {
        color: white!important;
    }

    .panel.my-payment-wallet {
        position: relative;
    }


    .panel.my-payment-wallet::after {
        content: "\ea77";
        position: absolute;
        left: 25px;
        top: -18px;
        font-family: 'icomoon';
        font-size: 80px;
        opacity: .3;
    }
</style>
@endpush

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li class="active"><a href="{{route('myDuePayments')}}"><i class="icon-cash3 position-left"></i> Due Payment</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->
<!-- Content area -->
<div class="content">
    <div class="row">
        <div class="col-md-9">
            @if (count($payment_histories) > 0)
                @foreach ($payment_histories as $key => $history)
                    <div class="col-md-12">
                        <div class="panel invoice-grid border-left-lg border-left-primary">
                            <div class="panel-body">
                                @if ($history->is_running == 2)
                                <div class="col-md-12">  
                                    <h5 class="text-semibold no-margin-top text-center text-orange-800">
                                        {{Helper::getRemainingDays($history->start_date)}} days left for payment, Otherwise your account will be freezed. 
                                    </h5>  
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h6 class="text-semibold no-margin-top">{{ $course_info->course_name }}</h6>
                                        <ul class="list list-unstyled">
                                            <li>Installment No: &nbsp; #0{{ $history->serial_no }}</li>
                                            <li>Issued on: <span class="text-semibold">{{ date("jS F, Y", strtotime($history->start_date)) }}</span></li>
                                        </ul>
                                    </div>
            
                                    <div class="col-sm-6">
                                        <h6 class="text-semibold text-right no-margin-top">{{ $history->amount }}</h6>
                                        <ul class="list list-unstyled text-right">
                                            <li>Method: <span class="text-semibold">{{ Helper::getPaymentSystemName($history->payment_system_id) }}</span></li>
                                            <li class="dropdown">
                                                Status: &nbsp;
                                                @if($history->is_running == 1)
                                                    <a href="#" class="label bg-success-400 dropdown-toggle">Successfully Paid</a>
                                                @elseif($history->is_running == 2) 
                                                    <a href="#" class="label bg-warning-400 dropdown-toggle">not paid</a>
                                                @else 
                                                    <a href="#" class="label bg-info-400 dropdown-toggle">Upcomming</a>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <ul>
                                    <li>Last Date: <span class="text-semibold">{{ date("jS F, Y", strtotime($history->end_date)) }}</span></li>
                                </ul>
            
                                <ul class="pull-right data-list">
                                        <li><button class="btn btn-lg btn-info btn-labeled open-modal" type="button" modal-title="Invoice of Installment #0{{ $history->serial_no }}" modal-type="show" modal-size="large" modal-class="" selector="Invoice" modal-link="{{route('viewInvoice', ['payment_history_id' => $history->id])}}"><b><i class="icon-eye"></i></b> View Invoice</button></li>
                                    @if($history->is_running == 1)
                                        <li><button class="btn btn-lg btn-success btn-labeled"><b><i class="icon-cash3"></i></b> Successfully Paid</button></li>
                                    @elseif($history->is_running == 2) 
                                        <li><a href="{{route('paymentDetails', ['payment_history_id'=>$history->id])}}" class="btn btn-lg bg-teal-400 btn-labeled"><b><i class="icon-cash3"></i></b> Pay Now</a></li>
                                    @else 
                                        <li><button href="#" class="btn btn-lg bg-teal-400 btn-labeled"><b><i class="icon-cash3"></i></b> Upcomming</button></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="panel panel-white">
                    <div class="panel-body">
                        <h6 class="panel-title text-center">Payment Not Found !!!</h6>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-3 col-lg-3">
            <div class="panel panel bg-indigo-400 my-payment-wallet">
                <div class="panel-body text-right">
                    @if ($assign_batch_student_info->payment_system_id == 1)
                        <h3 class="no-margin">৳ <del>{{$total_payable + 2000}}</del> {{$total_payable}}</h3>
                    @elseif($assign_batch_student_info->payment_system_id == 3)
                        <h3 class="no-margin">৳ {{$total_payable + 1000}}</h3>
                    @else 
                        <h3 class="no-margin">৳ {{$total_payable}}</h3>
                    @endif
                    <div class="text-muted text-size-large">Total Payable</div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a>
                </div>
            </div>
            <div class="panel panel bg-teal-400 my-payment-wallet">
                <div class="panel-body text-right">
                    @if ($assign_batch_student_info->payment_system_id == 3)
                        <h3 class="no-margin">৳ {{$total_paid + 1000}}</h3>
                    @else 
                        <h3 class="no-margin">৳ {{$total_paid}}</h3>
                    @endif
                    <div class="text-muted text-size-large">Total Paid</div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a>
                </div>
            </div>
            <div class="panel panel bg-pink-400 my-payment-wallet">
                <div class="panel-body text-right">
                    <h3 class="no-margin">৳ {{$total_due}}</h3>
                    <div class="text-muted text-size-large">Total Due</div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /content area -->
@endsection