@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.assignStudent.index')}}">Assign Students</a></li>
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
            <h5 class="panel-title">Batch - ({{$batch_info->batch_no}}) Students List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.assignBatchList')}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
                    <li style="margin-right: 10px;"><a href="{{route('provider.assignStudent.create', ['batch_id'=>$batch_info->id])}}" class="btn btn-primary add-new">Add New</a></li>
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

        <table class="table table-bordered table-hover datatable-highlight data-list" id="studentTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="30%">Student Name</th>
                    <th width="25%">Email</th>
                    <th width="15%">Phone</th>
                    <th width="10%">Active Status</th>
                    <th width="15%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($assign_students))
                    @foreach ($assign_students as $key => $assign_student)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$assign_student->name}}</td>
                        <td>{{$assign_student->email}}</td>
                        <td>{{$assign_student->phone}}</td>
                        <td class="text-center">
                            @if ($assign_student->active_status == 1)
                                <span class="label label-success">Active</span>
                            @else 
                                <span class="label label-danger">InActive</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <!-- <a href="{{route('provider.assignStudent.edit', [$assign_student->id])}}" class="action-icon"><i class="icon-pencil7"></i></a> -->
                            <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('provider.assignStudent.destroy', [$assign_student->id])}}">@csrf </i></a>
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
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif
    });
    
    $('#studentTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 5 }
            ]
    });
</script>
@endpush
