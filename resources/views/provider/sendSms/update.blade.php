@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.notification.index')}}">Notification</a></li>
            <li class="active">Create</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- Form validation -->
    <div class="panel panel-flat">
        <div class="panel-heading" style="border-bottom: 1px solid #ddd; margin-bottom: 20px;">
            <h5 class="panel-title">Notification Update</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('provider.notification.update', [$notification_info->id])}}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <fieldset class="content-group">
                    @if (session('msgType'))
                        <div id="msgDiv" class="alert alert-{{session('msgType')}} alert-styled-left alert-arrow-left alert-bordered">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            <span class="text-semibold">{{ session('msgType') }}!</span> {{ session('messege') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-styled-left alert-bordered">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            <span class="text-semibold">Opps!</span> {{ $error }}.
                        </div>
                        @endforeach
                    @endif

                    <!-- Basic text input -->

                    <div class="form-group">
                        <label class="control-label col-lg-3">Type <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select class="select-search" name="type"  id="notification_type">
                                <option value="1" @if($notification_info->type == 1) selected @endif>Course</option>
                                <option value="2" @if($notification_info->type == 2) selected @endif>Batch</option>
                                <option value="3" @if($notification_info->type == 3) selected @endif>Student</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group"id="course_list_div">
                        <label class="control-label col-lg-3">Select Course <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            @if(count($course_list) > 0)
                                <select class="select-search" name="course_id">
                                    @foreach ($course_list as $key => $course)
                                    <option value="{{$course->id}}" @if(@$notification_info->course_id == $course->id) selected @endif>
                                        {{$course->course_name}}
                                    </option>
                                    @endforeach
                                </select>
                            @else 
                                <span class="label label-danger">No Course Available</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group" style="display: none" id="batch_list_div">
                        <label class="control-label col-lg-3">Select Batch <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            @if(count($batch_list) > 0)
                                <select class="select-search" name="batch_id">
                                    @foreach ($batch_list as $key => $batch)
                                    <option value="{{$batch->id}}" @if(@$notification_info->batch_id == $batch->id) selected @endif>
                                        {{$batch->batch_no}}
                                    </option>
                                    @endforeach
                                </select>
                            @else 
                                <span class="label label-danger">No Course Available</span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group" style="display: none" id="student_list_div">
                        <label class="control-label col-lg-3">Select Student <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            @if(count($students_list) > 0)
                                <select class="select-search" name="student_id">
                                    @foreach ($students_list as $key => $student)
                                    <option value="{{$student->id}}" @if(@$notification_info->student_id == $student->id) selected @endif>
                                        {{$student->name}} [{{$student->email}}]
                                    </option>
                                    @endforeach
                                </select>
                            @else 
                                <span class="label label-danger">No Students Available</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-3">Title <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="text" name="title" class="form-control" value="{{$notification_info->title}}" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-lg-3">Details <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <textarea id="overviewDetails" name="overview" class="form-control">
                                {{$notification_info->overview}}
                            </textarea>
                        </div>
                    </div>
                    <!-- /basic textarea -->
                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="reset" class="btn btn-default" id="reset">Reset <i class="icon-reload-alt position-right"></i></button>
                    <a href="{{route('provider.notification.index')}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
                </div>
            </form>
        </div>
    </div>
    <!-- /form validation -->
</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 6000);
        @endif

        $("#overviewDetails").summernote({
            height: 150
        });
        
    });

    var notification_type = {{$notification_info->type}};

    if(notification_type == 1){
        $('#course_list_div').show();
        $('#student_list_div').hide();
        $('#batch_list_div').hide();
    }else if(notification_type == 2){
        $('#batch_list_div').show();
        $('#student_list_div').hide();
        $('#course_list_div').hide();
    }else{
        $('#batch_list_div').hide();
        $('#student_list_div').show();
        $('#course_list_div').hide();
    }

    $('#notification_type').change (function(){   
        var type = $(this).val();
        if(type == 1){
            $('#course_list_div').show();
            $('#student_list_div').hide();
            $('#batch_list_div').hide();
        }else if(type == 2){
            $('#batch_list_div').show();
            $('#student_list_div').hide();
            $('#course_list_div').hide();
        }else{
            $('#batch_list_div').hide();
            $('#student_list_div').show();
            $('#course_list_div').hide();
        }

    });

    $('#phone').keypress(function (event) {
        var keycode = event.which;
        if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
            event.preventDefault();
        }
    });

    function showPassword() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    } 
</script>
@endpush
