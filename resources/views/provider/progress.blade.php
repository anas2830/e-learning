@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.home')}}">Provider</a></li>
            <li class="active">Update</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- Form validation -->
    <div class="panel panel-flat">
        <div class="panel-heading" style="border-bottom: 1px solid #ddd; margin-bottom: 20px;">
            <h5 class="panel-title">Student Progress (%) Rate</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('provider.saveStdProgress')}}" method="POST" enctype="multipart/form-data">
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
                        <label class="control-label col-lg-3">Practice Time <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="text" value="{{@$studentProgress->practice_time}}" name="practice_time" maxlength="2" class="form-control classMark" placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required="required">
                                <span class="input-group-addon"><i class="icon-percent"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Video Watch Time <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="text" value="{{@$studentProgress->video_watch_time}}" name="video_watch_time" maxlength="2" class="form-control classMark" placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required="required">
                                <span class="input-group-addon"><i class="icon-percent"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Attendence <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="text" value="{{@$studentProgress->attendence}}" name="attendence" maxlength="2" class="form-control classMark" placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required="required">
                                <span class="input-group-addon"><i class="icon-percent"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Class Mark <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="text" value="{{@$studentProgress->class_mark}}" name="class_mark" maxlength="2" class="form-control classMark" placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required="required">
                                <span class="input-group-addon"><i class="icon-percent"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Assignment<span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="text" value="{{@$studentProgress->assignment}}" name="assignment" maxlength="2" class="form-control classMark" placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required="required">
                                <span class="input-group-addon"><i class="icon-percent"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Quiz<span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="text" value="{{@$studentProgress->quiz}}" name="quiz" maxlength="2" class="form-control classMark" placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');" required="required">
                                <span class="input-group-addon"><i class="icon-percent"></i></span>
                            </div>
                        </div>
                    </div>
        
                    <!-- /basic textarea -->
                    

                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <a href="{{route('provider.home')}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
                </div>
            </form>
        </div>
    </div>
    <!-- /form validation -->


    <!-- Footer -->
    <div class="footer text-muted">
        &copy; 2015.{{date('Y')}} <a href="#">Limitless Web App Kit</a> by <a href="#" target="_blank">Anas</a>
    </div>
    <!-- /footer -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    @if (session('msgType'))
        setTimeout(function() {$('#msgDiv').hide()}, 6000);
    @endif
</script>
@endpush
