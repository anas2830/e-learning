@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.courseAssignmentArchive.index', ['class_id'=>$class_info->id] )}}">Assignments</a></li>
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
            <h5 class="panel-title">[{{$class_info->class_name}}] Assignment Archive List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.courseAddClass.index', ['course_id'=>$class_info->course_id])}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
                    <li style="margin-right: 10px;"><a href="{{route('provider.courseAssignmentArchive.create', ['class_id'=>$class_info->id])}}" class="btn btn-primary add-new">Add New</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        {{-- <div class="panel-body" style="text-align: right">
            <a href="#" class="btn btn-primary">Add New</a>
        </div> --}}
        <table class="table table-bordered table-hover datatable-highlight data-list" id="assignmentArchiveTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="35%">Title</th>
                    <th width="50%">Overview</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($assignments))
                    @foreach ($assignments as $key => $assignment)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$assignment->title}}</td>
                        <td>{!! strip_tags(Str::words($assignment->overview, 20, '.....')) !!}</td>
                        <td class="text-center">
                            @if ($assignment->isUsed == false)
                                <a href="{{route('provider.courseAssignmentArchive.edit', [$assignment->id])}}" class="action-icon"><i class="icon-pencil7"></i></a>
                                <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('provider.courseAssignmentArchive.destroy', [$assignment->id])}}">@csrf </i></a>
                            @else 
                                <span class="label label-danger">Already Used</span>
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
    <!-- /highlighting rows and columns -->

    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">DevsSquad IT Solutions</a>
    </div>
    <!-- /footer -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    // $('#courseTable').DataTable();
    
    $('#assignmentArchiveTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 2 },
                {'orderable':false, "targets": 3 },
            ]
    });
</script>
@endpush
