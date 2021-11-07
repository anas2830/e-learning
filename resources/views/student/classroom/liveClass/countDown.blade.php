@extends('layouts.default')
<link href="{{asset('web/css/liveClass.css')}}" rel='stylesheet'>
<style type="text/css">
    .join_a_class {
        border: 1px solid #ccc;
        width: 100%; 
        height: 700px;
    }  
</style>

@section('content')

<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('stdLiveClass')}}">Support</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    @if ($liveClassDetails == null)
        <div class="panel panel-white">
            <div class="panel-body">
                <h6 class="panel-title text-center">Live Class is Not Configured For Your Running Class !!!</h6>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12 live-class-div">
                <?php
                    $curDate = date("Y-m-d");
                    $curTime = date("H:i:s");
                    $classStart = $liveClassDetails->start_date;
                    $classEnd_time =  date("H:i:s", strtotime($liveClassDetails->end_time));
                    $classTime =  date("H:i:s", strtotime($liveClassDetails->start_time)); 
                ?>
                    @if (strtotime($classStart) == strtotime($curDate) && strtotime($curTime) >= strtotime($classTime) && strtotime($curTime) <= strtotime($classEnd_time)) 
                        <iframe class="join_a_class" src="{{$liveClassDetails->join_url}}">
                            <style type="text/css">
                                .join-frame #header_container {
                                    display: none!important;
                                }
                            </style>
                        </iframe>
                    @else
                    <div class="post-box">
                        <div class="nothing-here classTimer">
                            <div class="flex-col-c-sb size1 overlay1">
                                <div class="flex-col-c-m p-l-15 p-r-15 p-t-50 p-b-120">
                                    <h3 class="l1-txt1 txt-center p-b-40 respon1">
                                        Coming Soon
                                    </h3>

                                    <div class="flex-w flex-c-m cd100">
                                        <div class="flex-col-c wsize1 m-b-30">
                                            <span class="l1-txt2 p-b-9 days"></span>
                                            <span class="s1-txt1 where1 p-l-35">Days</span>
                                        </div>

                                        <div class="flex-col-c wsize1 m-b-30">
                                            <span class="l1-txt2 p-b-9 hours"></span>
                                            <span class="s1-txt1 where1 p-l-35">Hours</span>
                                        </div>

                                        <div class="flex-col-c wsize1 m-b-30">
                                            <span class="l1-txt2 p-b-9 minutes"></span>
                                            <span class="s1-txt1 where1 p-l-35">Minutes</span>
                                        </div>

                                        <div class="flex-col-c wsize1 m-b-30">
                                            <span class="l1-txt2 p-b-9 seconds"></span>
                                            <span class="s1-txt1 where1 p-l-35">Seconds</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <?php 
                    $date = explode('-', $classStart);
                    $time = explode(':', $classTime);
                    $currentDate = explode('-', $curDate);
                ?>
                <input type="hidden" class="year" value="{{$date[0]}}">
                <input type="hidden" class="month" value="{{$date[1]}}">
                <input type="hidden" class="day" value="{{$date[2]}}">
                <input type="hidden" class="hour" value="{{$time[0]}}">
                <input type="hidden" class="min" value="{{$time[1]}}">
                <input type="hidden" class="sec" value="{{$time[2]}}">
                <input type="hidden" class="timezone" value="{{$liveClassDetails->timezone}}">
                <input type="hidden" class="timezone" value="{{$classStart}}">
                <input type="hidden" class="curTime" value="{{$curTime}}">
                <input type="hidden" class="classTime" value="{{$classTime}}">
            </div>
        </div>
    @endif
</div>
@endsection

@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            @if (session('msgType'))
                setTimeout(function() {$('#msgDiv').hide()}, 6000);
            @endif
        });
    </script>
    <script src="{{ asset('web/js/countdowntime/countdowntime.js') }}"></script>
    <script>
        //Countdown-Time -------------------
        var year = $(".year").val();
        var month = $(".month").val();
        var date = $(".day").val();
        var hour = $(".hour").val();
        var min = $(".min").val();
        var sec = $(".sec").val();
        var timezone = $(".timezone").val();
        $('.cd100').countdown100({
            endtimeYear: year,
            endtimeMonth: month,
            endtimeDate: date,
            endtimeHours: hour,
            endtimeMinutes: min,
            endtimeSeconds: sec,
            timeZone: ""
        });
    </script>
@endpush