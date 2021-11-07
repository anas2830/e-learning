<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">
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
    <link href="{{ asset('backend/assets/css/practiceTime.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/assets/summernote/summernote.css') }}" rel="stylesheet" type="text/css"/>
    <!-- /global stylesheets --> 
    <style>
        .add-new {
            color: #fff!important;
        }
        .add-new:hover {
            opacity: 1 !important;
        }
        .panel>.dataTables_wrapper .table-bordered {
            border: 1px solid #ddd;
        }
        .dataTables_length {
            margin: 20px 0 20px 20px;
        }
        .dataTables_filter {
            margin: 20px 0 20px 20px;
        }
        .dataTables_info {
            margin-bottom: 20px;
        }
        .dataTables_paginate {
            margin: 20px 0 20px 20px;
        }
        .action-icon {
            padding: 0px 10px 0 0;
        }

        .kv-fileinput-upload {
            display: none;
        }
    </style>
    @stack('styles')
</head>
<body class="navbar-top-md-xs sidebar-xs has-detached-left" >
    <div id="app">
        <!-- Main navbar -->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{route('courses')}}"><img src="{{ asset('backend/assets/images/logo_light.png') }}" alt=""></a>
                <ul class="nav navbar-nav pull-right visible-xs-block">
                    <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
                </ul>
            </div>
        </div>
        <!-- /main navbar -->
        
        <!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <!-- Main content -->
                <div class="content-wrapper">
                    
                    @yield('content')

                </div>
                <!-- /main content -->
                
            </div>
            <!-- /page content -->
        </div>
        <!-- /page container -->
    </div>

    <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function() {
        FB.init({
            xfbml            : true,
            version          : 'v9.0'
        });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <!-- Your Chat Plugin code -->
    <div class="fb-customerchat"
        attribution=setup_tool
        page_id="112941143881682"
        logged_in_greeting="Hi! How can we help you? if you have any issue or need help you can message..."
        logged_out_greeting="Hi! How can we help you? if you have any issue or need help you can message...">
    </div>
    
	<!-- Core JS files -->
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/loaders/pace.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/core/libraries/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/core/libraries/bootstrap.min.js') }}"></script>
 
    <!-- Uploader JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/uploaders/fileinput.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('backend/assets/js/core/app.js') }}"></script>
	{{-- <script type="text/javascript" src="{{ asset('backend/assets/js/pages/dashboard.js') }}"></script> --}}

    <!-- Uploader JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/pages/uploader_bootstrap.js') }}"></script>
    
    <!-- /Custom JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/custom_frame.js') }}"></script>    

    <!-- Per Page JS files -->
    @stack('javascript')
    <!-- /Per Page JS files -->

    <script type="text/javascript">

    </script>

</body>
</html>
