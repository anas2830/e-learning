@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.assignedBatchClass.index')}}">Assigned Class</a></li>
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
            <h5 class="panel-title">({{$course_name}} [{{$batcn_info->batch_no}}]) Class List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('teacher.assignedBatch')}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
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
                    <th width="5%">SL.</th>
                    <th width="20%">Class Name</th>
                    <th width="15%">Start Date</th>
                    <th width="15%">Start Time</th>
                    <th width="15%">End Date</th>
                    <th width="20%">Live Schedule</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($assigned_classes))
                    @foreach ($assigned_classes as $key => $class)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$class->class_name}}</td>
                        <td>@if($class->start_date) {{ date("jS F, Y", strtotime($class->start_date)) }} @endif</td>
                        <td>@if($class->start_time){{ Helper::timeGia($class->start_time) }}@endif</td>
                        <td>@if($class->end_date){{ date("jS F, Y", strtotime($class->end_date)) }}@endif</td>
                        <td>
                            @if($class->start_date &&  ($class->complete_status == 2))
                                <button type="button" class="btn btn-warning btn-xs open-modal" modal-title="Live Schedule" modal-type="update" modal-size="medium" modal-class="" selector="Schedule" modal-link="{{route('teacher.assignedBatchClass.show', [$class->id])}}"> Live Schedule </button>
                            @elseif($class->complete_status == 3)
                                <span class="label label-info">Upcomming</span>
                            @else 
                                <span class="label label-success">Completed</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($class->start_date &&  ($class->complete_status == 2))
                                <button type="button" class="btn btn-danger btn-sm open-modal" modal-title="Class Status Update" modal-type="update" modal-size="medium" modal-class="" selector="classStatus" modal-link="{{route('teacher.classStatus', [$class->id, $batch_id])}}">
                                    Running
                                </button>
                            @elseif($class->start_date &&  ($class->complete_status == 1))
                                <span class="label label-success">Completed</span>
                                <button type="button" class="btn btn-info mt-5 btn-sm open-modal" modal-title="YouTube Video ID Update" modal-type="update" modal-size="medium" modal-class="" selector="driveLink" modal-link="{{route('teacher.classDriveLink', [$class->id, $batch_id])}}">
                                    Class Videos
                                </button>
                            @else
                                <span class="label label-info">Upcomming</span>
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
    // $('#assignedBatchTable').DataTable();
    $('#assignedBatchTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 5 },
                {'orderable':false, "targets": 6 },
            ]
    });

    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif
    });
    
</script>
@endpush
