@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.batchstuClassList', [$assign_class_id])}}">Batch Student List</a></li>
            <li class="active">Create</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="panel panel-flat">
        <div class="panel-heading" style="border-bottom: 1px solid #ddd; margin-bottom: 20px;">
            <h5 class="panel-title">{{$course_name}}({{$assignBatchClassInfo->batch_no}}) {{$assignBatchClassInfo->class_name}} Class Attendence</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li style="margin-right: 10px;"><a href="{{route('teacher.classExamBatchClassList', [$assignBatchClassInfo->batch_id] )}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        @if (session('msgType'))
            @if(session('msgType') == 'danger')
                <div id="msgDiv" class="alert alert-danger alert-styled-left alert-arrow-left alert-bordered">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                    <span class="text-semibold">{{ session('msgType') }}!</span> {{ session('messege') }}
                </div>
            @else
            <div id="msgDiv" class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">{{ session('msgType') }}!</span> {{ session('messege') }}
            </div>
            @endif
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-styled-left alert-bordered">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">Opps!</span> {{ $error }}.
            </div>
            @endforeach
        @endif

        <table class="table table-bordered table-hover datatable-highlight data-list" id="batchTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="20%">Student Name</th>
                    <th width="20%">Exam Date</th>
                    <th width="20%" class="text-center">Result</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($courseBatchStu))
                    @foreach ($courseBatchStu as $key => $student)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{ @Helper::studentInfo($student->student_id)->name }}</td>
                        <td>
                            @if(!empty($student->done_exam))
                                {{ date("jS F, Y", strtotime($student->done_exam->created_at)) }}
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!empty($student->done_exam))
                                <button type="button" class="btn btn-success btn-sm open-modal" modal-title="Exam Result" modal-type="show" modal-size="large" modal-class="" selector="Schedule" modal-link="{{route('teacher.studentResult', ['batch_class_id' => $assign_class_id , 'std_id' => $student->student_id ])}}"> Done <i class="icon-play3 position-right"></i></button>
                            @else 
                                <span class="label label-danger">Not Done</span>
                            @endif
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No Data Found!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">Anas</a>
    </div>
    <!-- /footer -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    // $('#batchTable').DataTable();

    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif
    });
    
    $('#batchTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 3 }
            ]
    });
</script>
@endpush
