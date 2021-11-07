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
    <link type="text/css" rel="stylesheet" href="{{ asset('backend/assets/summernote/summernote.css') }}" />
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
</head>
<body class="navbar-top">
    <div id="app">
        <!-- Main navbar -->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{route('teacher.home')}}"><img src="{{ asset('backend/assets/images/logo_light.png') }}" alt=""></a>

                <ul class="nav navbar-nav pull-right visible-xs-block">
                    <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
                    <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
                </ul>
            </div>

            <div class="navbar-collapse collapse" id="navbar-mobile">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="sidebar-control sidebar-main-toggle hidden-xs">
                            <i class="icon-paragraph-justify3"></i>
                        </a>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown dropdown-user">
                        <a class="dropdown-toggle" data-toggle="dropdown">
                            @if( !empty($userInfo->image) || $userInfo->image != Null)
                                <img src="{{ asset('uploads/teacherProfile/'. $userInfo->image) }}" class="img-circle img-sm" alt="">
                            @else
                                <img src="{{ asset('backend/assets/images/placeholder.jpg') }}" alt="">
                            @endif
                            <span>{{ $userInfo->name }}</span>
                            <i class="caret"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="{{route('teacher.profile')}}"><i class="icon-user-plus"></i> My profile</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ route('teacher.logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"><i class="icon-switch2"></i> Logout</a>
                                <form id="logout-form" action="{{ route('teacher.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /main navbar -->
        
        <!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <!-- Main sidebar -->
                <div class="sidebar sidebar-main sidebar-fixed">
                    <div class="sidebar-content">

                        <!-- User menu -->
                        <div class="sidebar-user">
                            <div class="category-content">
                                <div class="media">
                                    <a href="#" class="media-left">
                                        @if( !empty($userInfo->image) || $userInfo->image != Null)
                                            <img src="{{ asset('uploads/teacherProfile/'. $userInfo->image) }}" class="img-circle img-sm" alt="">
                                        @else
                                            <img src="{{ asset('backend/assets/images/placeholder.jpg') }}" alt="">
                                        @endif
                                    </a>
                                    <div class="media-body">
                                        <span class="media-heading text-semibold"> {{ Auth::guard('teacher')->user()->name }} </span>
                                        <div class="text-size-mini text-muted">
                                            <i class="icon-pin text-size-small"></i> {{ @Auth::guard('teacher')->user()->address }}
                                        </div>
                                    </div>

                                    <div class="media-right media-middle">
                                        <ul class="icons-list">
                                            <li>
                                                <a href="#"><i class="icon-cog3"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /user menu -->


                        <!-- Main navigation -->
                        <div class="sidebar-category sidebar-category-visible">
                            <div class="category-content no-padding">
                                <ul class="navigation navigation-main navigation-accordion">

                                    <!-- Default -->
                                    <li class="navigation-header"><span>Default</span> <i class="icon-menu" title="Main pages"></i></li>
                                    <li class="{{ (request()->is('teacher/dashboard')) ? 'active' : '' }}"><a href="{{route('teacher.dashboard')}}"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                                    <li class="{{ (request()->is('teacher/teacherZoomAcc*')) ? 'active' : '' }}"><a href="{{route('teacher.teacherZoomAcc')}}"><i class="icon-task"></i> <span>Zoom Account</span></a></li>
                                    <li class="{{ (request()->is('teacher/widget*')) ? 'active' : '' }}"><a href="{{route('teacher.widget.index')}}"><i class="icon-paragraph-justify2"></i> <span>Student Widget</span></a></li>
                                    <li class="{{ (request()->is('teacher/stdRequestClass*')) ? 'active' : '' }}"><a href="{{route('teacher.stdRequestClass')}}"><i class="icon-envelop4"></i> <span>Class Request</span></a></li>
                                    <li class="{{ (request()->is('teacher/studentReport*')) ? 'active' : '' }}"><a href="{{route('teacher.studentReportForm')}}"><i class="icon-profile"></i> <span>Student Report</span></a></li>
                                    <!-- /Default -->

                                    <!-- Batch and course details -->
                                    <li class="navigation-header"><span>Batch and Class details</span> <i class="icon-menu" title="Forms"></i></li>
                                    <li class="{{ (request()->is('teacher/batchstu*')) ? 'active' : '' }}"><a href="{{route('teacher.batchstuAttendence')}}"><i class="icon-checkbox-checked"></i> <span>Attendance & Assignment</span></a></li>
                                    <li class="{{ (request()->is('teacher/classExam*')) ? 'active' : '' }}"><a href="{{route('teacher.classExamBatch')}}"><i class="icon-stack-text"></i> <span>Quiz Activity</span></a></li>
                                    <li class="{{ (request()->is('teacher/assignedBatch*')) ? 'active' : '' }}"><a href="{{route('teacher.assignedBatch')}}"><i class="icon-book3"></i> <span>Running Batch & Class Status</span></a></li>
                                    <li class="{{ (request()->is('teacher/improveAssignment*')) ? 'active' : '' }}"><a href="{{route('teacher.improveAssignment')}}"><i class="icon-checkbox-checked"></i> <span>Assignment Improvement</span></a></li>

                                    <!-- /Course Setup -->
                                </ul>
                            </div>
                        </div>
                        <!-- /main navigation -->

                    </div>
                </div>
                <!-- /main sidebar -->


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

    
	<!-- Core JS files -->
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/loaders/pace.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/summernote/summernote.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/js/core/libraries/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/js/bootbox.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/js/bootbox.locales.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <!-- Fixed Sidebar JS files -->
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/ui/nicescroll.min.js') }}"></script>
    
    <!-- Sweet Alert JS files -->
    {{-- <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script> --}}
    <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>

    
    <!-- Form JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/forms/validation/validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/forms/inputs/touchspin.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/core/libraries/jquery_ui/interactions.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/forms/selects/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/forms/styling/switch.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <!-- Form JS files -->

    <!-- Uploader JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/uploaders/fileinput.min.js') }}"></script>

    
    <!-- UserProfile JS files -->
    {{-- <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/ui/moment/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/ui/fullcalendar/fullcalendar.min.js') }}"></script> --}}
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/visualization/echarts/echarts.js') }}"></script>
	
    <script type="text/javascript" src="{{ asset('backend/assets/js/core/app.js') }}"></script>
    <!-- Fixed Sidebar JS files -->
	<script type="text/javascript" src="{{ asset('backend/assets/js/layout_fixed_custom.js') }}"></script>
    
    <!-- Datatable JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/pages/datatables_advanced.js') }}"></script>
    <!-- Form Validation JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/pages/form_validation.js') }}"></script>
    <!-- Select2 JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/pages/form_select2.js') }}"></script>

    <!-- UserProfile JS files -->
    {{-- <script type="text/javascript" src="{{ asset('backend/assets/js/pages/user_pages_profile.js') }}"></script> --}}

    <!-- Uploader JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/pages/uploader_bootstrap.js') }}"></script>
    
  
    <!-- /theme JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/custom_frame.js') }}"></script>

    <!-- Per Page JS files -->
    @stack('javascript')
    <!-- /Per Page JS files -->


</body>
</html>
