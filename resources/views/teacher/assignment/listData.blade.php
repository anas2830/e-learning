@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.batchstuAssignments.index' , ['batch_class_id'=>$batch_class_id] )}}">Assignments</a></li>
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
            <h5 class="panel-title">Assignment List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('teacher.batchstuClassList', [$batch_class_info->batch_id])}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
                    <li style="margin-right: 10px;"><a href="{{route('teacher.batchstuAssignments.create', ['batch_class_id'=>$batch_class_id])}}" class="btn btn-primary add-new">Add New</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>
        <div id="msgDiv" class="alert alert-styled-left alert-arrow-left alert-bordered" style="display: none">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
            <span class="text-semibold message"></span>
        </div>
        <table class="table table-bordered table-hover datatable-highlight data-list" id="userTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="35%">Title</th>
                    <th width="15%">Post Date</th>
                    <th width="15%">Due Date</th>
                    <th width="20%" class="text-center">StudentList</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($assignments))
                    @foreach ($assignments as $key => $assignment)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$assignment->title}}</td>
                        <td>{{$assignment->start_date}}</td>
                        <td>{{$assignment->due_date}} {{$assignment->due_time}}</td>
                        <td class="text-center">
                            <a href="{{route('teacher.batchstuStudentList', ['assignment_id'=>$assignment->id,'type'=>1])}}" class="btn btn-success btn-xs">Done List</a>
                            <a href="{{route('teacher.batchstuStudentList', ['assignment_id'=>$assignment->id,'type'=>2])}}" class="btn btn-danger btn-xs mt-5">Not Done List</a>
                        </td>
                        <td class="text-center">
                            <a href="{{route('teacher.batchstuAssignments.edit', [$assignment->id])}}" class="action-icon"><i class="icon-pencil7"></i></a>
                            <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('teacher.batchstuAssignments.destroy', [$assignment->id])}}">@csrf </i></a>
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
    @if (empty($is_thrown))
        <ul class="icons-list" style="margin-top: 0px">
            <li class="pull-right">
                <button id="throw_assignment" data-id={{$batch_class_id}} class="btn btn-warning add-new">Throw Assignment</button>
            </li>
        </ul>
    @endif
    <!-- /highlighting rows and columns -->

    <!-- Footer -->
    <div class="footer text-muted">
        &copy; 2015.{{date('Y')}} <a href="#">Limitless Web App Kit</a> by <a href="#" target="_blank">Anas</a>
    </div>
    <!-- /footer -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    // $('#courseTable').DataTable();
    
    $('#userTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 5 }
            ]
    });
    
    $('#throw_assignment').on('click', function(e){
        e.preventDefault;
        var assign_batch_id = $(this).attr('data-id');
        $.ajax
        ({ 
            url : "{{route('teacher.assignmentThrow')}}",
            data: {"assign_batch_id":assign_batch_id},
            method: 'GET',
            success: function(response)
            {   
                $("#msgDiv").show();
                $('#msgDiv').addClass(response.status);
                $('.message').text(response.msg);
                
                setTimeout(function()
                    { 
                        $("#msgDiv").hide();
                    }, 
                3000);

            }
        });
    });
</script>
@endpush
