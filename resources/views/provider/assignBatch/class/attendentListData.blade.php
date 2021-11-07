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
            <h5 class="panel-title">Class Attendence List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.batchAddClass.index', ['batch_id'=>$batch_class_info->batch_id])}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        @if (session('msgType'))
            <div id="msgDiv" class="alert alert-{{session('msgType')}} alert-styled-left alert-arrow-left alert-bordered">
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

        <table class="table table-bordered table-hover datatable-highlight data-list" id="courseTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="25%">Student Name</th>
                    <th width="10%">Phone</th>
                    <th width="10%">Mark</th>
                    <th width="30%">Remark</th>
                    <th width="10%">Update Remark</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($attendenceLists))
                    @foreach ($attendenceLists as $key => $student)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$student->name}}</td>
                        <td>{{ $student->phone }}</td>
                        <td>{{ $student->mark }}</td>
                        <td>{{ $student->remark }}</td>
                        <td class="text-center">
                            @if ($student->is_attend == 0)
                                <button type="button" class="btn btn-info btn-xs open-modal" modal-title="Update Remark" modal-type="update" modal-size="medium" modal-class="" selector="saveAttendenceRemark" modal-link="{{route('provider.batchAttendenceRemark', ['attendence_id'=>$student->id] )}}">
                                    Remark
                            </button>
                            @else
                                <span class="label label-success">Attend</span>
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
