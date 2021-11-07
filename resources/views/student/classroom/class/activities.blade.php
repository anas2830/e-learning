
<input type="hidden" id="assign_batch_class_id" value="{{$assign_batch_class_id}}" />
<!-- Content area -->
<div class="content">
    <div class="panel panel-body border-top-danger text-center">
        <h6 class="no-margin text-semibold mb-10">This Class Progress Ratio</h6>
        <div class="progress content-group-sm">
            <div class="progress-bar @if($this_class_final_progress < 40) progress-bar-warning @else progress-bar-success @endif progress-bar-striped active" style="width: {{$this_class_final_progress}}%">
                <span>{{$this_class_final_progress}}%</span>
            </div>
        </div>
    </div>

    {{-- Class Attendence --}}
    <div class="panel panel-body border-top-primary">
        <div class="text-center">
            <h6 class="no-margin text-semibold mb-5">This Class Attedance</h6>
        </div>
        <ul class="list-group">
            @if (!empty($attendence_info))
                @if($attendence_info->is_attend == 1)
                <li class="list-group-item mb-5 list-group-item-success">You Already Attend this class.
                    {{-- <span class="badge badge-success">Mark: {{$attendence_info->mark}}</span> --}}
                </li>
                @else 
                    <li class="list-group-item mb-5 list-group-item-danger">You didn't Attend this class.!!</li>
                @endif
            @else 
                <li class="list-group-item mb-5 list-group-item-warning">Attedance Not Taken Yet!!</li>
            @endif
        </ul>
    </div>
    {{--End Class Attendence --}}

    {{-- assignment list --}}
    <div class="panel panel-white">
        <div class="panel-heading">
            <h5 class="panel-title">Assignment List</h5>
        </div>
        <div class="panel-body">
            <div class="content-group">
                <ul class="list-group" style="height: 300px; overflow: auto;">
                    @if(count($class_assignments)> 0)
                    @foreach ($class_assignments as $assignment)
                        @if(empty($assignment->submit))
                            <li class="list-group-item mb-5 list-group-item-danger">{{$assignment->title}} 
                                <span class="badge badge-danger">InComplete</span>
                            </li>
                        @endif
                        @if(!empty($assignment->submit) && $assignment->submit->mark == 0)
                            <li class="list-group-item mb-5 list-group-item-warning">{{$assignment->title}} 
                                <span class="badge badge-warning">Pending</span>
                            </li>
                        @endif
                        @if(!empty($assignment->submit) && $assignment->submit->mark != 0)
                            <li class="list-group-item mb-5 list-group-item-success">{{$assignment->title}} 
                                <span class="badge badge-success">{{$assignment->submit->mark}}</span>
                            </li>
                        @endif
                    @endforeach
                    @else 
                        <li class="list-group-item mb-5 list-group-item-info">Assignment Not Found</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    {{-- end assignment list --}}

    {{-- Class Video list --}}
    <div class="panel panel-white">
        <div class="panel-heading">
            <h5 class="panel-title">Video Materials List</h5>
        </div>
        <div class="panel-body">
            <div class="content-group">
                <ul class="list-group" style="height: 300px; overflow: auto;">
                    @if(count($class_videos) > 0)
                    @foreach ($class_videos as $video)
                        <li class="list-group-item mb-5 list-group-item-info">{{$video->video_title}} 
                            @if ($video->watch_time == 0)
                                <span class="badge badge-danger">{{Helper::secondsToTime($video->watch_time)}} / [{{Helper::secondsToTime($video->video_duration)}}]</span>
                            @else 
                            <span class="badge badge-success">{{Helper::secondsToTime($video->watch_time)}} / [{{Helper::secondsToTime($video->video_duration)}}]</span>
                            @endif
                        </li>
                    @endforeach
                    @else 
                        <li class="list-group-item mb-5 list-group-item-info">Video Materials Not Found</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    {{-- end Class Video list --}}
</div>
<!-- /content area -->