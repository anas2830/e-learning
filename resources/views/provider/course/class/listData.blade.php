@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.courseAddClass.index')}}">Course Class</a></li>
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
            <h5 class="panel-title">{{$course_info->course_name}} Class List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.course.index')}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
                    <li style="margin-right: 10px;"><a href="{{route('provider.courseAddClass.create', ['course_id'=>@$course_info->id])}}" class="btn btn-primary add-new">Add New</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
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
        </div>

        <table class="table table-bordered table-hover datatable-highlight data-list" id="courseTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="25%">Class Name</th>
                    <th width="30%">Class Overview</th>
                    <th width="15%" class="text-center">Content</th>
                    <th width="15%" class="text-center">Archive Assignment</th>
                    <th width="15%" class="text-center">Archive Questions</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($assign_classes))
                    @foreach ($assign_classes as $key => $class)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$class->class_name}}</td>
                        <td>{!! Str::words($class->class_overview, 15, '.....') !!}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm open-modal" modal-title="View Materials" modal-type="show" modal-size="medium" modal-class="" selector="viewDetails" modal-link="{{route('provider.courseAddClass.show', [$class->id])}}">Materials <i class="icon-play3 position-right"></i></button>
                        </td>
                        <td class="text-center">
                            <a href="{{route('provider.courseAssignmentArchive.index', ['class_id'=>$class->id])}}" class="btn btn-primary">Assignment</a>
                        </td>
                        <td class="text-center">
                            <a href="{{route('provider.courseQuestionArchive.index', ['class_id'=>$class->id])}}" class="btn btn-primary">Questions</a>
                        </td>
                        <td class="text-center">
                            <a href="{{route('provider.courseAddClass.edit', [$class->id])}}" class="action-icon"><i class="icon-pencil7"></i></a>
                            <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('provider.courseAddClass.destroy', [$class->id])}}">@csrf </i></a>
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No Data Found!</td>
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
    // $('#courseTable').DataTable();
    $('#courseTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 3 },
                {'orderable':false, "targets": 4 },
                {'orderable':false, "targets": 5 },
                {'orderable':false, "targets": 6 },
            ]
    });
</script>
@endpush
