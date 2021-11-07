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
    <!-- /filePond stylesheets --> 
    <link href="{{ asset('web/filepond/filepond.css') }}" rel="stylesheet" type="text/css"/>
    
    <!-- Rang Slider stylesheets --> 
    <link href="{{ asset('web/rangSlider/rangSlider.css') }}" rel="stylesheet" type="text/css"/>

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
        
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5HBZ5F6RP7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        
        gtag('config', 'G-5HBZ5F6RP7');
    </script>
</head>
<body class="navbar-top-md-xs sidebar-xs has-detached-left">
    <div id="app">

        @if($is_payment_alert)
            <div id="payment_alert" style="display:none; margin: 0px 10px;">
                {{-- <div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" id="alert_close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Warning!</strong> Please pay your current installment immediately.
                </div> --}}
                <div class="alert alert-warning alert-styled-left">
                    <button type="button" class="close cursor-pointer" data-dismiss="alert" id="alert_close"><span>×</span><span class="sr-only">Close</span></button>
                    <span class="text-semibold">Payment Reminder!</span> You've {{$remainingDays}} day left to freeze your account, Please pay your installment payment. 
                </div>
            </div>
        @endif

        <!-- Main navbar -->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{route('home')}}"><img src="{{ asset('backend/assets/images/logo_light.png') }}" alt=""></a>

                <ul class="nav navbar-nav pull-right visible-xs-block">
                    <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
                </ul>
            </div>

            <div class="navbar-collapse collapse" id="navbar-mobile">
                <ul class="nav navbar-nav">
                    {{-- <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li> --}}
                    @if (request()->is('class'))
                    <li><a class="sidebar-control sidebar-detached-hide hidden-xs"><i class="icon-drag-left"></i></a></li>
                    @endif
                </ul>
                <ul class="nav navbar-nav">
                    <li class="{{ (request()->is('home')) ? 'active' : '' }}"><a href="{{route('home')}}"><i class="icon-home4 position-left"></i> <span>Dashboard</span></a></li>
                    <li class="{{ (request()->is('overview')) ? 'active' : '' }}"><a href="{{route('overview')}}">Overview</a></li>
                    <li class="{{ (request()->is('studentRanking')) ? 'active' : '' }}"><a href="{{route('studentRanking')}}">My Batch</a></li>
                    <li class="{{ (request()->is('todayGoal')) ? 'active' : '' }}"><a href="{{route('todayGoal')}}">Today Goal</a></li> 
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Class List<span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="{{ (request()->is('class')) ? 'active' : '' }}"><a @if($running_class_id == 0) href="{{route('class')}}" @else href="{{route('class', ['class_id'=>$running_class_id] )}}" @endif>Class</a></li>
                            <li class="{{ (request()->is('stdLiveClass')) ? 'active' : '' }}"><a href="{{route('stdLiveClass')}}" class="joinAClass" id="joinLive">Live Class</a></li>
                            <li class="{{ (request()->is('requestClass')) ? 'active' : '' }}"><a href="{{route('requestClass.index')}}">Request Class</a></li>
                        </ul>
                    </li>
                    <li class="{{ (request()->is('takeSupport*')) ? 'active' : '' }}"><a href="{{route('takeSupport.index')}}">Support</a></li>
                    {{-- <li class="{{ (request()->is('stdLiveClass*')) ? 'active' : '' }}"><a href="{{route('stdLiveClass')}}" class="joinAClass" id="joinLive">Live Class</a></li>
                    <li class="{{ (request()->is('requestClass*')) ? 'active' : '' }}"><a href="{{route('requestClass.index')}}">Request Class</a></li> --}}
                    <li class="{{ (request()->is('improveScore')) ? 'active' : '' }}"><a href="{{route('improveScore')}}">Improve Score</a></li>
                    <li class="{{ (request()->is('myDuePayments')) ? 'active' : '' }}"><a href="{{route('myDuePayments')}}">Payments</a></li>

                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-bubbles4"></i>
                            <span class="visible-xs-inline-block position-right">Messages</span>
                            @php
                                $unseenMsgCount = 0;
                                $seenHistory = 0;
                                $total_notify = count($student_notify);
                            @endphp
                            <span id="unseenMsg" class="badge bg-warning-400">{{$unseenMsgCount}}</span>
                        </a>
                        
                        <div class="dropdown-menu dropdown-content width-350">
                            <div class="dropdown-content-heading">
                                Messages
                            </div>
    
                            <ul class="media-list dropdown-content-body">
                                @foreach ($student_notify as $notify)
                                    @if (empty($notify->seen))
                                        @php $unseenMsgCount++; @endphp
                                        <li class="media">
                                            <div class="media-left">
												<a href="{{url($notify->notify_link)}}" class="btn border-teal-400 text-teal btn-flat btn-rounded btn-icon btn-xs notifyLink" data-id={{$notify->id}}><i class="icon-redo2"></i></a>
											</div>
            
                                            <div class="media-body"> 
                                                <a href="{{url($notify->notify_link)}}" class="media-heading notifyLink" data-id={{$notify->id}}>
                                                    <span class="text-semibold">{{ Helper::getAuthorName($notify->created_type, $notify->created_by) }}</span>
                                                    <span class="media-annotation pull-right">{{$notify->notify_date}} {{Helper::timeGia($notify->notify_time) }}</span>
                                                </a>
                                                <span class="text-muted">{!! strip_tags($notify->notify_title) !!}</span>
                                            </div>
                                        </li>
                                    @else
                                        @php $seenHistory++; @endphp
                                        
                                        @if($seenHistory <= 2)
                                            <li class="media">
                                                <p id="emptyNotifiy"></p>
                                                <div class="media-left">
                                                    <a href="{{url($notify->notify_link)}}" class="btn border-teal-400 text-teal btn-flat btn-rounded btn-icon btn-xs notifyLink" data-id={{$notify->id}}><i class="icon-redo2"></i></a>
                                                </div>
                                                <div class="media-body"> 
                                                    <a href="#" class="media-heading">
                                                        <span>{{ Helper::getAuthorName($notify->created_type, $notify->created_by) }}</span>
                                                        <span class="media-annotation pull-right">{{$notify->notify_date}} {{Helper::timeGia($notify->notify_time) }}</span>
                                                    </a>
                                                    <span class="text-muted">{!! strip_tags($notify->notify_title) !!}</span>
                                                </div>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
    
                            <div class="dropdown-content-footer">
                                <a href="#" data-popup="tooltip"><i class="icon-menu display-block"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown dropdown-user">
                        <a class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('backend/assets/images/image.png') }}" alt="">
                            <span>{{ $userInfo->name }}</span>
                            <i class="caret"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="{{route('availableAssignment')}}"><i class="icon-portfolio"></i> Check Assignments</a></li>
                            <li><a href="{{route('profile')}}"><i class="icon-user-plus"></i> My profile</a></li>
                            <li class="divider"></li>
                            <li><a href="{{route('myDuePayments')}}"><i class="icon-cash3"></i> My Payments</a></li>
                            <li class="divider"></li>
                            <li><a href="{{route('mySuccessStory.index')}}"><i class="icon-trophy3"></i> My Success</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"><i class="icon-switch2"></i> Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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

                <!-- Main content -->
                <div class="content-wrapper">
                    @if(!empty(@$student_course_info))
                        <div id="timerItem" title="Move Anywhere Just Drag &amp; Drop" style="display: none;">
                            <!-- Innter item content -->
                            <div id="timer" class="lfwf-timer">
                                <div class="clock-wrapper lfwf-clock-wrap">
                                    <span class="hours"></span>
                                    <span class="dots">:</span>
                                    <span class="minutes"></span>
                                    <span class="dots">:</span>
                                    <span class="seconds"></span>
                                    <div class="buttons-wrapper lfwf-button-wrap">

                                        <div class="stage filter-contrast" id="filterContrast"><div class="dot-shuttle"></div></div>
                                        <div class="start-practice" id="startPractice"> </div>
                                        <button class="btn lfwf-toggle-btn" id="start-cronometer" style="color: black">Start</button>
                                        <button class="btn lfwf-toggle-btn" id="resume-timer" data-id={{$userInfo->id}}>Start</button>
                                        <button class="btn lfwf-toggle-btn" id="again_start" data-id={{$userInfo->id}}>start</button>
                                        <button class="btn lfwf-toggle-btn" id="stop-timer" data-id={{$userInfo->id}}>Stop</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

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
    <script type="text/javascript" src="{{ asset('backend/assets/js/popper.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/core/libraries/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/js/bootbox.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/js/bootbox.locales.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/summernote/summernote.min.js') }}"></script>
    
    <!-- Stricky Class menu scrollbar -->
    @if (request()->is('class'))
    <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/ui/nicescroll.min.js') }}"></script>
    @endif
    <!-- Horizontal Navbar JS files -->
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/ui/drilldown.js') }}"></script>
    
    
    <!-- Sweet Alert JS files -->
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
    
    <!-- Dashboard JS files -->
    {{-- <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/visualization/d3/d3.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/visualization/d3/d3_tooltip.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/ui/moment/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('backend/assets/js/plugins/pickers/daterangepicker.js') }}"></script> --}}
    <!-- Dashboard JS files -->

    <!-- Uploader JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/uploaders/fileinput.min.js') }}"></script>

    @if (!request()->is('changeProfile'))
    <!-- Chart JS files -->
	{{-- <script type="text/javascript" src="{{ asset('backend/assets/js/plugins/visualization/echarts/echarts.js') }}"></script> --}}
    @endif
	
    <script type="text/javascript" src="{{ asset('backend/assets/js/core/app.js') }}"></script>
	{{-- <script type="text/javascript" src="{{ asset('backend/assets/js/pages/dashboard.js') }}"></script> --}}
    
    <!-- Datatable JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/pages/datatables_advanced.js') }}"></script>

    <!-- Form Validation JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/pages/form_validation.js') }}"></script>

    <!-- Select2 JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/pages/form_select2.js') }}"></script>

    <!-- Uploader JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/pages/uploader_bootstrap.js') }}"></script>
    
    <!-- Stricky Class menu scrollbar -->
    @if (request()->is('class'))
	<script type="text/javascript" src="{{ asset('backend/assets/js/sticky/sidebar_detached_sticky_custom.js') }}"></script>
    @endif
    <!-- /Filepond JS files -->
    <script type="text/javascript" src="{{ asset('web/filepond/filepond.js') }}"></script>
    
    <!-- /Filepond JS files -->
    <script src="{{ asset('web/rangSlider/prefixfree.min.js') }}"></script>
    <script src="{{ asset('web/rangSlider/rangeslider.min.js') }}"></script>
    <script src="{{ asset('web/rangSlider/underscore-min.js') }}"></script>

    <!-- /Custom JS files -->
    <script type="text/javascript" src="{{ asset('backend/assets/js/custom_frame.js') }}"></script>    

    <!-- Per Page JS files -->
    @stack('javascript')
    <!-- /Per Page JS files -->

    <script type="text/javascript">
    // jillur drag //
        var dragItem = document.querySelector("#timerItem");
        var container = document.querySelector("#app");

        var active = false;
        var currentX;
        var currentY;
        var initialX;
        var initialY;
        var xOffset = 0;
        var yOffset = 0;

        container.addEventListener("touchstart", dragStart, false);
        container.addEventListener("touchend", dragEnd, false);
        container.addEventListener("touchmove", drag, false);

        container.addEventListener("mousedown", dragStart, false);
        container.addEventListener("mouseup", dragEnd, false);
        container.addEventListener("mousemove", drag, false);

        function dragStart(e) {
        if (e.type === "touchstart") {
            initialX = e.touches[0].clientX - xOffset;
            initialY = e.touches[0].clientY - yOffset;
        } else {
            initialX = e.clientX - xOffset;
            initialY = e.clientY - yOffset;
        }

        if (e.target === dragItem) {
            active = true;
        }
        }

        function dragEnd(e) {
        initialX = currentX;
        initialY = currentY;

        active = false;
        localStorage.setItem('clockPosition', 'translate3d('+currentX+'px, '+currentY+'px, 0px)');

        }

        function drag(e) {

        if (active) {
            e.preventDefault();
            if (e.type === "touchmove") {
            currentX = e.touches[0].clientX - initialX;
            currentY = e.touches[0].clientY - initialY;
            } else {
            currentX = e.clientX - initialX;
            currentY = e.clientY - initialY;
            }

            xOffset = currentX;
            yOffset = currentY;

            setTranslate(currentX, currentY, dragItem);
        }
        }

        function setTranslate(xPos, yPos, el) {
        el.style.transform = "translate3d(" + xPos + "px, " + yPos + "px, 0)";
        }

    // end jillur drag //

    $(document).ready(function(){
        // localStorage.clear();
        var storageClockPosition = localStorage.getItem('clockPosition');
        if(storageClockPosition == null){
            storageClockPosition = localStorage.setItem('clockPosition', 'translate3d(0px, 0px, 0px)');
        }else{
            storageClockPosition = localStorage.getItem('clockPosition');
        }   
        $(dragItem).css("transform", storageClockPosition);
        setTimeout(function(){ $(dragItem).fadeIn(500); }, 2000);

        // payment alert start
        var today = new Date();
        var running_date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        // payment alert end

        if ("date_{{$student_course_info->course_id}}" in localStorage) {
            var firstTimestorageTime = localStorage.getItem('time_{{$student_course_info->course_id}}');
            var firstTimestorageTimeArray = firstTimestorageTime.split(':');

            $('.lfwf-timer').find(".hours").text(firstTimestorageTimeArray[0]);
            $('.lfwf-timer').find(".minutes").text(firstTimestorageTimeArray[1]);
            $('.lfwf-timer').find(".seconds").text(firstTimestorageTimeArray[2]);

            var seconds = firstTimestorageTimeArray[2];

            var storage_minutes = firstTimestorageTimeArray[1];
            var lastMinValue = storage_minutes.slice(-1);
            if(storage_minutes > 9){
               var minutes = firstTimestorageTimeArray[1];
            }else{
                var minutes = lastMinValue;
            }

            var storage_hours = firstTimestorageTimeArray[0];
            var lastHourValue = storage_hours.slice(-1);
            if(storage_hours > 9){
               var hours = firstTimestorageTimeArray[0];
            }else{
                var hours = lastHourValue;
            }

        } else {
            var firstTimestorageTime = "00:00:00";
            var firstTimestorageTimeArray = firstTimestorageTime.split(':');

            $('.lfwf-timer').find(".hours").text(firstTimestorageTimeArray[0]);
            $('.lfwf-timer').find(".minutes").text(firstTimestorageTimeArray[1]);
            $('.lfwf-timer').find(".seconds").text(firstTimestorageTimeArray[2]);

            var seconds = 0;
            var minutes = 0;
            var hours = 0;
        }

        // Unseen Message count
        var unseenMsgCount = {{$unseenMsgCount}};
        $('#unseenMsg').text(unseenMsgCount);
        var total_notify = {{$total_notify}};
        var seenHistory = {{$seenHistory}};
        if(total_notify <= seenHistory){
            $('#emptyNotifiy').text('You Have No New Notifications !!');
        }

        // notify link ajax
        $('.notifyLink').on('click', function(e){
            e.preventDefault;
            var notify_id = $(this).attr('data-id');
            $.ajax
            ({ 
                url : "{{route('notifySeen')}}",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                data: {"notify_id":notify_id},
                type: 'post',
                success: function(response)
                {   
                    console.log(response);
                }
            });
        });
        // end notify link ajax

        $('#startPractice').fadeOut(100); //New

        const measure = $('select#measure')
        const ammount = $('input#num')
        const timer = $('#timer')
        const s = $(timer).find('.seconds')
        const m = $(timer).find('.minutes')
        const h = $(timer).find('.hours')

        var interval = 0;
        var clockType = 'cronometer';

        if( seconds > 0 ){
            pauseClock();
        } 

        $('button#start-cronometer').on('click', function(e){
            e.preventDefault();
            $(this).hide();
            $('button#stop-timer').fadeIn(100);
            $('#filterContrast').fadeIn(100); //New
            $('#startPractice').fadeOut(100); //New
            clockType = 'cronometer'
            startClock();
            localStorage.setItem('clockStatus_{{$student_course_info->course_id}}', 1);
        });

        $('button#stop-timer').on('click', function(e) {
            e.preventDefault();
            $(this).hide();
            var id = $(this).attr("data-id");
            $.ajax
            ({ 
                url : "{{route('parcticeTimeUpdate')}}",
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                data: {"hours":hours,"minutes":minutes,"seconds":seconds,"type":1}, //type 1 = stop,type 2 = resu
                type: 'post',
                success: function()
                {
                    
                }
            });

            $('#start-cronometer').hide();
            $('#filterContrast').hide(); //New
            $('#resume-timer').fadeIn(100);
            $('#startPractice').fadeIn(100); //New
            pauseClock();
            localStorage.setItem('clockStatus_{{$student_course_info->course_id}}', 0);
        });

        $('button#resume-timer').on('click', function(e) {

            e.preventDefault();
            $(this).hide();
            $('button#stop-timer').fadeIn(100);
            $('#filterContrast').fadeIn(100);

            localStorage.setItem('clockStatus_{{$student_course_info->course_id}}', 1);
            $('button#resume-timer').fadeOut(100);
            $('#filterContrast').fadeIn(); //New
            $('#startPractice').fadeOut(100); //New
            switch (clockType) {
                case 'cronometer':
                    cronometer()
                    break
                default:
                    break;
            }
        });

        function pad(d) {
            return (d < 10) ? '0' + d.toString() : d.toString()
        }

        function startClock() {
            hasStarted = true
            hasEnded = false

            refreshClock()

            $('.input-wrapper').slideUp(350)
            setTimeout(function(){
                $('#timer').fadeIn(350)
                $('#stop-timer').fadeIn(350)

            }, 350)

            switch (clockType) {
                case 'cronometer':
                    cronometer()
                    break
                default:
                    break;
            }
        }

        function pauseClock() {
        clear(interval)
        $('#resume-timer').fadeIn()
        }

        var hasStarted = false
        var hasEnded = false
        if (hours == 0 && minutes == 0 && seconds == 0 && hasStarted == true) {
            hasEnded = true
        }

        function cronometer() {
            hasStarted = true
            interval = setInterval(() => {
                if (seconds < 59) {
                    seconds++
                    refreshClock()
                }
                else if (seconds == 59) {
                    minutes++
                    seconds = 0
                    refreshClock()
                }

                if (minutes == 60) {
                    hours++
                    minutes = 0
                    seconds = 0
                    refreshClock()
                }

            }, 1000)
        }

        function refreshClock() {
            $(s).text(pad(seconds))
            $(m).text(pad(minutes))
            if (hours < 0) {
                $(s).text('00')
                $(m).text('00')
                $(h).text('00')
            } else {
                $(h).text(pad(hours))
            }
        }

        function clear(intervalID) {
            clearInterval(intervalID)
            console.log('cleared the interval called ' + intervalID)
        }
        // end countdown timer

        // again start
        $('button#again_start').on('click', function() {
            againStart();
            localStorage.setItem('clockStatus_{{$student_course_info->course_id}}', 1);

            $('#filterContrast').fadeIn();
            $('#stop-timer').fadeIn();
            $('#again_start').fadeOut();
        });
           
        function againStart() {
            hours = 0;
            minutes =0;
            seconds =0;
            hasStarted = true
            interval = setInterval(() => {
                if (seconds < 59) {
                    seconds++
                    refreshClock()
                }
                else if (seconds == 59) {
                    minutes++
                    seconds = 0
                    refreshClock()
                }

                if (minutes == 60) {
                    hours++
                    minutes = 0
                    seconds = 0
                    refreshClock()
                }

            }, 1000)
        }

        // auto clock on/off when page load
        if(localStorage.getItem('clockStatus_{{$student_course_info->course_id}}') == 1){
            startClock();
            $('#filterContrast').fadeIn();
            $('#resume-timer').fadeOut();
            $('#start-cronometer').fadeOut();
            $('#again_start').fadeOut();
            $('#stop-timer').fadeIn();
        }else{
            $('#filterContrast').fadeOut();
            $('#resume-timer').fadeOut();
            $('#start-cronometer').fadeIn();
            $('#again_start').fadeOut();
            $('#stop-timer').fadeOut();
            $('#startPractice').fadeIn(100); //New
        }

        setInterval(function() {
            
            var running_hours = $('.lfwf-timer').find('.hours').text();
            var running_minutes = $('.lfwf-timer').find('.minutes').text();
            var running_seconds = $('.lfwf-timer').find('.seconds').text();
            var time = running_hours+":"+running_minutes+":"+running_seconds;
            
            var today = new Date();
            var running_date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            var storage_date = localStorage.getItem('date_{{$student_course_info->course_id}}');

            if (storage_date === null) {
                localStorage.setItem('time_{{$student_course_info->course_id}}', time);
                localStorage.setItem('date_{{$student_course_info->course_id}}', running_date);
                localStorage.setItem('clockStatus_{{$student_course_info->course_id}}', 0);
                localStorage.setItem('course_id', {{$student_course_info->course_id}});
            }else{
                if(storage_date != running_date){
                    $.ajax
                    ({ 
                        url : "{{route('parcticeTimeUpdate')}}",
                        headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                        data: {"hours":hours, "minutes":minutes, "seconds":seconds, "storageDate":storage_date, "type":1},  //type 1 = stop,type 2 = resume, 3=auto
                        type: 'post',
                        success: function()
                        {
                            
                        }
                    });

                    localStorage.clear(); 
                    pauseClock();

                    var time = 0+":"+0+":"+0;

                    $('.lfwf-timer').find(".hours").text(0);
                    $('.lfwf-timer').find(".minutes").text(0);
                    $('.lfwf-timer').find(".seconds").text(0);  

                    $('#resume-timer').fadeOut();
                    $('#start-cronometer').fadeOut();
                    $('#filterContrast').fadeOut();
                    $('#stop-timer').fadeOut();

                    $('#again_start').fadeIn();
                    localStorage.setItem('clockStatus_{{$student_course_info->course_id}}', 0);
                    localStorage.setItem('time_{{$student_course_info->course_id}}', time);
                    localStorage.setItem('date_{{$student_course_info->course_id}}', running_date);

                    $('button#resume-timer').hide();
                    $('#resume-timer').fadeOut();

                }else{
                    // timer set time
                    localStorage.setItem('time_{{$student_course_info->course_id}}', time);
                    localStorage.setItem('date_{{$student_course_info->course_id}}', running_date);
                }
            }
        }, 1000);

        //alert payment start
        $('#alert_close').on('click', function(e){
            e.preventDefault;
            localStorage.setItem('payment_alert_{{$auth_id}}_{{$student_course_info->course_id}}', running_date);
        });

        if(localStorage.getItem('payment_alert_{{$auth_id}}_{{$student_course_info->course_id}}') == running_date){
            $('#payment_alert').hide();
        }else{
            $('#payment_alert').show();
        }
        //alert payment end
    });
    </script>

</body>
</html>
