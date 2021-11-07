@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.improveAssignment')}}">Improvement</a></li>
            <li class="active">Student List Data</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Student List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
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

        <table class="table table-bordered table-hover datatable-highlight data-list" id="improveStudentTable">
            <thead>
                <tr>
                    <th width="3%">SL.</th>
                    <th width="15%">Std Name</th>
                    <th width="10%">Phone</th>
                    <th width="20%">Assignment</th>
                    <th width="10%">Batch</th>
                    <th width="5%">Class</th>
                    <th width="10%">Submit Date</th>
                    <th width="7%">Attachement</th>
                    <th width="10%">View Details</th>
                    <th width="10%" class="text-center">Marking</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($improveStudents))
                    @foreach ($improveStudents as $key => $studentData)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$studentData->name}}</td>
                        <td>{{$studentData->phone}}</td>
                        <td>
                            <a href="#" class="letter-icon-title open-modal" modal-title="Class Assignment" modal-type="show" modal-size="large" modal-class="" selector="viewAssignment" modal-link="{{route('teacher.classAssignment', [$studentData->assignment_id])}}">{{$studentData->title}}</a>
                        </td>
                        <td>{{$studentData->batch_no}}</td>
                        <td>{{$studentData->class_name}}</td>
                        <td>
                            @if(!empty($studentData->submission_date))
                                {{ date("jS F, Y", strtotime($studentData->submission_date)) }}
                            @endif
                        </td>
                        <td>
                            @if(!empty($studentData->file_name))
                                <a href="#" onClick="javascript:window.open('{{url('uploads/assignment/studentAttachment/'.$studentData->file_name)}}')" title="Click to Download">
                                    <img src="{{ asset(Helper::getFileThumb($studentData->extention)) }}" alt="" height="30" width="35"> [ {{Helper::fileSizeConvert($studentData->size)}} ]
                                </a>
                            @else 
                                <span style="color: red;">{{'No'}}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm open-modal" modal-title="View Submission Overview" modal-type="show" modal-size="medium" modal-class="" selector="Overview" modal-link="{{route('teacher.viewSubmissionDetails', ['submission_id'=> $studentData->id])}}">View Details</button>
                        </td>
                        <td class="text-center">
                            <a href="{{route('teacher.improveAssignmentMark', ['submission_id'=>$studentData->id])}}" class="btn btn-info">Marking</a>
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="10">No Data Found!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

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
        setTimeout(function() {$('#msgDiv').hide()}, 3000);
    @endif
});

    $('#improveStudentTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 7 },
                {'orderable':false, "targets": 8 },
                {'orderable':false, "targets": 9 },
            ]
    });
    
    
</script>
@endpush
