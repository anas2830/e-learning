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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-body border-top-primary text-center">
                                    <div class="payment-img">
                                        <img src="{{ asset('web/img/circle-cross.png') }}" alt="" class="img-fluid">
                                    </div>
                                    <div class="media">
                                        <div class="media-body">
                                            <h4 class="media-heading text-semibold"> <mark style="background: #f70000;">{{$msgType}}</mark></h4>
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
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="footer text-muted lfwf-footer">
                            &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">DevsSquad IT Solutions</a>
                            <ul>
                                <li><a href="https://www.lfwfacademy.com/about-us" target="_blank">About Us |</a></li>
                                <li><a href="https://www.lfwfacademy.com/terms-of-service" target="_blank">Terms & Conditions |</a></li>
                                <li><a href="https://www.lfwfacademy.com/privacy-policy" target="_blank">Privacy Policy |</a></li>
                                <li><a href="https://www.lfwfacademy.com/refund-policy" target="_blank">Refund and Return Policy</a></li>
                            </ul>
                        </div>
                        <!-- /footer -->
                    </div>
                    <!-- /content area -->

                </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->

        </div>
        <!-- /page container -->

    </div>
</body>
</html>
