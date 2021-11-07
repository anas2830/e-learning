@if(!empty($reviwer_comments))
    <div class="panel panel-white assignment-post-box">
        <div class="panel-heading">
            <div class="">
                <h5 class="panel-title">[ {{$assignment_title}} ] </h5>
                <small class="display-block"><b>Review at: </b>{{ date("jS F, Y", strtotime($reviwer_comments->created_at)) }}
                </small>
            </div>
        </div>

        <div class="panel-body">
            <p class="content-group"><b>Comment:</b> {!! $reviwer_comments->comment !!}</p>
            @if(isset($reviwer_comments->file_name))
                <p class="text-semibold">Given Attachments</p>
                <div class="grid-demo">
                    <div class="row show-grid">
                        <div class="col-md-4">
                            <ul class="list-group border-left-info border-left-lg">
                                <li class="list-group-item">
                                    <a href="javascript:window.open('{{url('uploads/assignment/teacherComment/'.$reviwer_comments->file_name)}}')" title="Click to Download">
                                        <h6 class="list-group-item-heading">
                                            <img src="{{ asset(Helper::getFileThumb($reviwer_comments->extention)) }}" alt="" height="35" width="40">
                                            {{$reviwer_comments->file_original_name}} 
                                            <span class="label bg-teal-400 pull-right">{{Helper::fileSizeConvert($reviwer_comments->size)}}</span>
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