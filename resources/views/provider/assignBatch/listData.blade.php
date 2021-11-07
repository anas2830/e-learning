@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.batch.index')}}">Assign Batch</a></li>
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
            <h5 class="panel-title">Assign Batch List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.batch.create')}}" class="btn btn-primary add-new">Add New</a></li>
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
                    <th width="3%">SL.</th>
                    <th width="5%">Batch No</th>
                    <th width="20%">Course Name</th>
                    <th width="19%">Start Date</th>
                    <th width="10%" class="text-center">Schedule</th>
                    <th width="10%" class="text-center">Assign Teacher</th>
                    <th width="8%" class="text-center">Assign Captain</th>
                    <th width="8%" class="text-center">Assign Instructor</th>
                    <th width="8%" class="text-center">Class</th>
                    <th width="6%" class="text-center">Complete Status</th>
                    <th width="8%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($assign_batches))
                    @foreach ($assign_batches as $key => $assign_batch)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$assign_batch->batch_no}}</td>
                        <td>{{$assign_batch->course_name}}</td>
                        <td>{{ date("jS F, Y", strtotime($assign_batch->start_date)) }}</td>
                        <td class="text-center">
                            @if($assign_batch->complete_status == 1)
                                <button type="button" class="btn btn-info disabled">Schedule</button>
                            @else 
                                <button type="button" class="btn btn-info btn-xs open-modal" modal-title="Update Schedule" modal-type="update" modal-size="medium" modal-class="" selector="Schedule" modal-link="{{route('provider.updateSchedule', [$assign_batch->id])}}"> Schedule </button>
                            @endif

                        </td>
                        <td class="text-center">
                            @if($assign_batch->complete_status == 1)
                                <button type="button" class="btn btn-info disabled">Assign Teacher</button>
                            @else 
                                @if ($assign_batch->is_schedule > 0)
                                    <button type="button" class="btn btn-info btn-xs open-modal" modal-title="Assign Teacher" modal-type="update" modal-size="medium" modal-class="" selector="Assign" modal-link="{{route('provider.assignTeacher', [$assign_batch->id])}}"> Assign Teacher </button>
                                @else 
                                    <span class="label label-warning">Uncomplete Schedule</span>
                                @endif
                            @endif
                        </td>
                        <td class="text-center">
                            @if($assign_batch->complete_status == 1)
                                <button type="button" class="btn btn-info disabled">Assign Captain</button>
                            @else 
                                @if ($assign_batch->is_schedule > 0)
                                    <button type="button" class="btn btn-primary btn-xs open-modal" modal-title="Assign Captain" modal-type="update" modal-size="medium" modal-class="" selector="Assign" modal-link="{{route('provider.assignCaptain', [$assign_batch->id])}}"> Assign Captain </button>
                                @else 
                                    <span class="label label-warning">Uncomplete Schedule</span>
                                @endif
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-xs open-modal" modal-title="Assign Instructor" modal-type="update" modal-size="medium" modal-class="" selector="Assign" modal-link="{{route('provider.assignInstructor', [$assign_batch->id])}}">Assign Instructor</button>
                        </td>
                        <td class="text-center">
                            <a href="{{route('provider.batchAddClass.index', ['batch_id'=>$assign_batch->id])}}" class="btn btn-primary btn-xs">Class List<i class="icon-circle-right2 position-right"></i></a>
                        </td>
                        <td class="text-center">
                            @if($assign_batch->class_complete == 0)
                                @if ($assign_batch->complete_status == 1)
                                    <span class="label label-success">Completed</span>
                                @else 
                                    <button type="button" class="btn btn-warning btn-xs open-modal" modal-title="Batch Complete" modal-type="update" modal-size="medium" modal-class="" selector="Batch" modal-link="{{route('provider.batchComplete', [$assign_batch->id])}}"> Complete Action </button>
                                @endif
                            @else 
                                <span class="label label-danger">Running</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- @if($assign_batch->done_activity == 0) --}}
                            @if ($assign_batch->complete_status == 1)
                                <a href="#" class="action-icon"><i class="icon-pencil7"></i></a>
                            @else
                                <a href="{{route('provider.batch.edit', [$assign_batch->id])}}" class="action-icon"><i class="icon-pencil7"></i></a>
                            @endif
                            {{-- @endif --}}
                            <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('provider.batch.destroy', [$assign_batch->id])}}">@csrf </i></a>
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
    <!-- /highlighting rows and columns -->

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
                {'orderable':false, "targets": 4 },
                {'orderable':false, "targets": 5 },
                {'orderable':false, "targets": 6 },
                {'orderable':false, "targets": 7 },
                {'orderable':false, "targets": 9 },
            ]
    });
</script>
@endpush
