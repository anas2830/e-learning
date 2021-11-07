@if(count($class_assignments) > 0)
    @foreach ($class_assignments as $assignment)
        <div class="panel panel-white assignment-post-box">
            <div class="panel-heading">
                <div class="">
                    <h5 class="panel-title">{{$assignment->class_name}} [ {{$assignment->title}} ] </h5>
                    <small class="display-block">{{$assignment->name}} || {{ date("jS F, Y", strtotime($assignment->start_date)) }}
                    </small>
                </div>
                <div class="heading-elements" id="answer_mark">
                    <span class="heading-text mr-10">{{ date("jS F, Y", strtotime($assignment->due_date))}} , {{Helper::timeGia($assignment->due_time)}}</span>
                </div>
            </div>
    
            <div class="panel-body">
                <p class="content-group">{!! $assignment->overview !!}</p>
                @if(!empty($assignment->attachment->extention))
                <p class="text-semibold">Given Attachments</p>
                <div class="grid-demo">
                    <div class="row show-grid">
                        <div class="col-md-4">
                            <ul class="list-group border-left-info border-left-lg">
                                <li class="list-group-item">
                                    <a href="javascript:window.open('{{url('uploads/assignment/teacherAttachment/'.$assignment->attachment->file_name)}}')" title="Click to Download">
                                        <h6 class="list-group-item-heading">
                                            <img src="{{ asset(Helper::getFileThumb($assignment->attachment->extention)) }}" alt="" height="35" width="40">
                                            {{$assignment->attachment->file_original_name}} 
                                            <span class="label bg-teal-400 pull-right">{{Helper::fileSizeConvert($assignment->attachment->size)}}</span>
                                        </h6>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @if (count($class_assignments) > 1)
        <legend class="text-bold"></legend>
        @endif
    @endforeach
@else
    <div class="panel panel-white">
        <div class="panel-body">
            <h6 class="panel-title text-center">Assignment Not Found !!!</h6>
        </div>
    </div>
@endif