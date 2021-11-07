@extends('layouts.default')

@push('styles')
<link href="{{ asset('web_graph/line_chart/css/lineStyle.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('web/slick/slick.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('web/slick/slick-theme.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('web/slick/customSlider.css') }}" rel="stylesheet" type="text/css"/>

<style>

</style>
@endpush
@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li class="active"><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content @if (@$student_course_info->is_freez == 1) lfwf-freez-content @endif">
    @if (@$student_course_info->is_freez == 1)
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-body border-top-warning text-center lfwf-suspend-div" style="border-top-width: 6px;">
                <div class="media">
                    <div class="media-body">
                        <h4 class="media-heading text-semibold"> <mark>Warning:  </mark>  Your Account has been Freezed !!!</h4>
                        <h5>You can't access other pages or do anything, Please contact with support  +8801889972995 / +880 9513-828206</h5>
                        <h6>Reason: {!! @$student_course_info->freez_reason !!} </h6>
                        <!--<h6>Reason: {!! strip_tags(@$student_course_info->freez_reason) !!} </h6>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-lg-3 col-md-6">
            
            <div class="thumbnail lfwf-p-thumbnail">
                <div class="thumb">
                    @if( !empty($userInfo->image) || $userInfo->image != Null)
                        <img src="{{ asset('uploads/studentProfile/'.$userInfo->image)}}" alt="{{$userInfo->image}}" style="max-height: 260px!important;">
                    @else
                        <img src="{{ asset('backend/assets/images/placeholder.jpg') }}" alt="" style="max-height: 260px!important;">
                    @endif
                </div>
            
                <div class="caption text-center">
                    <div class="lfwf-student-name"> 
                        <h6 class="text-semibold no-margin">{{$userInfo->name}}  </h6>
                        <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M7.7,145.6C109,125,299.9,116.2,401,121.3c42.1,2.2,87.6,11.8,87.3,25.7" stroke="#f39c12" fill="none" stroke-width="14px"/></svg>
                    </div>
                    <p class="course-name"><strong> Course: </strong>{{@$running_course_info->course_name}}</p>
                    <ul class="icons-list mt-15">
                        <li><span class="batch_no">{{@$assigned_batch_info->batch_no}}</span></li>
                        <li><a href="{{@$assigned_batch_info->batch_fb_url}}" target="_blank" data-popup="tooltip" title="Facebook Group" data-container="body"><i class="icon-facebook2"></i></a></li>
                        {{-- <li><a href="#" data-popup="tooltip" title="Google Drive" data-container="body"><i class="icon-google-drive"></i></a></li> --}}
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-6">
            <div class="panel dashboard-graph">
                <figure class="highcharts-figure">
                    <div id="container"></div>
                  </figure>
            </div>
        </div>
    </div>

    <!-- <h4 class="text-center content-group">
        Class Overview & Support
    </h4>  -->
    
    @if (isset($student_course_info))
            
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-body">
                    <div class="media">
                        <div class="media-left">
                            <a href="#"><i class="icon-file-text2 text-danger-400 icon-2x no-edge-top mt-5"></i></a>
                        </div>

                        <div class="media-body">
                            <h6 class="media-heading text-semibold"><a href="@if(!empty($completed_class)) {{route('class', ['class_id'=>$completed_class->id] )}} @else # @endif" class="text-default">Last completed class</a></h6>
                            @if(!empty($completed_class))
                                {{$completed_class->class_name}}
                            @else
                            <span class="label label-danger">Class not found</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-body">
                    <div class="media">
                        <div class="media-left">
                            <a href="#"><i class="icon-file-text2 text-success-400 icon-2x no-edge-top mt-5"></i></a>
                        </div>

                        <div class="media-body">
                            <h6 class="media-heading text-semibold"><a href="@if(!empty($upcomming_class)) {{route('class', ['class_id'=>$upcomming_class->id] )}} @else # @endif" class="text-default">Upcomming Class</a></h6>
                            @if(!empty($upcomming_class))
                                {{$upcomming_class->class_name}}
                            @else
                            <span class="label label-danger">Class not found</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-body">
                    <div class="media">
                        <div class="media-left">
                            <a href="{{route('takeSupport.index')}}"><i class="icon-file-xml text-info icon-2x no-edge-top mt-5"></i></a>
                        </div>

                        <div class="media-body">
                            <h6 class="media-heading text-semibold"><a href="{{route('takeSupport.index')}}" class="text-default">Help & Support</a></h6>
                            <span style="color:red">Get Live Support</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    <h4 class="text-center content-group student-success-story">
       Student Success Stories 
    </h4> 
    <hr>

    <div class="slider-container">
        <div class="lfwf-slider ">
            @if (count($student_success_stories) > 0)
                @foreach ($student_success_stories as $key => $success_item)
                <div class="slider-item">
                    <div class="slider-content">
                        <div class="slider-top">
                            <input type="hidden" name="success_story_id" value="{{$success_item->id}}">
                            <img src="{{asset('uploads/studentStory/usedImg/'.$success_item->work_screenshort)}}" alt="">
                            <div class="slider-hover-react">
                                <div class="slider-icon-inner">
                                    <div class="slider-icon-container">
                                        <div class="love-icon @if (@Helper::getReactStatus($success_item->id, Auth::id()) == 1) love-checked @endif">
                                            <i class="icon-heart5 love-fill"></i>
                                            <i class="icon-heart6 love-outline"></i>
                                            <span class="like-counter">{{$success_item->total_reaction}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{route('std-success')}}">
                            <div class="slider-mid">
                                <div class="slider-left">
                                    <div class="left-thumb">
                                        <img src="{{asset('uploads/studentProfile/thumb/'.Helper::studentProfileImg($success_item->created_by))}}" alt="">
                                    </div>
                                    <div class="left-student-info">
                                        <h2 class="slider-student-name">{{Helper::studentName($success_item->created_by)}}</h2>
                                        <h5 class="slider-course-name">{{Helper::courseName($success_item->course_id)}}</h5>
                                        <h3 class="slider-batch-name">{{Helper::batchName($success_item->batch_id)}}</h3>
                                    </div>
                                </div>
                                <div class="slider-right">
                                    <h3 class="marketplace-name">{{$success_item->marketplace_name}}</h3>
                                    <h4 class="job-ammount">${{$success_item->work_amount}}</h4>
                                </div>
                            </div>
                        </a>
                        <div class="slider-bottom">
                            <p> <span>"</span> {!! Str::words($success_item->own_comment, 25, '...') !!} <span>"</span></p>
                        </div>
                    </div>
                </div>
                @endforeach
            @else 
                <div class="col-md-12">
                    <div class="panel panel-body">
                        <div class="media">
                            <div class="media-body">
                                <h6 class="media-heading text-semibold">There have no Success Story</h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="slider-read-more">
            <a href="{{route('std-success')}}">
                <ul>
                    @if (count($student_success_stories) > 0)
                        @foreach ($student_success_stories as $key => $success_item)
                        <li><img src="{{asset('uploads/studentProfile/thumb/'.Helper::studentProfileImg($success_item->created_by))}}" alt=""></li>
                        @endforeach
                        <span> + {{number_format($student_total_success)}} </span>
                    @endif
                </ul>
            </a>
        </div>
    </div>
        
        <h4 class="text-center content-group">
            All Personal News
        </h4>
        <hr>
        @if (count($all_personal_news) > 0)
            <!-- Info blocks -->
            <div class="row">
                @foreach ($all_personal_news as $key => $widget)
                <div class="col-md-4">
                    <div class="panel">
                        <div class="panel-body text-center">
                            <div class="icon-object border-success-400 text-success"><i class="icon-book"></i></div>
                            <h5 class="text-semibold">{{$widget['title']}}</h5><hr>
                            <p class="mb-15">{!! $widget['overview'] !!}</p>
                            {{-- <a href="#" class="btn bg-success-400">Browse articles</a> --}}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else 
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-group panel-group-control content-group-lg" id="accordion-control">
                        <div class="panel panel-white">
                            <div class="panel-heading">
                                <h6 class="panel-title text-center">Widget Data Not Found !!!</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif 

        <h4 class="text-center content-group">
            All News By Teacher
        </h4>
        <hr>
        @if (count($all_teacher_news) > 0)
            <!-- Info blocks -->
            <div class="row">
                @foreach ($all_teacher_news as $key => $widget)
                <div class="col-md-4">
                    <div class="panel">
                        <div class="panel-body text-center">
                            <div class="icon-object border-success-400 text-success"><i class="icon-book"></i></div>
                            <h5 class="text-semibold">{{ $widget['title'] }}</h5><hr>
                            <p class="mb-15">{!! $widget['overview'] !!}</p>
                            {{-- <a href="#" class="btn bg-success-400">Browse articles</a> --}}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else 
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-group panel-group-control content-group-lg" id="accordion-control">
                        <div class="panel panel-white">
                            <div class="panel-heading">
                                <h6 class="panel-title text-center">Widget Data Not Found !!!</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif 

        <h4 class="text-center content-group">
            All Latest By Authority
        </h4>
        <hr>
        @if (count($all_provider_news) > 0)
            <!-- Info blocks -->
            <div class="row">
                @foreach ($all_provider_news as $key => $widget)
                <div class="col-md-4">
                    <div class="panel">
                        <div class="panel-body text-center">
                            <div class="icon-object border-success-400 text-success"><i class="icon-book"></i></div>
                            <h5 class="text-semibold">{{ $widget['title'] }}</h5><hr>
                            <p class="mb-15">{!! $widget['overview'] !!}</p>
                            {{-- <a href="#" class="btn bg-success-400">Browse articles</a> --}}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else 
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-group panel-group-control content-group-lg" id="accordion-control">
                        <div class="panel panel-white">
                            <div class="panel-heading">
                                <h6 class="panel-title text-center">Widget Data Not Found !!!</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif 
        <!-- /info blocks -->
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-body border-top-warning text-center">
                    <h6 class="no-margin text-semibold">No Course Found !!!</h6>
                    <p class="content-group-sm text-muted">Please Enroll our Course!</p>
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
<script src="{{ asset('web_graph/line_chart/js/highcharts.js') }}"></script>
<script src="{{ asset('web_graph/line_chart/js/data.js') }}"></script>
<script src="{{ asset('web_graph/line_chart/js/exporting.js') }}"></script>
<script src="{{ asset('web_graph/line_chart/js/export-data.js') }}"></script>
<script src="{{ asset('web_graph/line_chart/js/accessibility.js') }}"></script>

