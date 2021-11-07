@extends('layouts.default')

@push('styles')
    <style>
        .theme_perspective {
            width: 120px!important;
        }
        .theme_perspective .pace_activity {
            background-color: rgb(116, 113, 113)!important;
        }
    </style>
@endpush
@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>Home</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- Detached sidebar -->
    <div class="sidebar-detached">
        <div class="sidebar sidebar-default">
            <div class="sidebar-content">

                <!-- Sidebar search -->
                <div class="sidebar-category">
                    <div class="category-content">
                        <form action="#">
                            <div class="has-feedback has-feedback-left">
                                <input type="search" class="form-control" placeholder="Search" id="searchValue">
                                <div class="form-control-feedback">
                                    <i class="icon-search4 text-size-base text-muted"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /sidebar search -->
                {{-- <li class="navigation-header"><a href="#">Overview</a></li> --}}
                <!-- Sub navigation -->
                @if(count($course_classes) > 0)
                    @foreach ($course_classes as $class_key => $class)
                        <div class="sidebar-category classSidebar">
                            <div class="category-title" style="cursor: pointer; background-color:#eee">
                                <span class="class_name">
                                    {{$class->class_name}}
                                    @if($class->complete_status == 2)
                                        <span class="label bg-success-400">( Running )</span> 
                                    @elseif($class->complete_status == 1)
                                        <span class="label bg-info-400">( Completed )</span>
                                    @else 
                                        <span class="label bg-danger-400">( Upcomming )</span>
                                    @endif
                                </span>
                                <ul class="icons-list classList_ul">
                                    @if (!empty($upcomming_class_id))
                                        <li><a href="#" first_classID={{$upcomming_class_id}} data-id="{{$class->id}}" @if($class->id != $upcomming_class_id) class="rotate-180" @endif data-action="collapse"></a></li>
                                    @else 
                                        <li><a href="#" @if($class_key == 0) first_classID={{$class->id}} @endif data-id="{{$class->id}}" @if($class_key != 0) class="rotate-180" @endif data-action="collapse"></a></li>
                                    @endif
                                </ul>
                            </div>
                            
                            @if (!empty($upcomming_class_id))
                                <div class="category-content no-padding" @if($class->id != $upcomming_class_id) style="display: none;" @endif>
                            @else
                                <div class="category-content no-padding" @if($class_key != 0) style="display: none;" @endif>
                            @endif
                                <ul class="navigation navigation-alt navigation-accordion">
                                    @if(count($class->materials) > 0)
                                    @foreach ($class->materials as $material_key => $material)
                                    <li>
                                        <a href="#" @if($class->complete_status == 1 || $class->complete_status == 2) class="youtubeVideo" @else style="color: silver;" @endif videoId="{{$material->video_id}}" materialId="{{$material->id}}">
                                        <i class="icon-googleplus5"></i>{{$material->video_title}}
                                        <span class="label bg-success-400">{{Helper::secondsToTime($material->video_duration)}}</span>
                                        </a>
                                    </li>

                                    @endforeach
                                    @else 
                                        <p>No Materials</p>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endforeach
                @else 
                    <p>Class Not Found</p>
                @endif

            </div>
        </div>
    </div>
    <!-- /detached sidebar -->

    <!-- Detached content -->
    <div class="container-detached">
        <div class="content-detached">
            <div class="row mb-10">
                <div class="col-md-12">
                    <!-- Second navbar -->
                    <div class="navbar navbar-default navbar-xs">
                        <ul class="nav navbar-nav no-border visible-xs-block">
                            <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-circle-down2"></i></a></li>
                        </ul>

                        <div class="navbar-collapse collapse" id="navbar-second-toggle">
                            <ul class="nav navbar-nav" id="sub_menu">
                                <li class="active classDetails-overView"><a href="classDetails">Overview</a></li>
                                <li><a href="assignments">Assignments</a></li>
                                <li><a href="quiz">Quiz</a></li>
                                <li><a href="activities">Activities</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- /second navbar -->
                </div>
            </div>

            <!-- video player -->
            <div class="panel-body" id="videoPlayerIframe">
                <div id="video-placeholder"></div>
            </div>
            <!-- end video player -->
            
            <div class="" id="load_content" style="min-height: 90vh;">
            </div>
        </div>
    </div>
    <!-- /detached content -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script src="{{ asset('backend/assets/js/videoPlayer/iframe_api.js') }}"></script>
