@extends('support.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('support.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Assignment Taking Form</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- Form validation -->
    <div class="panel panel-flat">
        <div class="panel-heading" style="border-bottom: 1px solid #ddd; margin-bottom: 20px;">
            <h5 class="panel-title">Assignment Taking</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" id="assignmentTakingForm" action="{{route('support.takeStdAssignmentAction')}}" method="POST">
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
                        <label class="control-label col-lg-3">Batch Filter Type<span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select class="select-search" name="batch_filter_type" id="batch_filter_type">
                                <option value="0">Select Type</option>
                                <option value="1">With Batch</option>
                                <option value="2">Without Batch (Total {{$total_qty}})</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="conditional_batch_div" style="display: none;">
                        <label class="control-label col-lg-3">Select Batch<span class="text-danger">*</span></label>
                        <div class="col-lg-9" id="batch_selection_div">
                            <select class="select-search" name="batch_id" id="batch_id">
                                <option value="">Select Batch</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-3">Student Selection Type<span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select class="select-search" name="student_selection_type" id="student_selection_type">
                                <option value="">Select Type</option>
                                <option value="1">All Students</option>
                                <option value="2">Selected Students</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="conditional_student_div" style="display: none;">
                        <label class="control-label col-lg-3">Select students Assignment <span class="text-danger">*</span></label>
                        <div class="col-lg-9" id="student_selection_div">
                            <select data-placeholder="Select Students..." multiple="multiple" class="select-search" name="std_assignment_submission_ids[]" id="std_assignment_submission_ids">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="all_student_qty" style="display: none;">
                        <label class="control-label col-lg-3">Quantity</label>
                        <div class="col-lg-9">
                            <input type="text" name="quantity"  maxlength="3" class="form-control" placeholder="Quantity" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                    </div>
                    
                    <!-- /basic textarea -->
                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Take These Assignment <i class="icon-arrow-right14 position-right"></i></button>
                    <a href="{{route('support.checkAssignmentList')}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
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
        
        $("#assignmentTakingForm").on('change', '#batch_filter_type', function(){
            let batch_filter_type = $(this).val();
            $('#all_student_qty').hide();
            if(batch_filter_type == 1){ //With Batch
                $('#conditional_batch_div').show();
                $('#conditional_student_div').hide();
                // Reset Student Selection Type
                $('#student_selection_type').val('');
                $("#student_selection_type").select2({placeholder:"Select Student"});
                $.ajax({
                    url: "{{route('support.getRunningBatch')}}",
                    type: "GET",
                    success: function (data) {
                        $("#batch_selection_div").html(data);
                        $("#batch_id").select2({placeholder:"Select Batch"});
                    }
                });
            } else if(batch_filter_type == 2) { //Without Batch
                $('#conditional_batch_div').hide();
                $('#conditional_student_div').hide();
                // Reset Student Selection Type
                $('#student_selection_type').val('');
                $("#student_selection_type").select2({placeholder:"Select Student"});
            } else { 
                $('#conditional_batch_div').hide();
                $('#conditional_student_div').hide();
                alert('Firstly Select Batch Filter Type');
            }
        });
        
        $("#assignmentTakingForm").on('change', '#student_selection_type', function(){
            let student_selection_type = $(this).val();
            if(student_selection_type == 1){ //All Students (no need to show student list)
                $('#conditional_student_div').hide();
                $('#all_student_qty').show();
            } else if(student_selection_type == 2) { //Selected Students (need to show student list)
                $('#all_student_qty').hide();
                let batch_filter_type = $('#batch_filter_type').val();
                if (batch_filter_type == 1) { //With Batch
                    let batch_id = $('#batch_id').val();
                    if (batch_id) {
                        $('#conditional_student_div').show();
                        $.ajax({
                            url: "{{route('support.getAvailableAssignments')}}",
                            type: "GET",
                            data: {batch_id:batch_id},
                            success: function (data) {
                                $("#student_selection_div").html(data);
                                $("#std_assignment_submission_ids").select2({placeholder:"Select Student"});
                            }
                        });
                    } else {
                        $('#conditional_student_div').hide();
                        alert('Firstly Select Batch');
                    }
                } else if((batch_filter_type == 2)) { //Without Batch
                    $('#conditional_student_div').show();
                    $.ajax({
                        url: "{{route('support.getAvailableAssignments')}}",
                        type: "GET",
                        data: {batch_id:0},
                        success: function (data) {
                            $("#student_selection_div").html(data);
                            $("#std_assignment_submission_ids").select2({placeholder:"Select Student"});
                        }
                    });
                }
            } else {
                $('#all_student_qty').hide();
                $('#conditional_student_div').hide();
                alert('Firstly Select Batch Filter');
            }
        });

        $("#assignmentTakingForm").on('change', '#batch_id', function(){
            let batch_id = $(this).val();

            if(batch_id){
                $('#conditional_student_div').hide();
                // Reset Student Selection Type
                $('#student_selection_type').val('');
                $("#student_selection_type").select2({placeholder:"Select Student"});
            }
        });

    });
</script>
@endpush
