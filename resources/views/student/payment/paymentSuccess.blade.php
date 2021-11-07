<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'LFWF Academy') }}</title>
    <!-- Favicon -->
    <link href="{{ asset('web/img/fav.png') }}" rel="shortcut icon" type="image/x-icon"/>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ asset('backend/assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('backend/assets/css/minified/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('backend/assets/css/minified/core.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('backend/assets/css/minified/components.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('backend/assets/css/minified/colors.min.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/loaders/pace.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/core/libraries/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/core/libraries/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('backend/assets/js/core/app.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/pages/login.js') }}"></script>
    <!-- /theme JS files -->
    <style>
        .lfwf-footer {
            text-align: center;
            width: 95%;
        }
        .lfwf-footer ul {
            list-style: none;
        }
        .lfwf-footer ul li { 
            text-decoration: none;
            display: inline-block;
        }
        .payment-img {
            height: 30vh;
            width: 100%
        }
        .payment-img img {
            height: 200px;
        }
    </style>
</head>
<body>
    <div id="app">
       	<!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <!-- Main content -->
                <div class="content-wrapper">
                    
                    <!-- Content area -->
                    <div class="content">
                        @if (isset($payment_history_info))
                            <div class="panel panel-body border-top-primary text-center" style="margin-bottom: 0px;">
                                <div class="media">
                                    <div class="media-body">
                                        <h4 class="media-heading text-semibold"> <mark style="background: #7ac043;">{{$msgType}}</mark></h4>
                                        <h6> {{ $messege }} </h6>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <a href="{{route($back_route)}}" class="btn btn-primary btn-block content-group"><i class="icon-circle-left2 position-left"></i> Go Back To Dues Payment</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-body border-top-primary" id="DivIdToPrint" style="background: #fff; border-radius: 10px;">
                                <div class="row">
                                    <div class="col-md-6 content-group">
                                        <img src="{{ asset('web/img/fav.png') }}" class="content-group mt-10 mb-10" alt="" style="width: 70px; height: 70px;">
                                        <ul class="list-condensed list-unstyled">
                                            <li>LFWF Academy</li>
                                            <li>Suihari Dinajpur</li>
                                            <li><a href="tel:+8801889972995"> +8801889972995</a></li>
                                        </ul>
                                    </div>
                            
                                    <div class="col-md-6 content-group">
                                        <div class="invoice-details">
                                            <h5 class="text-uppercase text-semibold">Invoice #0{{$payment_history_info->serial_no}}</h5>
                                            <ul class="list-condensed list-unstyled">
                                                <li>Date: <span class="text-semibold">{{ date("jS F, Y", strtotime($payment_history_info->start_date)) }}</span></li>
                                                <li>Due date: <span class="text-semibold">{{ date("jS F, Y", strtotime($payment_history_info->end_date)) }}</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row invoice-payment">
                                    <div class="col-sm-7">
                                        <div class="content-group">
                                            <h6>Invoice To:</h6>
                                            <div class="mb-15 mt-15">
                                                <img src="assets/images/signature.png" class="display-block" style="width: 150px;" alt="">
                                            </div>
                            
                                            <ul class="list-condensed list-unstyled text-muted">
                                                <li><h6>{{$user_info->name}} [{{$user_info->student_id}}]</h6></li>
                                                <li>Batch: {{$batch_no}}</li>
                                                <li>Course: {{$course_name}}</li>
                                                <li>{{$user_info->address}}</li>
                                                <li>{{$user_info->phone}}</li>
                                                <li>{{$user_info->email}}</li>
                                            </ul>
                                        </div>
                                    </div>
                            
                                    <div class="col-sm-5">
                                        <div class="content-group">
                                            <h6>@if ($payment_history_info->is_running == 1) Total Paid by {{@$payment_success->payment_method}} @else Total Due @endif </h6>
                                            <div class="table-responsive no-border">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th>Subtotal:</th>
                                                            <td class="text-right">৳ {{$payment_history_info->amount}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Tax:</th>
                                                            <td class="text-right">0</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total:</th>
                                                            <td class="text-right text-primary"><h5 class="text-semibold">৳ {{$payment_history_info->amount}}</h5></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-right">
                                                <button class="btn btn-primary btn-labeled printMe"><b><i class="icon-printer"></i></b> Print</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="panel panel-body border-top-primary text-center" style="margin-bottom: 0px;">
                                <div class="media">
                                    <div class="media-body">
                                        <h4 class="media-heading text-semibold"> <mark style="background: red;">{{$msgType}}</mark></h4>
                                        <h6> {{ $messege }} </h6>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <a href="{{route($back_route)}}" class="btn btn-primary btn-block content-group"><i class="icon-circle-left2 position-left"></i> Go Back To Dues Payment</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- /content area -->

                </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->

        </div>
        <!-- /page container -->

    </div>
    <script type="text/javascript">
        $('.printMe').click(function(){
            // printDiv();
            window.print();
        });
        
        // function printDiv() {
        //     var divToPrint=document.getElementById('DivIdToPrint');
        //     var newWin=window.open('','Print-Window');
        //     newWin.document.open();
        //     newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        //     newWin.document.close();
        //     setTimeout(function(){newWin.close();},10);
        // }
    </script>
</body>
</html>
