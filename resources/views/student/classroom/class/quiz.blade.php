
<input type="hidden" id="assign_batch_class_id" value="{{$assign_batch_class_id}}" />
<!-- Content area -->
<div class="content">
    {{-- Quiz Start --}}
    @if (!$classCompleteStatus)
        @if (!empty($examConfig))
            <div class="panel panel-body border-top-primary">
                <div>
                    @php $examDuration = $examConfig->exam_duration * 60; @endphp
                    <h6 class="no-margin text-semibold mb-5">Total Question: {{$examConfig->total_question}} And Duration: {{Helper::secondsToTime($examDuration)}} </h6>
                    <p class="content-group-sm text-muted">{!! $examConfig->exam_overview !!}</p>
                    <a href="{{route('classExamRunning', $assign_batch_class_id)}}"><button type="button" class="btn btn-primary btn-sm" id="h-fill-basic-start">Start Exam</button></a>
                </div>
            </div>
        @else
            <div class="panel panel-body border-top-primary">
                <div class="text-center">
                    <h6 class="no-margin text-semibold mb-5"> Class Exam is not Configured Yet!!! </h6>
                    <p class="content-group-sm text-muted">You will get the exam When your class teacher create this class exam.</p>
                </div>
            </div>
        @endif
    @else
        <div class="panel panel-body border-top-primary">
            <div class="text-center">
                <h6 class="no-margin text-semibold mb-5" style="color: red;">Opps! This class have been Completed !!! </h6>
                <p class="content-group-sm text-muted">You will have get the exam start option before the class have been completed.</p>
            </div>
        </div>
    @endif
    {{--End Quiz Start --}}
</div>
<!-- /content area -->