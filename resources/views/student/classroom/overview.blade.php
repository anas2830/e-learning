@extends('layouts.default')

<style>
    .backColor{
        background-color: #eee !important;
        cursor: pointer;
    }
    .buttons {
        min-width: 310px;
        text-align: center;
        margin-bottom: 1.5rem;
        font-size: 0;
    }

    .buttons button {
        cursor: pointer;
        border: 1px solid silver;
        border-right-width: 0;
        background-color: #f8f8f8;
        font-size: 13px;
        padding: 5px;
        outline: none;
        transition-duration: 0.3s;
    }

    .buttons button:first-child {
        border-top-left-radius: 0.3em;
        border-bottom-left-radius: 0.3em;
    }

    .buttons button:last-child {
        border-top-right-radius: 0.3em;
        border-bottom-right-radius: 0.3em;
        border-right-width: 1px;
    }

    .buttons button:hover {
        color: white;
        background-color: rgb(158, 159, 163);
        outline: none;
    }

    .buttons button.active {
        background-color: #0051B4;
        color: white;
    }
</style>
@push('styles')
<link href="{{ asset('web_graph/bar_chart/css/barStyle.css') }}" rel="stylesheet" type="text/css"/>
@endpush
@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('overview')}}">Course Overview</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">

    <div class="row overall-progress">
        <div class="col-md-12">
            <div class="panel panel-body border-top-info text-center">
                <h6 class="no-margin text-semibold mb-10">Your Average success score based on your Practice time, Attendance, Classmark, Assignment & Quiz</h6>
    
                <div class="progress content-group-sm">
                    <div class="progress-bar @if($std_course_progress < 40) progress-bar-warning @else progress-bar-success @endif progress-bar-striped active" style="width: {{$std_course_progress}}%">
                        <span>{{$std_course_progress}}% Success</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-white overview-graph">
        <div class="panel-heading">
            <h6 class="panel-title">Class Wise Performance</h6>
            <div class="heading-elements buttons" id="filter_button">
                <button id="all" title="Class Wise">
                    ALL Class
                </button>
                <button id="30" title="Last 30 Class">
                    Last 30
                </button>
                <button id="20" class="active" title="Last 20 Class">
                    Last 20
                </button>
                <button id="10" title="Last 10 Class">
                    Last 10
                </button>
            </div>
        </div>
        <div class="panel-body">
            <figure class="highcharts-figure">
                <div id="container"></div>
                <p class="highcharts-description">
                This is your class wise five activities Performances, This Performance are daily updatable by your done activities. So please do your all given jobs carefully.
                </p>
            </figure>
        </div>
    </div>

    {{-- absent/present class list --}}
    <div class="panel panel-white clikPanel">
        <div class="panel-heading backColor">
            <h5 class="panel-title">Absent/Present Class List</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <div class="content-group">
                <ul class="list-group" style="height: 300px; overflow: auto;">
                    @if(count($all_attendence) > 0)
                        @foreach ($all_attendence as $attendence)
                            @if($attendence->is_attend == 1)
                                <li class="list-group-item mb-5 list-group-item-success">{{@Helper::className($attendence->course_class_id)}} 
                                    <span class="badge badge-success">Present</span>
                                </li>
                            @else 
                                <li class="list-group-item mb-5 list-group-item-danger">{{@Helper::className($attendence->course_class_id)}} 
                                    <span class="badge badge-danger">Absent</span>
                                </li>
                            @endif
                        @endforeach
                    @else  
                    <li class="list-group-item mb-5 list-group-item-info">Class Not Taken Yet!!!</li>
                    @endif
                    
                </ul>
            </div>
        </div>
    </div>
    {{-- end absent/present class list --}}

    {{-- assignment list --}}
    <div class="panel panel-white panel-collapsed clikPanel">
        <div class="panel-heading backColor">
            <h5 class="panel-title">Assignment List</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse" class="rotate-180"></a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <div class="content-group">
                <ul class="list-group" style="height: 300px; overflow: auto;">
                    @if(count($total_assignment)> 0)
                    @foreach ($total_assignment as $assignment)
                        @if(empty($assignment->submit))
                            <li class="list-group-item mb-5 list-group-item-danger">{{$assignment->title}} 
                                <span class="badge badge-danger">InComplete</span>
                            </li>
                        @endif
                        @if(!empty($assignment->submit) && $assignment->submit->mark == 0)
                            <li class="list-group-item mb-5 list-group-item-warning">{{$assignment->title}} 
                                <span class="badge badge-warning">Pending</span>
                            </li>
                        @endif
                        @if(!empty($assignment->submit) && $assignment->submit->mark != 0)
                            <li class="list-group-item mb-5 list-group-item-success">{{$assignment->title}} 
                                <span class="badge badge-success">{{$assignment->submit->mark}}</span>
                            </li>
                        @endif
                    @endforeach
                    @else 
                        <li class="list-group-item mb-5 list-group-item-info">Assignment Not Found</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    {{-- end assignment list --}}

    {{-- quiz list --}}
    <div class="panel panel-white panel-collapsed clikPanel">
        <div class="panel-heading backColor">
            <h5 class="panel-title">Quiz List</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse" class="rotate-180"></a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <div class="content-group">
                <ul class="list-group" style="height: 300px; overflow: auto;">
                    @if(count($total_exams)> 0)
                    @foreach ($total_exams as $exam)
                        @if(empty($exam->submit))
                            <li class="list-group-item mb-5 list-group-item-danger">{{Helper::className($exam->class_id)}} Quiz
                                <span class="badge badge-danger">Not Done</span>
                            </li>
                        @else 
                            <li class="list-group-item mb-5 list-group-item-success">{{Helper::className($exam->class_id)}} Quiz
                                <span class="badge badge-success">Done</span>
                            </li>
                        @endif
                    @endforeach
                    @else 
                        <li class="list-group-item mb-5 list-group-item-info">Quiz Not Found</li>
                    @endif
                    {{-- <li class="list-group-header">Success context</li>
                    <li class="list-group-item list-group-item-success">Sheared coasted so concurrent</li>

                    <li class="list-group-header">Info context</li>
                    <li class="list-group-item list-group-item-info">Relentless ouch essentially</li>

                    <li class="list-group-header">Warning context</li>
                    <li class="list-group-item list-group-item-warning">Negatively far essential much</li>

                    <li class="list-group-header">Danger context</li>
                    <li class="list-group-item list-group-item-danger">Into darn intrepid belated</li> --}}
                </ul>
            </div>
        </div>
    </div>
    {{-- end quiz list --}}

    <!-- Start Practice Time Ratio -->
    <div class="panel panel-white panel-collapsed clikPanel">
        <div class="panel-heading backColor">
            <h5 class="panel-title">Practice Time Ratio</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse" class="rotate-180"></a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-body border-top-primary">
                        <div class="text-center">
                            <h6 class="no-margin text-semibold mb-5">Last Day Ratio</h6>
                        </div>

                        <div class="progress">
                            <div class="progress-bar @if($last_one_practice > 50) bg-teal @else progress-bar-warning @endif" style="width: {{$last_one_practice}}%">
                                {{-- <span>@if($last_one_practice > 100) 100 @else  {{$last_one_practice}} @endif % Complete</span> --}}
                                <span> {{$last_one_practice}} % Complete </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-body border-top-primary">
                        <div class="text-center">
                            <h6 class="no-margin text-semibold mb-5">Last Seven Days Ratio</h6>
                        </div>

                        <div class="progress">
                            <div class="progress-bar @if($last_seven_practice > 50) bg-teal @else progress-bar-warning @endif" style="width: {{$last_seven_practice}}%">
                                <span>{{$last_seven_practice}}% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-body border-top-primary">
                        <div class="text-center">
                            <h6 class="no-margin text-semibold mb-5">Last Thirty Days Ratio</h6>
                        </div>

                        <div class="progress">
                            <div class="progress-bar @if($last_thirty_practice > 50) bg-teal @else progress-bar-warning @endif" style="width: {{$last_thirty_practice}}%">
                                <span>{{$last_thirty_practice}}% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Practice Time Ratio -->


    <!-- Start watch video Time Ratio -->
    <div class="panel panel-white panel-collapsed clikPanel">
        <div class="panel-heading backColor">
            <h5 class="panel-title">Video Watch Time</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse" class="rotate-180"></a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-body border-top-primary">
                        <div class="text-center">
                            <h6 class="no-margin text-semibold mb-5">Current Days Ratio</h6>
                        </div>

                        <div class="progress">
                            <div class="progress-bar @if($today_avg_watch_time > 50) bg-teal @else progress-bar-warning @endif" style="width: {{$today_avg_watch_time}}%">
                                <span>{{$today_avg_watch_time}}% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-body border-top-primary">
                        <div class="text-center">
                            <h6 class="no-margin text-semibold mb-5">Last Seven Days Ratio</h6>
                        </div>

                        <div class="progress">
                            <div class="progress-bar @if($sevenDays_avg_watch_time > 50) bg-teal @else progress-bar-warning @endif" style="width: {{$sevenDays_avg_watch_time}}%">
                                <span>{{$sevenDays_avg_watch_time}}% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-body border-top-primary">
                        <div class="text-center">
                            <h6 class="no-margin text-semibold mb-5">Last Thirty Days Ratio</h6>
                        </div>

                        <div class="progress">
                            <div class="progress-bar @if($thirtyDays_avg_watch_time > 50) bg-teal @else progress-bar-warning @endif" style="width: {{$thirtyDays_avg_watch_time}}%">
                                <span>{{$thirtyDays_avg_watch_time}}% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End watch video Time Ratio -->

    {{-- start notification --}}
    <div class="panel panel-white panel-collapsed clikPanel">
        <div class="panel-heading backColor">
            <h5 class="panel-title">Notification List</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse" class="rotate-180"></a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <div class="content-group">
                <div class="panel-group panel-group-control content-group-lg" id="accordion-control" style="height: 300px; overflow: auto;">
                    @if(!empty($notifications))
                    @foreach ($notifications as $key => $notification)
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h6 class="panel-title">
                                <a @if ($key != 0) class="collapsed" @endif data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group{{$key}}">{{$notification->title}}</a>
                            </h6>
                        </div>
                        <div id="accordion-control-group{{$key}}" class="panel-collapse collapse @if ($key == 0) in @endif">
                            <div class="panel-body">
                                {{-- <p>{!! $notification->overview !!}</p> --}}
                                <ul class="list-group border-left-info border-left-lg">
                                    <li class="list-group-item">
                                        <h6 class="list-group-item-heading"><i class="icon-youtube position-left"></i> {!! $notification->overview !!} <span class="label bg-teal-400 pull-right"></span></h6>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else 
                        <li class="list-group-item mb-5 list-group-item-info">Notification Not Found</li>
                    @endif
                </div>

            </div>
        </div>
    </div>
     
    {{-- end notification --}}

    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">DevsSquad IT Solutions</a>
    </div>
    <!-- /footer -->
