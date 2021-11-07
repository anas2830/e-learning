@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.eventSms.index')}}">SMS</a></li>
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
            <h5 class="panel-title">Event SMS Update</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" id="eventSmsForm" action="{{route('provider.eventSms.update', [$eventSmsInfo->id])}}" method="POST" enctype="multipart/form-data">
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

                    <div class="form-group"  id="sms_type_list_div">
                        <label class="control-label col-lg-3">Select Sms Type <span class="text-danger">*</span></label>
                        <div class="col-lg-9" id="sms_type">
                            <select class="select-search" name="sms_type" id="sms_type" disabled>
                                <option value="">Select</option>
                                <option value="1" @if($eventSmsInfo->type == 1) selected @endif>Register Student</option>
                                <option value="2" @if($eventSmsInfo->type == 2) selected @endif>Assign Student</option>
                                <option value="3" @if($eventSmsInfo->type == 3) selected @endif>Class Schedule Change</option>
                                <option value="4" @if($eventSmsInfo->type == 4) selected @endif>Absent Class</option>
                            </select>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="control-label col-lg-3">Message <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <span class="register_user" style="display: none">use @name@ for student name</span>
                            <span class="assign_student" style="display: none">use @name@, @batch@, @course@ for student name, batch and course</span>
                            <span class="class_schedule" style="display: none">use @name@, @batch@, @course@ for student name, batch and course</span>
                            <span class="absent_class" style="display: none">use @name@, @class@, @batch@, @course@ for student name, class, batch and course</span>
                            <textarea name="message" cols="5" rows="3" class="form-control">{!! $eventSmsInfo->message !!}</textarea>
                        </div>
                    </div>

                    <div class="form-group"  id="status_div">
                        <label class="control-label col-lg-3">Select Status <span class="text-danger">*</span></label>
                        <div class="col-lg-9" id="status">
                            <select class="select-search" name="status" id="status">
                                <option value="">Select</option>
                                <option value="1" @if($eventSmsInfo->status == 1) selected @endif>Active</option>
                                <option value="0"  @if($eventSmsInfo->status == 0) selected @endif>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <!-- /basic textarea -->
                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="reset" class="btn btn-default" id="reset">Reset <i class="icon-reload-alt position-right"></i></button>
                    <a href="{{route('provider.eventSms.index')}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
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


        $("#eventSmsForm").on('change', '#sms_type', function(){
            var sms_type=$(this).val();

            if(sms_type){
                if(sms_type == 1){
                    $('.register_user').show();

                    $('.assign_student').hide();
                    $('.absent_class').hide();
                    $('.class_schedule').hide();

                }else if(sms_type == 2){
                    $('.assign_student').show();

                    $('.register_user').hide();
                    $('.absent_class').hide();
                    $('.class_schedule').hide();
                }else if(sms_type == 3){
                    $('.class_schedule').show();

                    $('.register_user').hide();
                    $('.assign_student').hide();
                    $('.absent_class').hide();

                }else if(sms_type == 4){
                    $('.absent_class').show();

                    $('.register_user').hide();
                    $('.assign_student').hide();
                    $('.class_schedule').hide();
                }
            }
        });


    });

</script>
@endpush
