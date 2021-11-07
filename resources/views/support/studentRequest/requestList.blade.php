@extends('support.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('support.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('support.stdRequest')}}">Support</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <!-- Data Table -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Request List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight data-list" id="courseTable">
            <thead>
                <tr>
                    <th width="2%">SL.</th>
                    <th width="10%">Category</th>
                    <th width="13%">RequestTime</th>              
                    <th width="15%">StudentInfo</th>              
                    <th width="20%">Title</th>
                    <th width="10%">Start Time</th>
                    <th width="10%">Join</th>
                    <th width="10%">Status</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($all_requests))
                    @foreach ($all_requests as $key => $request)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{@Helper::supportCategoryName($request->category_id)}}</td>
                        <td>{{ date("h:i A jS F, Y", strtotime($request->created_at)) }}</td>
                        <td>
                            {{ $request->student_name }}<br>
                            {{ $request->phone }}<br>
                            <a href="{{$request->fb_profile}}" target="_blank" class="btn btn-info btn-xs">FB</a>

                        </td>
                        <td>
                            {{ $request->request_title }}<br>
                            <button type="button" class="btn btn-info btn-xs open-modal" modal-title="Request Details" modal-type="show" modal-size="medium" modal-class="" selector="RequestDetails" modal-link="{{route('support.requestDetails', ['req_id'=>$request->id])}}"> Details </button>
                        </td>
                        @if(!empty($request->liveSchedule))
                            <td>
                                {{ date("jS F, Y", strtotime($request->liveSchedule->start_date)) }}
                                {{Helper::timeGia($request->liveSchedule->start_time)}}
                            </td>
                            <td>
                                @if($request->liveSchedule->created_by == $authId)
                                    <a href="{{$request->liveSchedule->start_url}}" target="_blank" class="btn btn-primary btn-xs">Start</a>
                                @endif
                            </td>
                        @else 
                            <td></td>
                            <td></td>
                        @endif
                        <td>
                            @if($request->approve_status == 1)
                                <span class="label label-success">Approved</span>
                            @else 
                                <span class="label label-danger">Pending</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- @if(!empty($request->liveSchedule->created_by == $authId)) --}}
                            @if(!empty($request->liveSchedule))
                                @if($request->liveSchedule->created_by == $authId)
                                    <a href="{{route('support.stdRequestSchedule', ['std_req_id'=>$request->id])}}" class="btn btn-primary btn-xs">Schedule<i class="icon-circle-right2 position-right"></i></a>
                                @else 
                                <span class="label label-danger">Supported By {{Helper::supportManagerInfo($request->liveSchedule->created_by)->name}}</span>
                                @endif
                            @else 
                                <a href="{{route('support.stdRequestSchedule', ['std_req_id'=>$request->id])}}" class="btn btn-primary btn-xs">Schedule<i class="icon-circle-right2 position-right"></i></a>
                            @endif
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="10">No Data Found!</td>
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
                    {'orderable':false, "targets": 6 },
                    {'orderable':false, "targets": 8 },
                ]
        });

        $(document).ready(function(){
            @if (session('msgType'))
                setTimeout(function() {$('#msgDiv').hide()}, 6000);
            @endif
        });
    </script>
@endpush