</div>
<!-- /content area -->
@endsection

@push('javascript')
<script src="{{ asset('web_graph/bar_chart/js/highcharts.js') }}"></script>
<script src="{{ asset('web_graph/bar_chart/js/exporting.js') }}"></script>
<script src="{{ asset('web_graph/bar_chart/js/export-data.js') }}"></script>
<script src="{{ asset('web_graph/bar_chart/js/accessibility.js') }}"></script>


    <script>
        // START GRAPH
        var arrayFromPHP = <?php echo json_encode($all_assign_classes) ?>;
        var classArray = [];
        var attendArray = [];
        var stdClassMark = [];
        var stdClassVideo = [];
        var stdClassAssignment = [];
        var stdClassQuiz = [];
        $.each(arrayFromPHP, function (i, elem){
            classArray.push(elem.std_class_name);
            attendArray.push(parseInt(elem.std_class_attend) > 100 ? 100 : parseInt(elem.std_class_attend));
            stdClassMark.push(parseInt(elem.std_class_mark) > 100 ? 100 : parseInt(elem.std_class_mark));
            stdClassVideo.push(parseInt(elem.std_class_video) > 100 ? 100 : parseInt(elem.std_class_video));
            stdClassAssignment.push(parseInt(elem.std_class_assignment) > 100 ? 100 : parseInt(elem.std_class_assignment));
            stdClassQuiz.push(parseInt(elem.std_class_exam) > 100 ? 100 : parseInt(elem.std_class_exam));
        });
        var myTotalClass = classArray.length;
        let activeLimit = $('#filter_button').find('button.active').attr('id');
        let haveToTake = parseInt(activeLimit);
        let haveToRemove = myTotalClass-haveToTake;
        haveToRemove = haveToRemove > 0 ? haveToRemove : 0;

        var chart  =  Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Class Wise Overall Average Performance'
            },
            // subtitle: {
            //     text: 'Source: Pro.lfwfacademy.com'
            // },
            xAxis: {
                categories: classArray.slice(haveToRemove, myTotalClass), //slice(haveToRemove, HaveToReach)
                crosshair: true
            },
            yAxis: {
                min: 0,
                max:100,
                title: {
                text: 'Mark in ( % )'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0">{point.y:.1f}%</td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                pointPadding: 0.2,
                borderWidth: 0
                }
            },
            series: [{
                name: 'Attend',
                data: attendArray.slice(haveToRemove, myTotalClass)
            
            }, {
                name: 'ClassMark',
                data: stdClassMark.slice(haveToRemove, myTotalClass)
            
            }, {
                name: 'Video',
                data: stdClassVideo.slice(haveToRemove, myTotalClass)
            
            }, {
                name: 'Assignment',
                data: stdClassAssignment.slice(haveToRemove, myTotalClass)
            
            },{
                name: 'Quiz',
                data: stdClassQuiz.slice(haveToRemove, myTotalClass)
            
            }]
        });

        $('#filter_button').find('button').on('click', function (e) {

            let last_count_class = $(this).attr('id');
            $('#filter_button').find('button').removeClass('active');
            $(this).addClass('active');
            var btn_title = $(this).attr('title');
            let total_class = classArray.length;

            if(last_count_class == 'all'){
                var haveShowClass = parseInt(total_class);
            }else{
                var haveShowClass = parseInt(last_count_class);
            }
            var haveToRemove = total_class-haveShowClass;
            haveToRemove = haveToRemove > 0 ? haveToRemove : 0;

            chart.update({
                title: {
                text: btn_title
                },
                // subtitle: {
                //     text: ''
                // },
                xAxis: {
                    categories: classArray.slice(haveToRemove, total_class),
                    crosshair: true
                },
                series:[
                    {
                        name: 'Attend',
                        data: attendArray.slice(haveToRemove, total_class)
                
                    }, 
                    {
                        name: 'Class Mark',
                        data: stdClassMark.slice(haveToRemove, total_class)
                
                    }, 
                    {
                        name: 'Video',
                        data: stdClassVideo.slice(haveToRemove, total_class)
                    
                    }, 
                    {
                        name: 'Assignment',
                        data: stdClassAssignment.slice(haveToRemove, total_class)
                    
                    },
                    {
                        name: 'Quiz',
                        data: stdClassQuiz.slice(haveToRemove, total_class)
                
                    }
                ]
            }, true, false, {
                duration: 800
            });
        });
        // END GRAPH

        $(document).ready(function(){
            $('.backColor').on('click', function(e){
                e.preventDefault();

                if($(this).find('.icons-list').first().find('a').hasClass("rotate-180")){
                    // alert('rotate');
                    $(this).find('.icons-list').first().find('a').removeClass("rotate-180");
                    $(this).parent('.clikPanel').removeClass('panel-collapsed');
                    $(this).next('.panel-body').show();

                }else{
                    // alert('no rotate');
                    $(this).find('.icons-list').first().find('a').addClass("rotate-180");
                    $(this).parent('.clikPanel').addClass('panel-collapsed');
                    $(this).next('.panel-body').hide();
                }

            });

            $('.icons-list li').on('click', 'a', function(e) {
                e.preventDefault();
                if($(this).hasClass("rotate-180")){
                    // alert('a rotate');
                    $(this).removeClass("rotate-180");
                }else{
                    // alert('a no rotate');
                    $(this).addClass("rotate-180");
                }
            });

            
        });
    </script>
@endpush
