@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.studentReportForm')}}">Student Report</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- Invoice template -->
    <div class="panel panel-flat">
        <div class="panel-heading" style="border-bottom: 1px solid #ddd; margin-bottom: 20px;">
            <h6 class="panel-title">Static invoice</h6>
            <div class="heading-elements">
                <button type="button" class="btn btn-default btn-xs heading-btn"><i class="icon-file-check position-left"></i> Save</button>
                <button type="button" class="btn btn-default btn-xs heading-btn" id="printPreview"><i class="icon-printer position-left"></i> Print</button>
                <input type="hidden" class="form-control" id="batch_id" value="{{ $batch_id }}">
                <input type="hidden" class="form-control" id="student_id" value="{{ $student_id }}">
                <input type="hidden" class="form-control" id="from_date" value="{{ $from_date }}">
                <input type="hidden" class="form-control" id="to_date" value="{{ $to_date }}">
                
            </div>
        </div>

        <div class="panel-body no-padding-bottom">
            <div class="row">
                <div class="col-md-6 content-group">
                    <img src="{{ asset('web/img/fav.png') }}" class="content-group mt-10 mb-10" alt="" style="width: 70px; height: 70px;">
                    <ul class="list-condensed list-unstyled">
                        <li>LFWF Academy</li>
                        <li>Suihari Dinajpur</li>
                        <li><a href="tel:+8801889972995"> +8801889972995</a></li>
                    </ul>
                </div>

                <div class="col-md-6 content-group">
                    <div class="invoice-details">
                        <h5 class="text-uppercase text-semibold">Md. Rafikul Islam Rafi</h5>
                        <ul class="list-condensed list-unstyled">
                            <li>Course Name: <span class="text-semibold">January 12, 2015</span></li>
                            <li>From Date: <span class="text-semibold">{{ date("jS F, Y", strtotime($from_date)) }}</span></li>
                            <li>To Date: <span class="text-semibold">{{ date("jS F, Y", strtotime($to_date)) }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th class="col-md-8">Topic</th>
                        <th class="col-md-2">Base/Status</th>
                        <th class="col-md-2">Gained</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($assign_batch_classess) > 0)
                        @foreach ($assign_batch_classess as $batch_class)
                            <tr class="active border-double">
                                <td colspan="2">{{ $batch_class->class_name }}</td>
                                <td class="text-right">
                                    <span class="progress-meter" id="today-progress" data-progress="30"></span>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <h6 class="no-margin">Attendance</h6>
                                    {{-- <span class="text-muted">One morning, when Gregor Samsa woke from troubled.</span> --}}
                                </td>
                                <td><span class="text-semibold">{{ $batch_class->attend_status }}</span></td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <h6 class="no-margin">Class Performance Mark</h6>
                                </td>
                                <td><span class="text-semibold">{{ $batch_class->class_mark }}</span></td>
                            </tr>

                            <tr>
                                <td colspan="2">
                                    <h6 class="no-margin">Class Quiz/Exam</h6>
                                </td>
                                <td><span class="text-semibold">{{ $batch_class->quiz_mark }}</span></td>
                            </tr>

                            <tr>
                                <td>
                                    <h6 class="no-margin">Class Practice Time</h6>
                                </td>
                                <td>
                                    <div class="text-default text-semibold"> {{ Helper::secondsToTime($batch_class->base_practice_time) }} </div>
                                    <div class="text-muted text-size-small">
                                        <span class="status-mark border-blue position-left"></span>
                                        Need To Practice
                                    </div>
                                </td>
                                <td><span class="text-semibold">{{ Helper::secondsToTime($batch_class->final_practice_time) }}</span></td>
                            </tr>

                            @if (count($batch_class->assignments) > 0)
                                @foreach ($batch_class->assignments as $assignment_key => $class_assignment)
                                    <tr>
                                        <td>
                                            <h6 class="no-margin">Assignment: {{++$assignment_key}} {{ $class_assignment->title }}</h6>
                                        </td>
                                        <td>{{ $class_assignment->assignment_submit_status }}</td>
                                        <td><span class="text-semibold">{{ $class_assignment->assignment_mark }}</span></td>
                                    </tr>
                                @endforeach
                            @endif

                            @if (count($batch_class->videos) > 0)
                                @foreach ($batch_class->videos as $video_key => $class_video)
                                    <tr>
                                        <td>
                                            <h6 class="no-margin">Video: {{++$video_key}} {{ $class_video->video_title }}</h6>
                                        </td>
                                        <td>
                                            <div class="text-default text-semibold"> {{ Helper::secondsToTime($class_video->video_duration) }} </div>
                                            <div class="text-muted text-size-small">
                                                <span class="status-mark border-blue position-left"></span>
                                                Need To Watch
                                            </div>
                                        </td>
                                        <td><span class="text-semibold">{{ $class_video->watched_duration }}</span></td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">
                                <h6 class="no-margin">No Data Found!!!</h6>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
    <!-- /invoice template -->


    <!-- Footer -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif

        $('#printPreview').on('click', function (e) {
            e.preventDefault();
            let batch_id = $('#batch_id').val();
            let student_id = $('#student_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var width = $(document).width();
            var height = $(document).height();
            var url = "{{route('teacher.studentReportPrint')}}"+'?batch_id='+batch_id+ '&student_id='+student_id+ '&from_date='+from_date+ '&to_date='+to_date;
            var myWindow = window.open(url, "", "width="+width+",height="+height);
        })
    })
</script>
@endpush
