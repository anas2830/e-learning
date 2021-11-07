@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.studentReportForm')}}">Student Report</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- Form validation -->
    <div class="panel panel-flat">
        <div class="panel-heading" style="border-bottom: 1px solid #ddd; margin-bottom: 20px;">
            <h5 class="panel-title">Student Report</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" id="studentReportForm" action="{{route('teacher.studentReportOverview')}}" method="POST">
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
                        <label class="control-label col-lg-2">Select Batch<span class="text-danger">*</span></label>
                        <div class="col-lg-10" id="batch_selection_div">
                            <select class="select-search" name="batch_id" id="batch_id">
                                <option value="">Select Batch</option>
                                @foreach ($batches as $batch)
                                <option value="{{$batch->id}}">{{$batch->batch_no}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Select Student<span class="text-danger">*</span></label>
                        <div class="col-lg-10" id="student_selection_div">
                            <select class="select-search" name="student_id" id="student_id">
                                <option value="">Select Student</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Date Range</label>
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="date" class="form-control" name="from_date" id="from_date" required="">
                                    <span class="help-block text-center">Start Date</span>
                                </div>

                                <div class="col-md-6">
                                    <input type="date" class="form-control" name="to_date" id="to_date" required="">
                                    <span class="help-block text-center">End Date</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /basic text input -->
                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Show Report <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </form>
        </div>
    </div>
    <!-- /form validation -->


    <!-- Footer -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    $(document).ready(function () {
        $("#studentReportForm").on('change', '#batch_id', function(){
            let batch_id = $(this).val();
            $.ajax({
                url: "{{route('teacher.getBatchStudents')}}",
                type: "GET",
                data: {batch_id:batch_id},
                success: function (data) {
                    $("#student_selection_div").html(data);
                    $("#student_id").select2({placeholder:"Select Student"});
                }
            });
        });
    })
</script>
@endpush
