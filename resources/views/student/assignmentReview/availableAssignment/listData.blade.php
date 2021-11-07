@extends('layouts.default')
@push('styles')
    <style>
        td img {
            width: 80px;
            height: 80px;
        }
    </style>
@endpush
@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('studentRanking')}}">My Batch Students Ranking</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <!-- Data Table -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Available Assignment List</h5>
            <div class="heading-elements">
                <ul class="icons-list data-list">
                    @if ($eligible_for_take)
                        <li>
                            <button type="button" class="btn btn-primary btn-xs open-modal" modal-title="Get Assignment" modal-type="create" modal-size="medium" modal-class="" selector="Assignment" modal-link="{{route('takenAvailableAssignment', ['course_id'=>$student_course_info->course_id, 'batch_id'=>$student_course_info->batch_id])}}"> Request For Assignment({{$available_submit_assignment}} Available) </button>
                        </li>
                    @else
                        <span class="label label-danger">Not Eligible for take assignment cause Your Score less then 75%</span>
                    @endif
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        @if (session('msgType'))
            <div id="msgDiv" class="alert alert-{{ session('msgType') }} alert-styled-left alert-arrow-left alert-bordered">
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


        <table class="table table-bordered table-hover datatable-highlight data-list" id="availableAssignment">
            <thead>
                <tr>
                    <th width="5%">SL</th>
                    {{-- <th width="15%">Batch Name</th> --}}
                    <th width="30%">Course Name</th>
                    <th width="20%">Taken Date & Time</th>
                    <th width="20%">Expire Date & Time</th>
                    <th width="10%" class="text-center">Status</th>
                    <th width="10%" class="text-center">Check Now</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($taken_assignments))
                    @foreach ($taken_assignments as $key => $take_assignment)
                    <tr>
                        <td>{{++$key}}</td>
                        {{-- <td>{{Helper::batchName($take_assignment->batch_id)}}</td> --}}
                        <td>{{Helper::courseName($take_assignment->course_id)}}</td>
                        <td>{{$take_assignment->taken_date}} {{$take_assignment->taken_time}}</td>
                        <td>{{$take_assignment->expire_date}} {{$take_assignment->expire_time}}</td>
                        <td class="text-center">
                            @if ($take_assignment->review_status == 1)
                                Pending
                            @elseif($take_assignment->review_status == 2)
                                Reviewed
                            @else 
                                Under Revision
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge badge-info" title="Total Discussion SMS">{{$take_assignment->total_discussion}}</span>
                            <a href="{{route('reviewAssignment', ['assignment_subm_taken_id'=> $take_assignment->id])}}" class="btn @if ($take_assignment->review_status == 1) btn-primary @elseif($take_assignment->review_status == 2) btn-success @else btn-danger @endif btn-sm">
                                @if ($take_assignment->review_status == 1) Check Now @elseif($take_assignment->review_status == 2) Reviewed @else Revising @endif 
                                <i class="icon-circle-right2 position-right"></i>
                            </a>
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
        var table = $('#availableAssignment').DataTable({
            dom: 'lBfrtip',
                "iDisplayLength": 10,
                "lengthMenu": [ 10, 25,30, 50 ],
                columnDefs: [
                    {'orderable':false, "targets": 5 },
                ]
        });

        $(document).ready(function(){
            @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
            @endif

            $("#availableAssignment thead th").each( function (i) {
                if ($(this).text() === 'Status') {
                    var select = $('<select class="filter-select" data-placeholder="Filter"><option value=""></option></select>')
                        .appendTo( $(this).empty())
                        .on('change', function () {
                            var val = $(this).val();
                            
                            table.column(i)
                                .search( val ? '^'+$(this).val()+'$' : val, true, false )
                                .draw();
                        });

                    table.column(i).data().unique().sort().each( function ( d, j ) {  
                        select.append( '<option value="'+d+'">'+d+'</option>' );
                    });	
                }
            });

            $('.filter-select').select2({
                width: '100%'
            });
        });
    </script>
@endpush