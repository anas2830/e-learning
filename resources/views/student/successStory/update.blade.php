@extends('layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('mySuccessStory.index')}}">Support</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <!-- Data Table -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Request List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('mySuccessStory.create')}}" class="btn btn-primary add-new">Take Support</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('mySuccessStory.update', [$success_story_info->id])}}" method="POST" enctype="multipart/form-data">
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
                        <label class="control-label col-lg-2">Select Marketplace <span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <select class="select-search" name="marketplace_name" required="required">
                                <option value="">Select</option>
                                <option @if ($success_story_info->marketplace_name == 'Fiverr') selected @endif value="Fiverr">Fiverr</option>
                                <option @if ($success_story_info->marketplace_name == 'Upwork') selected @endif value="Upwork">Upwork</option>
                                <option @if ($success_story_info->marketplace_name == 'Freelancer') selected @endif value="Freelancer">Freelancer</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2" for="work_amount">Job Amount ($)<span class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" id="work_amount" name="work_amount" class="form-control" required="required" placeholder="Job Amount" value="{{$success_story_info->work_amount}}">
                        </div>
                    </div>
                    <!-- Image input -->
                    <div class="form-group">
                        <label class="col-lg-2 control-label text-semibold">Screenshort</label>
                        <div class="col-lg-6">
                            <div class="file-preview" id="custom_file_preview">
                                <div class="close fileinput-remove text-right" id="custom_close">×</div>
                                <div class="file-preview-thumbnails">
                                    <div class="file-preview-frame" id="preview-1603644588432-0">
                                        <img src="{{ asset('uploads/studentStory/usedImg/'.$success_story_info->work_screenshort)}}" class="file-preview-image" title="{{$success_story_info->work_screenshort}}" alt="{{$success_story_info->work_screenshort}}" style="width:auto;height:160px;">
                                    </div>
                                </div>
                                <div class="clearfix"></div>   
                                <div class="file-preview-status text-center text-success"></div>
                                <div class="kv-fileinput-error file-error-message" style="display: none;"></div>
                                <input type="hidden" name="work_screenshort" value="{{$success_story_info->work_screenshort}}">
                            </div>
                            <div id="custom_file_input" style="display: none;">
                                <input type="file" name="work_screenshort" class="file-input-extensions">
                                <span class="help-block">Allow extensions: <code>jpg</code>, <code>png</code> and <code>jpeg</code> and  Allow Size: <code>639 * 326</code> or more than Only</span>
                            </div>
                        </div>
                    </div>
                    <!-- /Image input -->
                    <div class="form-group">
                        <label for="own_comment" class="control-label col-lg-2">Comment</label>
                        <div class="col-lg-10">
                            <textarea name="own_comment" id="own_comment" class="form-control" cols="3">{{$success_story_info->own_comment}}</textarea>
                        </div>
                    </div>
                    <!-- /basic textarea -->
                    

                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <a href="{{route('mySuccessStory.index')}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
                </div>
            </form>
        </div>
    </div>
    <!-- /Data Table -->

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
        $('#work_amount').keypress(function (event) {
            var keycode = event.which;
            if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
                event.preventDefault();
            }
        });

        $(document).ready(function(){
            @if (session('msgType'))
                setTimeout(function() {$('#msgDiv').hide()}, 6000);
            @endif

            $("#request_reasons").summernote({
                height: 150
            });
        });
    </script>
@endpush