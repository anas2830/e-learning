@extends('layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('takeSupport.index')}}">Support</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <div class="row">
        <div class="panel panel-body">
            <div class="media">
                <div class="media-left">
                    <a href="#"><i class="icon-phone text-danger-400 icon-2x no-edge-top mt-5"></i></a>
                </div>

                <div class="media-body">
                    <h2 class="media-heading text-semibold">Hand Holding Mentor Support Number: <strong><a href="tel:+8809613828206">+8809613828206</a></strong><span> (Free Call Via Brilliant App)</span></h2>
                    <h4>Support will open (8am - 10pm), in this type contact for any type of problems, only for Hand Holding Support Students.</h4>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Table -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Request List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('takeSupport.create')}}" class="btn btn-primary add-new">Take Support</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight data-list" id="courseTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="10%">Category</th>
                    <th width="25%">Support About</th>
                    <th width="20%">Start Time</th>
                    <th width="15%">Join</th>
                    <th width="10%" class="text-center">Status</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($all_requests))
                    @foreach ($all_requests as $key => $request)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$request->category_name}}</td>
                        <td>{!! Str::words($request->request_title, 20, '.....') !!}</td>
                        @if(!empty($request->liveSchedule))
                            <td>
                                {{ date("jS F, Y", strtotime($request->liveSchedule->start_date)) }}
                                {{Helper::timeGia($request->liveSchedule->start_time)}}
                            </td>
                            <td>
                                <a href="{{$request->liveSchedule->join_url}}" target="_blank" class="btn btn-primary btn-xs">Join To The Support</a>
                            </td>
                        @else 
                            <td></td>
                            <td></td>
                        @endif
                        <td class="text-center">
                            @if($request->approve_status == 1)
                                <span class="label label-success">Approved</span>
                            @else 
                                <span class="label label-warning">Pending</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($request->approve_status == 1)
                                <span class="label label-success">Request Approved</span>
                            @else 
                                <a href="{{route('takeSupport.edit', [$request->id])}}" class="action-icon"><i class="icon-pencil7"></i></a>
                                <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('takeSupport.destroy', [$request->id])}}">@csrf </i></a>
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
        $('#courseTable').DataTable({
            dom: 'lBfrtip',
                "iDisplayLength": 10,
                "lengthMenu": [ 10, 25,30, 50 ],
                columnDefs: [
                    {'orderable':false, "targets": 3 },
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