<script type="text/javascript" src="{{ asset('web/slick/slick.min.js') }}"></script>
<script type="text/javascript">
    // Slider
    $(document).ready(function(){
        // Love Toggle class
        $(".slider-top").click(function(){
            var $selector = $(this);
            $(this).find(".love-icon").toggleClass("love-checked");
            let success_story_id = $selector.find("[name='success_story_id']").val();
            let count = $selector.find('.like-counter').text();

            $.ajax({
                url: "{{route('storyReactUpdate')}}",
                data: {success_story_id:success_story_id, _token: '{{csrf_token()}}'},
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if(parseInt(response.auth)===0) {
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
                    } else if(parseInt(response.status) == 1){
                        $selector.find('.like-counter').text(response.total_reactions);
                        console.log(response.message, response.total_reactions);
                    } else {
                        console.log(response.message);
                    }
                }
            });
        });

        $('.lfwf-slider').slick({
            arrows: true,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 4000,
            centerPadding: '30px',
            adaptiveHeight: true,
            infinite: true,
            speed: 600,
            easing: 'ease-in-out',
            dots: true,
            swipe: true,
            swipeToSlide: true,
            touchMove: true,
            lazyLoad: 'ondemand',
            slidesToShow: 4,
            slidesToScroll: 4,
            responsive: [
                {
                breakpoint: 1400,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
                },
                {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
                },
                {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false
                }
                }
            ]
        });
    });