<script src="{{ asset('backend/assets/js/videoPlayer/script.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#videoPlayerIframe").hide();
        var ajax_url = location.hash.replace(/^#/, '');
        if (ajax_url.length < 1) {
            ajax_url = 'classDetails';
            window.location.hash = ajax_url;
        }
        // For Page Refresh / First time loaded
        var first_class_id = $('.classList_ul').first().find('a').attr('first_classID');
        LoadPageContent(ajax_url,first_class_id);
        $('#sub_menu li a').removeClass('active');
        $('#sub_menu li').removeClass('active');
        $('#sub_menu li').find('a[href='+ajax_url+']').parent('li').addClass('active');
        $('#sub_menu li').find('a[href='+ajax_url+']').addClass('active');

        // sub menu
        $('#sub_menu li').on('click', 'a', function(e) {
            e.preventDefault();
            $("#videoPlayerIframe").hide();
            $('#load_content').show();
            $('#sub_menu li a.active').removeClass('active');
            $('#sub_menu li').removeClass('active');
            $(this).parent('li').addClass('active');
            $(this).addClass('active');

            var url = $(this).attr('href');
            let assign_batch_class_id = $('#load_content').find('#assign_batch_class_id').val();
            if (url) {
                if (url != '#') {
                    window.location.hash = url;
                    LoadPageContent(url,assign_batch_class_id);
                }
            }
        })
        // end sub menu

        
        //category click
        $('.category-title').on('click', function(e){
            e.preventDefault();
            $("#videoPlayerIframe").hide();
            $('#load_content').show();

            if($(this).find('.classList_ul').first().find('a').hasClass("rotate-180")){
                // alert('rotate');
                $(this).next(".category-content").show();
                $(this).find('.classList_ul').first().find('a').removeClass("rotate-180");
                $('#sub_menu li').removeClass('active');
                $('#sub_menu li a').removeClass('active');
                $('.classDetails-overView').addClass('active');
                var batch_class_id = $(this).find('.classList_ul').first().find('a').attr('data-id');
                var url = 'classDetails';
                if (url) {
                    LoadPageContent(url, batch_class_id);
                }
            }else{
                // alert('no rotate');
                $(this).find('.classList_ul').first().find('a').addClass("rotate-180");
                $(this).next(".category-content").hide();
            }
        });

        //Show classOverview from left sidebar
        $('.classList_ul li').on('click', 'a', function(e) {
            e.preventDefault();
            if($(this).hasClass("rotate-180")){
                // alert('a rotate');
                $(this).removeClass("rotate-180");
            }else{
                // alert('a no rotate');
                $(this).addClass("rotate-180");
            }

            $("#videoPlayerIframe").hide();
            $('#load_content').show();
        })
        //End Show classOverview

        $("#searchValue").on("keyup", function(e) {
            e.preventDefault();
            var value = $(this).val().toLowerCase();
            $(".classSidebar").filter(function() {
                $(this).toggle($(this).find('.class_name').text().toLowerCase().indexOf(value) > -1);
            });
        });
    })

    function LoadPageContent(url,batch_class_id=false) {
        $('#load_content').html(`<div class="theme_perspective preloader"><div class="pace_activity"></div><div class="pace_activity"></div><div class="pace_activity"></div><div class="pace_activity"></div></div>`);
        $.ajax({
            mimeType: 'text/html; charset=utf-8', // ! Need set mimeType only when run from local file
            url: url,
            data: {'batch_class_id': batch_class_id },
            type: "GET",
            dataType: "html",
            success: function (data) {
                if (parseInt(data) === 0) {
                    $('.preloader').show();
                } 
                else {
                    $('.preloader').hide();
                    $('#load_content').html(data);
                }
            }
        });
    }

    // for load video
    var player,
    time_update_interval = 0;
    var materialId = 0;

    // var window_width = $( window ).width();
    // var div_width = $('#videoPlayerIframe').width() - 50;
    // if(window_width < 767) {
    //     div_width = $('#videoPlayerIframe').width();
    // }

    div_width = $('#videoPlayerIframe').width();

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('video-placeholder', {
            width: div_width,
            height: 600,
            videoId: '',
            events: {
                onReady: initialize
            }
        });
    };
    
    $('.youtubeVideo').on('click', function () {
        let videoId = $(this).attr('videoId');
        materialId = $(this).attr('materialId');
        $('#videoPlayerIframe').show();
        $('#load_content').hide();
        $('#sub_menu li').removeClass('active');
        player.cueVideoById(videoId);
    });
    
    setInterval(function() {
        let dur = updateTimerDisplay();
        if(dur != "0:00"){
            console.log(dur);
            updateWatchTime(materialId, dur);
        }
    }, 5000);

    function updateWatchTime(materialId, curDuration) {
        var assign_batch_class_id = $('#load_content').find('#assign_batch_class_id').val();

        $.ajax({
            url: "{{route('updateVideoWatchTime')}}",
            data: {materialId:materialId, batch_class_id: assign_batch_class_id, curDuration:curDuration, _token: '{{csrf_token()}}'},
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

    // end for load video
</script>    
@endpush
