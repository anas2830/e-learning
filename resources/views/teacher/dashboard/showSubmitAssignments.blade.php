@if(!empty($submit_assignments) > 0)
    <div class="panel panel-white assignment-post-box">
        <div class="panel-heading">
            <div class="">
                <h5 class="panel-title">[ {{$assignment_title}} ] </h5>
                <small class="display-block"><b>Submitted By: </b>{{ date("jS F, Y", strtotime($submit_assignments->created_at)) }}
                </small>
            </div>
        </div>

        <div class="panel-body">
            <p class="content-group"><b>Comment:</b> {!! $submit_assignments->comment !!}</p>
            @if(!empty($submit_attachment))
            <p class="text-semibold">Given Attachments</p>
            <div class="grid-demo">
                <div class="row show-grid">
                    <div class="col-md-4">
                        <ul class="list-group border-left-info border-left-lg">
                            <li class="list-group-item">
                                <a href="javascript:window.open('{{url('uploads/assignment/studentAttachment/'.$submit_attachment->file_name)}}')" title="Click to Download">
                                    <h6 class="list-group-item-heading">
                                        <img src="{{ asset(Helper::getFileThumb($submit_attachment->extention)) }}" alt="" height="35" width="40">
                                        {{$submit_attachment->file_original_name}} 
                                        <span class="label bg-teal-400 pull-right">{{Helper::fileSizeConvert($submit_attachment->size)}}</span>
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
@else
    <div class="panel panel-white">
        <div class="panel-body">
            <h6 class="panel-title text-center">Assignment Not Found !!!</h6>
        </div>
    </div>
@endif