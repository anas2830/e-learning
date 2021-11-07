@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
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
            <h5 class="panel-title">Course List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight data-list" id="courseTable">
            <thead>
                <tr>
                    <th width="3%">SL.</th>
                    <th width="5%">Batch No</th>
                    <th width="10%">Course Name</th>
                    <th width="15%">Start Date</th>
                    <th width="20%" class="text-center">Schedule</th>
                    <th width="10%" class="text-center">Assign Teacher</th>
                    <th width="10%" class="text-center">Class</th>
                    <th width="10%" class="text-center">Complete Status</th>
                    <th width="10%" class="text-center">Activity</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($all_batches))
                    @foreach ($all_batches as $key => $batch)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$batch->batch_no}}</td>
                        <td>{{$batch->course_name}}</td>
                        <td>{{ date("jS F, Y", strtotime($batch->start_date))}}</td>
                        <td class="text-center">
                            <?php $count = count($batch->schedules); ?>
                            @foreach($batch->schedules as $key => $schedule)
                                 {{ Helper::dayName($schedule->day_dt) }} @if($count != $key+1) , @endif
                            @endforeach
                        </td>
                        <td>
                            @if($batch->teacher_id != null)
                                {{ Helper::getTeacherName($batch->teacher_id)}}
                            @endif
                        </td>
                        <td>{{$batch->total_class}}</td>
                        <td>
                            @if($batch->complete_status == 1)
                                <span class="label label-success">Completed</span>
                            @else 
                                <span class="label label-primary">Running</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{route('provider.analysisBatchStudents', ['batch_id'=>$batch->id])}}" class="btn btn-primary">See activity <i class="icon-circle-right2 position-right"></i></a>
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
    // $('#courseTable').DataTable();
    
    $('#courseTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 5 }
            ]
    });
</script>
@endpush
