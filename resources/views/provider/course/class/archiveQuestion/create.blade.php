@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.courseQuestionArchive.index', ['class_id'=>$class_info->id])}}">Question</a></li>
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
            <h5 class="panel-title">[{{$class_info->class_name}}] Archive Question Create</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('provider.courseQuestionArchive.store')}}" method="POST">
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
                    <input type="hidden" name="class_id" value="{{$class_info->id}}">
                    <div class="form-group">
                        <label class="control-label col-lg-2">Question <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <textarea id="overviewDetails" name="question" class="form-control">
                            </textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Answer Type <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <select class="select-search" name="answer_type"  id="answer_type" required>
                                <option value="">Select Type</option>
                                @foreach ($answer_types as $type)
                                <option value="{{$type->id}}">{{$type->answer_type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="answerDiv" style="display:none;">
                        <label class="control-label col-lg-2">Answer <span class="text-danger">*</span></label>
                        <div class="col-lg-10" id="answer_view_1" style="display:none;">
                            <div class="checkbox checkbox-switch">
                                {{-- <label class=toggle data-on=True data-off=False>
                                    <input type=checkbox id="answer" name="answer_tf" value="1"> <span class=button-checkbox></span>
                                </label> --}}
                                <label>
                                    <input type="checkbox" data-on-text="True" data-off-text="False" class="switch" id="answer" name="answer_tf" value="1" checked="checked">
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-10" id="answer_view_2_3" style="display:none;">
                            <div class="row">
                                <div class="col-md-9 pr0 mb10">
                                    <input name="answer[]" placeholder="Answer" class="form-control mb-5">
                                </div>
                                <div class="col-md-3 btnView">
                                    <div class="toggle-custom mr10" style="display: inline-block">
                                        <label class="toggle answerCheck" data-on="Yes" data-off="No">
                                            <input name="true_answer[]" class="trueAnswer mr-5" type="radio" value="0"> <span class="button-radio"></span>
                                        </label>
                                    </div>
                                    <button id="answer_add" class="btn btn-default ml5" type="button"><i class="icon-plus-circle2"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /basic textarea -->
                    

                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <a href="{{route('provider.courseQuestionArchive.index', ['class_id'=>$class_info->id])}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
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

        $("#overviewDetails").summernote({
            height: 150
        });

        $('#answer_type').change(function(){
            var answer_type = $(this).val();
            if(answer_type) {
                if(answer_type==1) {
                    $("#answer_view_1").show();
                    $("#answer_view_2_3").hide();
                } else {
                    $("#answer_view_2_3").show();
                    $("#answer_view_1").hide();

                    if(answer_type==2) {
                        $(".answerCheck").find("input").attr("type", "radio");
                        $(".answerCheck").find("span").attr("class", "button-radio");
                    } else {
                        $(".answerCheck").find("input").attr("type", "checkbox");
                        $(".answerCheck").find("span").attr("class", "button-checkbox");
                    }
                }
                $("#answerDiv").show();
            }else{
                $("#answerDiv").hide();
            }
        });
        
        $("#answerDiv").on("click", "#answer_add", function(){
            var answer_type = $("#answer_type").val();
            $("#answer_view_2_3").find("#answer_add").remove();
            var trueAnswer = $("#answer_view_2_3").find(".trueAnswer:last").val();
            $("#answer_view_2_3").append('<div class="row"><div class="col-md-9 pr0 mb10"><input name="answer[]" placeholder="Answer" class="form-control mb-5"></div><div class="col-md-3 btnView"><div class="toggle-custom mr10" style="display: inline-block"><label class="toggle answerCheck" data-on="Yes" data-off="No"><input name="true_answer[]" class="trueAnswer mr-5" value="'+(parseInt(trueAnswer)+1)+'" type="'+((answer_type==2)?'radio':'checkbox')+'"> <span class="button-'+((answer_type==2)?'radio':'checkbox')+'"></span></label></div><button id="answer_remove" class="btn btn-default ml5" type="button"><i class="icon-minus-circle2"></i></button><button id="answer_add" class="btn btn-default ml5" type="button"><i class="icon-plus-circle2"></i></button></div></div>');

            if($("#answer_view_2_3").find(".btnView:first").find("#answer_remove").length<=0) {
                $("#answer_view_2_3").find(".btnView:first").append('<button id="answer_remove" class="btn btn-default ml5" type="button"><i class="icon-minus-circle2"></i></button>');
            }
        });

        $("#answerDiv").on("click", "#answer_remove", function(){
            $(this).parents(".row").first().remove();
            if($("#answer_view_2_3").find("#answer_add").length<=0) {
                $("#answer_view_2_3").find(".btnView:last").append('<button id="answer_add" class="btn btn-default ml5" type="button"><i class="icon-plus-circle2"></i></button>');
            }
            if($("#answer_view_2_3").find(".btnView").length==1) {
                $("#answer_view_2_3").find(".btnView").find("#answer_remove").remove();
            }
            $("#answer_view_2_3").find(".trueAnswer").each(function(index){
                $(this).val(index);
            });
        });
    })
</script>
@endpush
