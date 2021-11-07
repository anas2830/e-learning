@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.courseAssignmentArchive.index', ['class_id'=>$class_info->id])}}">Assignments</a></li>
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
            <h5 class="panel-title">[{{$class_info->class_name}}] Archive Assignments Create</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('provider.courseAssignmentArchive.store')}}" method="POST" enctype="multipart/form-data">
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
                        <label for="title" class="control-label col-lg-2">Title <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="text" id="title" class="form-control" name="title" required="" placeholder="Assignment Title">
                            <input type="hidden" name="class_id" value="{{$class_info->id}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Overview</label>
                        <div class="col-lg-10">
                            <textarea id="overview" name="overview" class="form-control" required="required"></textarea>
                        </div>
                    </div>

                    <!-- Image input -->
                    <div class="form-group">
                        <label class="col-lg-2 control-label text-semibold">Attachments</label>
                        <div class="col-lg-6">
                            <input type="file" name="attachment" class="file-input">
                            <span class="help-block">Allow extensions: <code>jpg/jpeg</code>, <code>png</code>, <code>pdf</code> , <code>doc</code>, <code>docx</code> and  <code>zip</code>and  Allow Size: <code>5 MB</code> Only</span>
                        </div>
                    </div>
                    <!-- /Image input -->
                    <!-- /basic textarea -->
                    

                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="reset" class="btn btn-default" id="reset">Reset <i class="icon-reload-alt position-right"></i></button>
                    <a href="{{route('provider.courseAssignmentArchive.index', ['class_id'=>$class_info->id])}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
                </div>
            </form>
        </div>
    </div>
    <!-- /form validation -->

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
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 6000);
        @endif
        
        $("#overview").summernote({
            height: 150
        });
    })
</script>
@endpush
