@extends('layouts.default')

@push('styles')
    <style>
        @media only screen and (max-width: 768px) {
            .col-md-7 .panel-body {
                padding: 0 !important;
            }
        }
    </style>
@endpush
@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('materials')}}">Course Materials</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    @if (count($course_classes) > 0)
        <div class="row">
            <div class="col-md-5" style="border: 1px solid #2196f3; padding: 4px; overflow: auto; max-height: 500px;">
                <div class="panel-group panel-group-control content-group-lg" id="accordion-control">
                    @foreach ($course_classes as $key => $class)
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h6 class="panel-title">
                                <a @if ($key != 0) class="collapsed" @endif data-toggle="collapse" data-parent="#accordion-control" href="#accordion-control-group{{$key}}">{{$class->class_name}}</a>
                                <span class="label @if($class->complete_status == 1) label-success @elseif($class->complete_status == 2) label-info @else label-warning @endif pull-right">@if ($class->complete_status == 1) Completed @elseif($class->complete_status == 2) Running @else Upcoming @endif</span>
                            </h6>
                        </div>
                        <div id="accordion-control-group{{$key}}" class="panel-collapse collapse @if ($key == 0) in @endif">
                            <div class="panel-body">
                                <ul class="list-group border-left-info border-left-lg youtubeVideoList">
                                    @foreach ($class->materials as $material)
                                    <li class="list-group-item">
                                        <a href="#" @if($class->complete_status == 1 || $class->complete_status == 2) class="youtubeVideo" @else style="color: silver;" @endif videoId="{{$material->video_id}}" materialId="{{$material->id}}">
                                            <h6 class="list-group-item-heading"><i class="icon-youtube position-left"></i> {{$material->video_title}}  <span class="label bg-teal-400 pull-right">{{Helper::secondsToTime($material->video_duration)}}</span></h6>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-7">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">Youtube Video Player</h6>
                        {{-- <p><span id="current-time" class="current_dur"></span></p> --}}
                        {{-- <p><span id="material_id"></span>  --}}
                    </div>

                    <div class="panel-body" id="videoPlayerIframe">
                        <div id="video-placeholder"></div>
                    </div>
                </div>
            </div>
        </div>
    @else
    <div class="panel panel-white">
        <div class="panel-body">
            <h6 class="panel-title text-center">Materials/Video Not Found !!!</h6>
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
<script src="{{ asset('backend/assets/js/videoPlayer/iframe_api.js') }}"></script>
<script src="{{ asset('backend/assets/js/videoPlayer/script.js') }}"></script>

<script type="text/javascript">

    var window_width = $( window ).width();
    var div_width = $('.col-md-7').width() - 50;
    if(window_width < 767) {
        div_width = $('.col-md-7').width();
    }
    var player,
    time_update_interval = 0;
    
    // For fist video thumb show
    let firstVideoId = $('.youtubeVideoList li:first-child a').attr('videoId');
    var materialId = $('.youtubeVideoList li:first-child a').attr('materialId');
    
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('video-placeholder', {
            width: div_width,
            height: 400,
            videoId: firstVideoId,
            events: {
                onReady: initialize
            }
        });
    };
    
    $('.youtubeVideo').on('click', function () {
        let videoId = $(this).attr('videoId');
        materialId = $(this).attr('materialId');
        player.cueVideoById(videoId);
    });
    
    setInterval(function() {
        let dur = updateTimerDisplay();
        updateWatchTime(materialId, dur);
    }, 5000);

    function updateWatchTime(materialId, curDuration) {
        $.ajax({
            url: "{{route('updateVideoWatchTime')}}",
            data: {materialId:materialId, curDuration:curDuration, _token: '{{csrf_token()}}'},
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                if(parseInt(data.auth)===0) {
                    swal({
                        title: "Sorry!!",
                        text: "You have logged out.",
                        type: "error",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Login Now!",
                        closeOnConfirm: false
                    },
                    function(){
                        location.replace('{{route("login")}}');
                    });
                }else{
                    console.log(data.msgType);
                }
            }
        });
    }



    // For fist video thumb show End
</script>    
@endpush
