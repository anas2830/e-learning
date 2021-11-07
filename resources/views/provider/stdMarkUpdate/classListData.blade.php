@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.powerMenuStdList')}}">Power Menu</a></li>
            <li class="active">Batch Class List</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">{{Helper::batchName($assign_batch_student_info->batch_id)}} Class List of <span style="color: red;">{{Helper::studentInfo($assign_batch_student_info->student_id)->name}}</span></h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.powerMenuStdList')}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
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

        <table class="table table-bordered table-hover datatable-highlight data-list" id="batchClassTable">
            <thead>
                <tr>
                    <th width="3%">SL.</th>
                    <th width="30%">Class Name</th>
                    <th width="20%" class="text-center">Class Mark</th>
                    <th width="20%" class="text-center">Assignment</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($batch_completed_classes))
                    @foreach ($batch_completed_classes as $key => $class)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$class->class_name}}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm open-modal" modal-title="Update Class Mark" modal-type="update" modal-size="medium" modal-class="" selector="classMark" modal-link="{{route('provider.stdClassMarkUpdate', ['assign_batch_std_id' => $assign_batch_student_info->id, 'assign_batch_class_id' => $class->id, 'type' => 1])}}">Class Mark <i class="icon-pencil7 position-right"></i></button>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm open-modal" modal-title="Update Assignment Mark" modal-type="update" modal-size="medium" modal-class="" selector="assignmentMark" modal-link="{{route('provider.stdClassMarkUpdate', ['assign_batch_std_id' => $assign_batch_student_info->id, 'assign_batch_class_id' => $class->id, 'type' => 2])}}">Assignment Mark <i class="icon-pencil7 position-right"></i></button>
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
</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    $('#batchClassTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 2 },
                {'orderable':false, "targets": 3 },
            ]
    });


    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif
    });
</script>
@endpush
