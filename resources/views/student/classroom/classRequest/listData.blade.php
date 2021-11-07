@extends('layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('requestClass.index')}}">Request Class</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">

    <!-- Data Table -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Requested Class List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('requestClass.create')}}" class="btn btn-primary add-new">Send Request</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight data-list" id="classRequestTable">
            <thead>
                <tr>
                    <th width="3%">SL.</th>
                    <th width="20%">Class Name</th>
                    <th width="20%">Reason</th>
                    <th width="7%" class="text-center">Status</th>
                    <th width="20%">Response</th>
                    <th width="15%">Go To</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($requested_classes))
                    @foreach ($requested_classes as $key => $class_request)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$class_request->class_name}}</td>
                        <td>{!! strip_tags(Str::words($class_request->request_reasons, 50, '.....')) !!}</td>
                        <td class="text-center">
                            @if($class_request->approve_status == 1)
                                <span class="label label-success">Approved</span>
                            @else 
                                <span class="label label-warning">Pending</span>
                            @endif
                        </td>
                        <td>@if($class_request->approve_status == 1) {!! $class_request->response !!} @endif</td>
                        <td>
                            @if($class_request->approve_status == 1)
                                <a href="{!! $class_request->class_link !!}" target="_blank" class="btn btn-primary btn-xs">Watch & Download The Class</a>
                            @endif
                        </td>
                        
                        <td class="text-center">
                            @if($class_request->approve_status == 1)
                                <span class="label label-success">Request Approved</span>
                            @else 
                                <a href="{{route('requestClass.edit', [$class_request->id])}}" class="action-icon"><i class="icon-pencil7"></i></a>
                                <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('requestClass.destroy', [$class_request->id])}}">@csrf </i></a>
                            @endif
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Data Found!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <!-- /Data Table -->

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
        $('#classRequestTable').DataTable({
            dom: 'lBfrtip',
                "iDisplayLength": 10,
                "lengthMenu": [ 10, 25,30, 50 ],
                columnDefs: [
                    {'orderable':false, "targets": 2 },
                    {'orderable':false, "targets": 4 },
                    {'orderable':false, "targets": 5 },
                    {'orderable':false, "targets": 6 },
                ]
        });

        $(document).ready(function(){
            @if (session('msgType'))
                setTimeout(function() {$('#msgDiv').hide()}, 6000);
            @endif
        });
    </script>
@endpush