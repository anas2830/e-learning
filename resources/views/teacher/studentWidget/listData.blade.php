@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.widget.index')}}">Widget</a></li>
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
            <h5 class="panel-title">Widget List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('teacher.widget.create')}}" class="btn btn-primary add-new">Add New</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        {{-- <div class="panel-body" style="text-align: right">
            <a href="#" class="btn btn-primary">Add New</a>
        </div> --}}
        <table class="table table-bordered table-hover datatable-highlight data-list" id="widgetTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="20%">Title</th>
                    <th width="30%">Overview</th>
                    <th width="10%">Type</th>
                    <th width="20%">Student Name</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (count($all_widgets) > 0)
                    @foreach ($all_widgets as $key => $widget)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$widget->title}}</td>
                        <td>{!! $widget->overview !!}</td>
                        <td>@if($widget->type == 1) Course @elseif($widget->type == 2) Batch @else Student @endif</td>
                        <td>@if(!empty($widget->student_id)) {{ @Helper::studentInfo($widget->student_id)->name}} @endif</td>
                        <td class="text-center">
                            <a href="{{route('teacher.widget.edit', [$widget->id])}}" class="action-icon"><i class="icon-pencil7"></i></a>
                            <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('teacher.widget.destroy', [$widget->id])}}">@csrf </i></a>
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
</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">


    $('#widgetTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 5 }
            ]
    });

</script>
@endpush
