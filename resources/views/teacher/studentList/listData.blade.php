@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.batchstuStudentList', ['assignment_id'=>$assignment_info->id])}}">Student List</a></li>
            <li class="active">List Data</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">[{{$assignment_info->title}}] Student List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('teacher.batchstuAssignments.index', ['batch_class_id'=>$assignment_info->assign_batch_class_id])}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
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
        @if (session('danger'))
            
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-styled-left alert-bordered">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">Opps!</span> {{ $error }}.
            </div>
            @endforeach
        @endif

        <table class="table table-bordered table-hover datatable-highlight data-list" id="assignedBatchTable">
            <thead>
                <tr>
                    <th width="3%">SL.</th>
                    <th width="10%">Student Name</th>
                    <th width="10%">Email</th>
                    <th width="9%">Phone</th>
                    <th width="8%">Submit Status</th>
                    @if($type == 1)
                        <th width="20%">Submission DateTime</th>
                        <th width="20%">Attachment</th>
                        <th width="10%">View Details</th>
                        <th width="10%" class="text-center">Marking</th>
                    @endif
                   
                </tr>
            </thead>
            <tbody>
                @if (!empty($batch_students))
                    @foreach ($batch_students as $key => $student)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$student->name}}</td>
                        <td>{{$student->email}}</td>
                        <td>{{$student->phone}}</td>
                        <td>
                            @if (empty($student->submission_date))
                                <span class="label label-danger">Not Done</span>
                            @elseif(!empty($student->late_submit) || $student->late_submit == 0)
                                <span class="label label-success">Done</span>
                            @else 
                                <span class="label label-warning">Late Done</span>
                            @endif
                        </td>
                        @if($type == 1)
                            <td>
                                @if(!empty($student->submission_date))
                                    {{ date("jS F, Y", strtotime($student->submission_date)) }} {{$student->submission_time}}
                                @endif
                            </td>
                            <td>
                                @if($student->attachment != null )
                                    <a href="#" onClick="javascript:window.open('{{url('uploads/assignment/studentAttachment/'.$student->attachment->file_name)}}')" title="Click to Download">
                                        <img src="{{ asset(Helper::getFileThumb($student->attachment->extention)) }}" alt="" height="30" width="35"> [ {{Helper::fileSizeConvert($student->attachment->size)}} ]
                                    </a>
                                @elseif (empty($student->submission_date))
                                    {{''}}
                                @else 
                                    <span style="color: red;">{{'No Attachement'}}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info btn-sm open-modal" modal-title="View Submission Overview" modal-type="show" modal-size="medium" modal-class="" selector="Overview" modal-link="{{route('teacher.viewSubmissionDetails', ['submission_id'=> $student->submission_id])}}">View Details</button>
                            </td>
                            <td class="text-center">
                                @if(!empty($student->submission_date) && empty($student->submit_assignment))
                                    <a href="{{route('teacher.batchstuStudentGiveMark', ['submission_id'=>$student->submission_id])}}" class="btn btn-info">Marking</a>
                                @else
                                    <span class="label label-success">Done</span>
                                @endif
                            </td>
                        @endif
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
});
    // $('#assignedBatchTable').DataTable();

    var type = {{$type}};

    if(type == 1){
        $('#assignedBatchTable').DataTable({
            dom: 'lBfrtip',
                "iDisplayLength": 10,
                "lengthMenu": [ 10, 25,30, 50 ],
                columnDefs: [
                    {'orderable':false, "targets": 5 },
                    {'orderable':false, "targets": 6 },
                    {'orderable':false, "targets": 7 },
                    {'orderable':false, "targets": 8 },
                ]
        });
    }else{
        $('#assignedBatchTable').DataTable({
            dom: 'lBfrtip',
                "iDisplayLength": 10,
                "lengthMenu": [ 10, 25,30, 50 ],
                columnDefs: [
                    {'orderable':false, "targets": 4 },
                ]
        });
    }
    
</script>
@endpush
