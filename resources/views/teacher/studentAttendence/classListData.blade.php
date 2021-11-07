@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.batchstuAttendence')}}">Assign Batch Class</a></li>
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
            <h5 class="panel-title">{{$course_name}}({{$batch_no}}) Batch Class List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('teacher.batchstuAttendence')}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
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

        <table class="table table-bordered table-hover datatable-highlight data-list" id="courseTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="25%">Class Name</th>
                    <th width="30%">Start Date</th>
                    <th width="20%">Time</th>
                    <th width="10%" class="text-center">Attendence</th>
                    <th width="10%" class="text-center">Assignment</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($assign_classes))
                    @foreach ($assign_classes as $key => $class)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$class->class_name}}</td>
                        <td>
                            @if (!empty($class->start_date))
                                {{ date("jS F, Y", strtotime($class->start_date)) }}
                            @endif
                        </td>
                        <td>
                            @if (!empty($class->start_time))
                                {{ Helper::timeGia($class->start_time) }}
                            @endif
                        </td>
                        <td class="text-center">
                            @if (!empty($class->start_date))
                                @if ($class->isAttendanceDone == 0)
                                    <a href="{{route('teacher.batchstuGiveAttendence', ['batch_class_id'=>$class->id])}}" class="btn btn-primary">Take Attendence<i class="icon-checkbox-checked position-right"></i></a>
                                @else 
                                    <button type="button" class="btn btn-success btn-sm open-modal" modal-title="Class Attendence List" modal-type="show" modal-size="large" modal-class="" selector="showAttendence" modal-link="{{route('teacher.showAttendence', ['batch_class_id'=>$class->id] )}}">
                                        Show Attendence
                                   </button>
                                @endif
                            @else 
                                <span class="label label-warning">Complete Running Class</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if (!empty($class->start_date))
                                {{-- @if ($class->complete_status == 1)
                                    <span class="label label-success">Completed</span>
                                @else --}}
                                    <a href="{{route('teacher.batchstuAssignments.index', ['batch_class_id'=>$class->id])}}" class="btn btn-info">Assignments</a>
                                {{-- @endif --}}
                            @else 
                                <span class="label label-warning">Complete Running Class</span>
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
    // $('#courseTable').DataTable();

    @if (session('msgType'))
        setTimeout(function() {$('#msgDiv').hide()}, 3000);
    @endif
    
    $('#courseTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 4 },
                {'orderable':false, "targets": 5 }
            ]
    });
</script>
@endpush
