@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.batchstuStudentGiveMark')}}">Assignment Marking</a></li>
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
            <h5 class="panel-title">Assignment Marking of {{$student_name}} </h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li style="margin-right: 10px;"><a href="{{route('teacher.improveAssignment')}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('teacher.improveAssignmentMarkSave')}}" method="POST" enctype="multipart/form-data">
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
                        <label class="control-label col-lg-2">Comment <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <textarea name="comment" id="comment_overview" class="form-control" placeholder="Say Something" required=""></textarea>
                            <input type="hidden" name="submission_id" value="{{$submission_id}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Mark <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="text" name="mark"  maxlength="3" class="form-control" placeholder="given mark" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                    </div>

                    <!-- Image input -->
                    <div class="form-group">
                        <label class="col-lg-2 control-label text-semibold">Attachments</label>
                        <div class="col-lg-6">
                            <input type="file" name="attachment" class="file-input">
                            <span class="help-block">Allow extensions: <code>jpeg</code> , <code>jpg</code>, <code>png</code> and  Allow Size: <code>1 MB</code> Only</span>
                        </div>
                    </div>
                    <!-- /Image input -->
                    <!-- /basic textarea -->
                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="reset" class="btn btn-default" id="reset">Reset <i class="icon-reload-alt position-right"></i></button>
                    <a href="{{route('teacher.improveAssignment')}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
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
        
        $("#comment_overview").summernote({
            height: 150
        });
    })
</script>
@endpush
