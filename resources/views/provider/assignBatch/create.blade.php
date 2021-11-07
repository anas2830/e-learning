@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.batch.index')}}">Assign Batch</a></li>
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
            <h5 class="panel-title">Assign Batch Create</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('provider.batch.store')}}" method="POST">
                @csrf
                <fieldset class="content-group">
                    @if (session('msgType'))
                        <div id="msgDiv" class="alert alert-{{ session('msgType') }} alert-styled-left alert-arrow-left alert-bordered">
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
                        <label class="control-label col-lg-2">Batch Name <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" name="batch_no" required="" placeholder="Batch No-01">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Select Course</label>
                        <div class="col-lg-10">
                            <select class="select-search" name="course_id" required="">
                                <option value="">Select</option>
                                @foreach ($courses as $course)
                                <option value="{{$course->id}}">{{$course->course_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Start Date <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="date" class="form-control" name="start_date" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Start Time <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="time" class="form-control" name="start_time" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Batch Fb url</label>
                        <div class="col-lg-10">
                            <textarea name="batch_fb_url" class="form-control"></textarea>
                        </div>
                    </div>
                    <!-- /basic text input -->

                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="reset" class="btn btn-default" id="reset">Reset <i class="icon-reload-alt position-right"></i></button>
                    <a href="{{route('provider.batch.index')}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
                </div>
            </form>
        </div>
    </div>
    <!-- /form validation -->


    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">Rafikul Islam</a>
    </div>
    <!-- /footer -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif

        $('.bootstrap-switch-on').on('click', function() {
            if($('input[type="checkbox"]').is(":checked")){
                $('#price').prop("disabled", false);
            }
            else if($('input[type="checkbox"]').is(":not(:checked)")){
                $('#price').prop("disabled", true);
            }
        });
        $('.bootstrap-switch-off').on('click', function() {
            if($('input[type="checkbox"]').is(":checked")){
                $('#price').prop("disabled", false);
            }
            else if($('input[type="checkbox"]').is(":not(:checked)")){
                $('#price').prop("disabled", true);
            }
        });
    })
</script>
@endpush
