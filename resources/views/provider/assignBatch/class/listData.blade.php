@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.batchAddClass.index')}}">Course Class</a></li>
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
            <h5 class="panel-title">{{$course_info->course_name}} ({{$batch_info->batch_no}}) Class List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.batch.index')}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
                    @if($batch_info->complete_status != 1)
                        <li style="margin-right: 10px;"><a href="{{route('provider.batchAddClass.create', ['batch_id'=>$batch_info->id])}}" class="btn btn-primary add-new">Add New</a></li>
                    @endif
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight data-list" id="courseTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="25%">Class Name</th>
                    <th width="30%">Class Overview</th>
                    <th width="20%">Content</th>
                    <th width="10%">Attendance List</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($class_list))
                    @foreach ($class_list as $key => $class)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$class->class_name}}</td>
                        <td>{!! Str::words($class->class_overview, 15, '.....') !!}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm open-modal" modal-title="View Materials" modal-type="show" modal-size="medium" modal-class="" selector="viewDetails" modal-link="{{route('provider.courseAddClass.show', [$class->id])}}">View Materials <i class="icon-play3 position-right"></i></button>
                        </td>
                        <td class="text-center">
                            @if ($class->is_attendence_done > 0)
                                <a href="{{route('provider.batchShowAttendence', ['batch_class_id'=>$class->assign_batch_id])}}" class="btn btn-primary">Show Attendence<i class="icon-checkbox-checked position-right"></i></a>
                            @else
                                <span class="label label-warning">Not Taken</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($batch_info->complete_status == 1)
                                <a href="#" class="action-icon"><i class="icon-trash"></i></a>
                            @else
                                <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('provider.batchAddClass.destroy', [$class->assign_batch_id] )}}">@csrf </i></a>
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
    // $('#courseTable').DataTable();
    $('#courseTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 3 },
                {'orderable':false, "targets": 4 }
            ]
    });
</script>
@endpush
