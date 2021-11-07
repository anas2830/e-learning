@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('teacher.stdRequestClass')}}">Class Request</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <!-- Data Table -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Class Request List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
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
                    <th width="10%">Batch</th>
                    <th width="15%">Class</th>
                    <th width="22%">Student Email</th>
                    <th width="30%">Request Reason</th>
                    <th width="10%">Status</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($class_requests))
                    @foreach ($class_requests as $key => $request)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{ $request->batch_no }}</td>
                        <td>{{ $request->class_name }}</td>
                        <td>{{ $request->email }}</td>
                        <td>{!! strip_tags(Str::words($request->request_reasons, 50, '.....')) !!}</td>
                        <td>
                            @if($request->approve_status == 1)
                                <span class="label label-success">Approved</span>
                            @else 
                                <span class="label label-danger">Pending</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{route('teacher.stdRequestClassFeeback', ['class_request_id'=>$request->id])}}" class="btn btn-primary btn-xs">Feedback<i class="icon-circle-right2 position-right"></i></a>
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
                    {'orderable':false, "targets": 4 },
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