</script>
<!-- Line Chart -->
<script>

var arrayFromPHP = <?php echo json_encode($all_assign_classes) ?>;
var catArray = [];
var averagePer = [];

$.each(arrayFromPHP, function (i, elem){
    catArray.push(elem.std_class_name);
    // var total_average_value = elem.std_class_practiceTime+elem.std_class_attend+elem.std_class_mark+elem.std_class_video+elem.std_class_assignment+elem.std_class_exam;

    pr_std_class_practiceTime = parseInt(elem.std_class_practiceTime) > 100 ? 100 : parseInt(elem.std_class_practiceTime);
    pr_std_class_attend = parseInt(elem.std_class_attend) > 100 ? 100 : parseInt(elem.std_class_attend);
    pr_std_class_mark = parseInt(elem.std_class_mark) > 100 ? 100 : parseInt(elem.std_class_mark);
    pr_std_class_video = parseInt(elem.std_class_video) > 100 ? 100 : parseInt(elem.std_class_video);
    pr_std_class_assignment = parseInt(elem.std_class_assignment) > 100 ? 100 : parseInt(elem.std_class_assignment);
    pr_std_class_exam = parseInt(elem.std_class_exam) > 100 ? 100 : parseInt(elem.std_class_exam);
    var total_average_value = pr_std_class_practiceTime + pr_std_class_attend + pr_std_class_mark + pr_std_class_video + pr_std_class_assignment + pr_std_class_exam;
    
    averagePer.push(parseInt( total_average_value/6));
});


 Highcharts.chart('container', {
  chart: {
    type: 'line',
    scrollablePlotArea: {
      minWidth: 600,
      scrollPositionX: 0
    }
  },
  title: {
    text: '',
    align: 'left'
  },
  subtitle: {
    text: '',
    align: 'left'
  },
  xAxis: {
    categories: catArray,
    crosshair: true
   },
  yAxis: {
    min: 0,
    max:100,
    title: {
      text: 'Mark In (%)'
    },
    minorGridLineWidth: 0,
    gridLineWidth: 0,
    alternateGridColor: null,
    plotBands: [{ // Red Zone
      from: 0,
      to: 40,
      color: 'rgba(244, 67, 54,.4)',
      label: {
        text: 'Red Zone',
        style: {
          color: '#f44336'
        }
      }
    }, { // Blue Zone
      from: 41,
      to: 70,
      color: 'rgba(33, 150, 243,.2)',
      label: {
        text: 'Blue Zone',
        style: {
          color: '#4CAF50'
        }
      }
    }, { // Green Zone
      from: 71,
      to: 99,
      color: 'rgba(76, 176, 81,.4)',
      label: {
        text: 'Green Zone',
        style: {
          color: '#2196F3'
        }
      }
    }]
  },
  tooltip: {
    valueSuffix: '%'
  },
  plotOptions: {
    spline: {
      lineWidth: 4,
      states: {
        hover: {
          lineWidth: 5
        }
      },
      marker: {
        enabled: false
      },
      pointInterval: 3600000, // one hour
      pointStart: 0
    }
  },
  series: [{
    name: 'Performance',
    data: averagePer
    // data: [50, 71, 10, 12, 14, 17, 13, 14, 21]

  }],
  navigation: {
    menuItemStyle: {
      fontSize: '10px'
    }
  }
});
</script>
@endpush

