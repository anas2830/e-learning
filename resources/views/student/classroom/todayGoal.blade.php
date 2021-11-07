@extends('layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('todayGoal')}}">Today Goal</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    @if ($hasAnyCourse)
    @if (isset($course_info->goal_title))
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-body border-top-primary text-center lfwfgolepanal">
                    <div class="media">
                        <div class="media-body">
                            <h4 class="media-heading text-semibold"> <mark>Our Goal: </mark>  {{ @$course_info->goal_title }}</h4>
                            <h6> {{ @$course_info->goal_sub_title }} </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-body border-top-danger text-center">
                <h6 class="no-margin text-semibold mb-10">Daily Practice Ratio</h6>
                <div class="pace-demo" style="padding-bottom: 30px;">
                    <div class="theme_bar_xs"><div class="pace_progress" data-progress-text="{{$today_practice}}%" data-progress="{{$today_practice}}" style="width: {{$today_practice}}%;">{{$today_practice}}%</div></div>
                </div>
            </div>
            {{-- <div class="panel panel-white">
                <div class="panel-body text-center">
                    <div class="progress content-group-sm">
                        <div class="@if($today_practice > 50)progress-bar progress-bar-success @else progress-bar progress-bar-danger @endif progress-bar-striped active" style="width: {{$today_practice}}%">
                            <span>{{$today_practice}}%</span>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="col-md-3">
            <div class="panel panel-body border-top-success text-center data-list">
                <h6 class="no-margin text-semibold">Upcomming Class Resource: {{@$upcomming_class->class_name}}</h6>
                <p class="content-group-sm text-muted"></p>
                <button type="button" class="btn bg-teal-400 open-modal" id="spinner-dark" modal-title="Class Resource" modal-type="show" modal-size="medium" modal-class="" selector="viewResource" modal-link="{{route('classResourceModal', ['assign_batch_class_id'=>$upcomming_class->id])}}"><i class="icon-arrow-right16 position-left"></i> View Resource</button>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-body border-top-info text-center">
                <h6 class="no-margin text-semibold">Upcomming Class: {{$upcomming_class->class_name}}</h6>
                <p class="content-group-sm text-muted"></p>
                @if (!empty($upcomming_class))
                    <a href="{{route('class', ['class_id'=>$upcomming_class->id] )}}">
                        <button type="button" class="btn bg-teal-400" id="spinner-dark"><i class="icon-arrow-right16 position-left"></i> Go To Class</button>
                    </a>
                @else
                    <button type="button" class="btn bg-danger-400" id="spinner-dark"><i class="icon-arrow-down16 position-left"></i>No Upcomming Class</button>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-body border-top-primary text-center">
                <h6 class="no-margin text-semibold">Upcomming Assignment: {{$upcomming_class->class_name}}</h6>
                <p class="content-group-sm text-muted"></p>
                @if(count($running_assignments)>0)
                    <a href="{{route('class', ['class_id'=>$upcomming_class->id, '#assignments'] )}}">
                        <button type="button" class="btn bg-teal-400" id="spinner-dark"><i class="icon-arrow-right16 position-left"></i> Go To Assignment</button>
                    </a>
                @else 
                    <button type="button" class="btn bg-danger-400" id="spinner-dark"><i class="icon-arrow-down16 position-left"></i>No Assignment</button>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-body border-top-success text-center">
                <h6 class="no-margin text-semibold">Upcomming Videos: {{$upcomming_class->class_name}}</h6>
                <p class="content-group-sm text-muted"></p>
                @if(count($running_videos)>0)
                <a href="{{route('class', ['class_id'=>$upcomming_class->id] )}}">
                    <button type="button" class="btn bg-teal-400" id="spinner-dark"><i class="icon-arrow-right16 position-left"></i> Go To Videos</button>
                </a>
                @else 
                    <button type="button" class="btn bg-danger-400" id="spinner-dark"><i class="icon-arrow-down16 position-left"></i>No Videos</button>
                @endif
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-body border-top-info text-center">
                <h6 class="no-margin text-semibold">Upcomming Quiz: {{$upcomming_class->class_name}}</h6>
                <p class="content-group-sm text-muted"></p>
                @if(!empty($running_quiz))
                    <a href="{{route('class', ['class_id'=>$upcomming_class->id, '#quiz'] )}}">
                        <button type="button" class="btn bg-teal-400" id="spinner-dark"><i class="icon-arrow-right16 position-left"></i> Go To Quiz</button>
                    </a>
                @else 
                    <button type="button" class="btn bg-danger-400" id="spinner-dark"><i class="icon-arrow-down16 position-left"></i>No Quiz</button>
                @endif
            </div>
        </div>
    </div>

    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">Weekly Schedule</h6>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            @if (!empty($total_days))
                @foreach($total_days as $key => $day)
                    <div class="form-group row">
                        <label class="control-label checkbox-inline col-md-3"> {{$day->day_name}} 
                            <input type="checkbox" name="days[]" value="{{$day->dt}}" class="pt5" @if(isset($day->schedule)) checked disabled="disabled" @endif>
                        </label>
                        <div class="col-md-6">
                            @if (@$day->schedule->start_time)
                                <span>{{Helper::timeGia($day->schedule->start_time)}}</span>
                            @else 
                                <span>.........</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <h6 class="panel-title text-center">Schedule Not Found !!!</h6>
            @endif
        </div>
    </div>
    @else
    <div class="row">
        <div class="panel panel-body">
            <div class="media">

                <div class="media-body">
                    <h4 class="media-heading text-semibold">You have not any active course</h4>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">DevsSquad IT Solutions</a>
    </div>
    <!-- /footer -->
</div>
<!-- /content area -->
@endsection

@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
        });
    </script>
@endpush