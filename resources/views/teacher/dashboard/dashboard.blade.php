@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <div class="panel panel-flat">
        <div class="table-responsive">
            <table class="table text-nowrap data-list">
                <thead>
                    <tr>
                        <th class="col-md-3">Batch</th>
                        <th class="col-md-3">ClassName</th>
                        <th class="col-md-2">Assignment</th>
                        <th class="col-md-2">Quiz</th>
                        <th class="col-md-2 text-center" style="width: 20px;">Videos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="active border-double">
                        <td colspan="4">Today's Class List</td>
                        <td class="text-right">
                            <span class="progress-meter" id="today-progress" data-progress="30"></span>
                        </td>
                    </tr>
                    @if (!empty($my_assigned_batches))
                        @foreach ($my_assigned_batches as $batch)
                            @if (!empty($batch->running_class))
                                <tr>
                                    <td>
                                        <div class="media-left media-middle">
                                            <i class="icon-checkmark3 text-success"></i>
                                        </div>
                                        <div class="media-left">
                                            <div class="text-default text-semibold">{{$batch->batch_no}}</div>
                                        </div>
                                        <div class="media-right">
                                            <a href="{{$batch->batch_fb_url}}" target="_blank" title="Go To FB Group" class="btn border-teal-400 text-teal btn-flat btn-rounded btn-icon btn-xs"><i class="icon-stats-growth2"></i></a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="media-left">
                                            <div class="text-default text-semibold">{{$batch->running_class->class_name}}</div>
                                            <div class="text-muted text-size-small">
                                                <span class="status-mark border-blue position-left"></span>
                                                {{ Helper::timeGia($batch->running_class->start_time) }} ({{ date("jS F, Y", strtotime($batch->running_class->start_date)) }})
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="media-left">
                                            <a href="{{route('teacher.batchstuAssignments.index', ['batch_class_id'=>$batch->running_class->id])}}" class="btn border-teal-400 text-teal btn-flat btn-rounded btn-icon btn-xs"><i class="icon-redo2"></i></a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <a href="#" class="letter-icon-title open-modal" modal-title="Class Assignment" modal-type="show" modal-size="large" modal-class="" selector="viewAssignment" modal-link="{{route('teacher.classAssignment', [$batch->running_class->id])}}">View Assignment</a>
                                            </div>
                                            <div class="text-muted text-size-small"><i class="icon-checkmark3 text-success text-size-mini position-left"></i>{{$batch->run_total_submitted_assignment}} Submitted</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="media-left">
                                            <a href="{{route('teacher.classExamResult', ['batch_class_id'=>$batch->running_class->id])}}" class="btn border-teal-400 text-teal btn-flat btn-rounded btn-icon btn-xs"><i class="icon-redo2"></i></a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <a href="{{route('teacher.classExamResult', ['batch_class_id'=>$batch->running_class->id])}}" class="letter-icon-title">Go To Quiz</a>
                                            </div>
                                            <div class="text-muted text-size-small"><i class="icon-checkmark3 text-success text-size-mini position-left"></i>{{$batch->run_total_given_quiz}} Given</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-info btn-xs open-modal" modal-title="Class Videos" modal-type="show" modal-size="large" modal-class="" selector="viewVideo" modal-link="{{route('teacher.classVideos', [$batch->running_class->class_id])}}"> View Videos </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @else 
                        <tr>
                            <td colspan="5">No Data Found!</td>
                        </tr>
                    @endif

                    <tr class="active border-double">
                        <td colspan="4">Last Completed Class List</td>
                        <td class="text-right">
                            <span class="progress-meter" id="yesterday-progress" data-progress="65"></span>
                        </td>
                    </tr>
                    @if (!empty($my_assigned_batches))
                        @foreach ($my_assigned_batches as $batch)
                            @if (!empty($batch->completed_class))
                                <tr>
                                    <td>
                                        <div class="media-left media-middle">
                                            <i class="icon-checkmark3 text-success"></i>
                                        </div>
                                        <div class="media-left">
                                            <div class="text-default text-semibold">{{$batch->batch_no}}</div>
                                        </div>
                                        <div class="media-right">
                                            <a href="{{$batch->batch_fb_url}}" target="_blank" title="Go To FB Group" class="btn border-teal-400 text-teal btn-flat btn-rounded btn-icon btn-xs"><i class="icon-stats-growth2"></i></a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="media-left">
                                            <div class="text-default text-semibold">{{$batch->completed_class->class_name}}</div>
                                            <div class="text-muted text-size-small">
                                                <span class="status-mark border-blue position-left"></span>
                                                {{ Helper::timeGia($batch->completed_class->start_time) }} ({{ date("jS F, Y", strtotime($batch->completed_class->start_date)) }})
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="media-left">
                                            <a href="{{route('teacher.batchstuAssignments.index', ['batch_class_id'=>$batch->completed_class->id])}}" class="btn border-teal-400 text-teal btn-flat btn-rounded btn-icon btn-xs"><i class="icon-redo2"></i></a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <a href="#" class="letter-icon-title open-modal" modal-title="Class Assignment" modal-type="show" modal-size="large" modal-class="" selector="viewAssignment" modal-link="{{route('teacher.classAssignment', [$batch->completed_class->id])}}">View Assignment</a>
                                            </div>
                                            <div class="text-muted text-size-small"><i class="icon-checkmark3 text-success text-size-mini position-left"></i>{{$batch->com_total_submitted_assignment}} Submitted</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="media-left">
                                            <a href="{{route('teacher.classExamResult', ['batch_class_id'=>$batch->completed_class->id])}}" class="btn border-teal-400 text-teal btn-flat btn-rounded btn-icon btn-xs"><i class="icon-redo2"></i></a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-heading">
                                                <a href="{{route('teacher.classExamResult', ['batch_class_id'=>$batch->completed_class->id])}}" class="letter-icon-title">Go To Quiz</a>
                                            </div>
                                            <div class="text-muted text-size-small"><i class="icon-checkmark3 text-success text-size-mini position-left"></i>{{$batch->com_total_given_quiz}} Given</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-info btn-xs open-modal" modal-title="Class Videos" modal-type="show" modal-size="large" modal-class="" selector="viewVideo" modal-link="{{route('teacher.classVideos', [$batch->completed_class->class_id])}}"> View Videos </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @else 
                        <tr>
                            <td colspan="5">No Data Found!</td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Assignment Complain List</h5>
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
        <table class="table table-bordered table-hover datatable-highlight data-list" id="complainList">
            <thead>
                <tr>
                    <th width="2%">SL.</th>
                    <th width="8%">Student</th>
                    <th width="5%">Course</th>
                    <th width="15%">Batch & Class</th>
                    <th width="10%">View Assignment</th>
                    <th width="10%">Submission Details</th>
                    <th width="10%">Reviwer Details</th>
                    <th width="35%">Complain</th>
                    <th width="5%" class="text-center">Marking</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($assignment_complain))
                    @foreach ($assignment_complain as $key => $complain)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ @Helper::getUserName(1, $complain->complain_from) }}</td>
                            <td>{{ Helper::courseName($complain->course_id) }}</td>
                            <td>{{ Helper::batchName($complain->batch_id) }} [{{ Helper::getClassName($complain->assign_batch_class_id) }}]</td>
                            <td>
                                <a href="#" class="letter-icon-title open-modal" modal-title="Class Assignment" modal-type="show" modal-size="large" modal-class="" selector="viewAssignment" modal-link="{{route('teacher.classAssignment', [$complain->assign_batch_class_id])}}">View Assignment</a>
                            </td>
                            <td>
                                <a href="#" class="letter-icon-title open-modal" modal-title="Submit Assignment" modal-type="show" modal-size="large" modal-class="" selector="viewAssignment" modal-link="{{route('teacher.viewSubmitAssignment', [$complain->assignment_submission_id])}}">View Submission</a>
                            </td>
                            <td>
                                <a href="#" class="letter-icon-title open-modal" modal-title="Submit Reviwer" modal-type="show" modal-size="large" modal-class="" selector="viewAssignment" modal-link="{{route('teacher.viewReviwerSubmitAssignment', [$complain->assignment_submission_id])}}">View Reviwer</a>
                            </td>
                            <td>{{ $complain->complain }}</td>
                            <td class="text-center">
                                @if ($complain->review_status == 3)
                                    <button type="button" class="btn btn-primary btn-sm open-modal" modal-title="Update Mark" modal-type="update" modal-size="medium" modal-class="" selector="Marking" modal-link="{{route('teacher.complainAssignmentMark', ['complain_id'=> $complain->id])}}">Update Mark</button>
                                @else
                                    Done
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
</div>
<!-- /content area -->
@endsection
@push('javascript')
<script type="text/javascript">
    var table = $('#complainList').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 3}
            ]
    });
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif

        $("#studentTable thead th").each( function (i) {
            if ($(this).text() === 'Batch') {
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
    });

</script>
@endpush