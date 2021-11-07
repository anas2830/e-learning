@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.assignedBatch')}}">Assign Batch</a></li>
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
                    <th width="15%">Course Name</th>
                    <th width="15%">Start Date</th>
                    <th width="12%">Start Time</th>
                    <th width="10%">Schedule(Weekly)</th>
                    <th width="10%">Total Students</th>
                    <th width="12%">Assign Class</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($assign_batches))
                    @foreach ($assign_batches as $key => $assign_batch)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$assign_batch->course_name}} [{{$assign_batch->batch_no}}]</td>
                        <td>{{ date("jS F, Y", strtotime($assign_batch->start_date)) }}</td>
                        <td>
                            @if (!empty($assign_batch->start_time))
                                {{ Helper::timeGia($assign_batch->start_time) }}
                            @endif
                        </td>
                        <td class="text-center">
                            <?php $count = count($assign_batch->schedules); ?>
                            @foreach($assign_batch->schedules as $key => $schedule)
                                 {{ Helper::dayName($schedule->day_dt) }} @if($count != $key+1) , @endif
                            @endforeach
                        </td>
                        <td>{{$assign_batch->total_students}}</td>
                        <td class="text-center">
                            <a href="{{route('teacher.assignedBatchClass.index', ['batch_id'=>$assign_batch->id])}}" class="btn btn-primary">Class <i class="icon-circle-right2 position-right"></i></a>
                        </td>
                        <td class="text-center">
                            @if ($assign_batch->complete_status == 1)
                            <span class="label label-success">Completed</span>
                            @else 
                            <span class="label label-danger">InCompleted</span>
                            @endif
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No Data Found!</td>
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
                {'orderable':false, "targets": 6 }
            ]
    });
</script>
@endpush
