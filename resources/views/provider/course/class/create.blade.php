@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.courseAddClass.index')}}">Class</a></li>
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
            <h5 class="panel-title">({{$course_info->course_name}}) Class Create</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('provider.courseAddClass.store')}}" method="POST">
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
                        <label for="class_name" class="control-label col-lg-3">Class Name <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="text" id="class_name" name="class_name" class="form-control" required="required" placeholder="Class Name">
                            <input type="hidden" name="course_id" value="{{$course_info->id}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="class_overview" class="control-label col-lg-3">Class Overview <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <textarea id="class_overview" name="class_overview" class="form-control" required="required"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="class_resource" class="control-label col-lg-3">Class Resource <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <textarea id="class_resource" name="class_resource" class="form-control" required="required"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="video_id" class="control-label col-lg-3">Video Id <span class="text-danger">*</span></label>

                        <div id="video_plus">
                            <div class="video_top">
                                <div class="col-lg-6">
                                    <input type="text" required id="video_id" class="form-control" name="video_id[]" placeholder="Video Id">
                                </div>
                                <div class="col-lg-2 pl0 pr0" id="first_row">
                                    <button class="btn btn-default pub-plus" type="button"><i class="icon-googleplus5"></i></button>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                      
                        <div id="video_plus_clone" style="display: none;">
                            <div class="video_top video_top_firstRow col-lg-offset-3">
                                <div class="col-lg-8" style="margin-top: 1%">
                                    <input required type="text" class="form-control" id="video_id" name="video_id[]" placeholder="Video Id">
                                </div>
                                <div class="col-lg-2 pub-plus-minus" style="margin-top:1%">
                                    <button class="btn btn-default pub-minus" type="button"><i class="icon-minus-circle2"></i></button>
                                    <button class="btn btn-default pub-plus" type="button"><i class="icon-googleplus5"></i></button>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                    </div>
                    <!-- /basic textarea -->

                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="reset" class="btn btn-default" id="reset">Reset <i class="icon-reload-alt position-right"></i></button>
                    <a href="{{route('provider.courseAddClass.index', ['course_id'=>$course_id])}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
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
            setTimeout(function() {$('#msgDiv').hide()}, 6000);
        @endif

        $("#class_overview").summernote({
            height: 150
        });
        $("#class_resource").summernote({
            height: 150
        });

        $('#video_plus').on('click', '.pub-plus', function(){
            videoTrAdd('video_plus');
        });

        $('#video_plus').on('click', '.pub-minus', function(){
            videoTrRemove('video_plus', $(this));
        });
    })

    function videoTrAdd(selector) {
        var sn = parseInt($('#'+selector).find('.td-sn').last().html())+1;
        $('#'+selector).append($('#'+selector+'_clone').html());
        var $lastChild = $('#'+selector).find('.video_top_firstRow').last();
        $('#'+selector).find('.pub-plus').not($('#'+selector+' .pub-plus').last()).remove();
    }

    function videoTrRemove(selector, $that) {
        var $row = $that.parents('.video_top_firstRow').remove();
        $row.remove();
        if($('#'+selector+' .pub-plus-minus').length==1) {
            $('#'+selector+' .pub-plus-minus').html('<button class="btn btn-default pub-minus" type="button"><i class="icon-minus-circle2 pub-minus"></i></button><button class="btn btn-default pub-plus" type="button"> <i class="icon-googleplus5"></i></button>');
        } else if($('#'+selector+' .pub-plus-minus').length > 1) {
            $('#'+selector+' .pub-plus-minus').last().html('<button class="btn btn-default pub-minus" type="button"><i class="icon-minus-circle2 pub-minus"></i> <button class="btn btn-default pub-plus" type="button"><i class="icon-googleplus5"></i></button>');
        } else {
            $('#first_row').html('<button class="btn btn-default pub-plus" type="button"><i class="icon-googleplus5"></i></button>');
        }
    }
</script>
@endpush
