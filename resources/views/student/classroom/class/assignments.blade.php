<div class="content">
    <input type="hidden" id="assign_batch_class_id" value="{{$assign_batch_class_id}}" />
    @if(!empty($assignment))
        <div class="panel panel-white assignment-post-box">
            <div class="panel-heading">
                <div class="">
                    <h5 class="panel-title">{{$assignment->class_name}} [ {{$assignment->title}} ] </h5>
                    <small class="display-block">{{$assignment->name}} || {{ date("jS F, Y", strtotime($assignment->start_date)) }}
                    </small>
                </div>
                <div class="heading-elements" id="answer_mark">
                    <span class="heading-text mr-10">{{ date("jS F, Y", strtotime($assignment->due_date))}} , {{Helper::timeGia($assignment->due_time)}}</span>
                    @if(!empty($assignment->submitted) && $assignment->submitted->late_submit == 0)
                        @if (!empty($assignment->reviewedComment))
                            <span class="btn border-success text-success btn-flat btn-icon btn-rounded btn-sm" title="Assignment Submitted"> {{$assignment->submitted->mark}}/100</span>
                            
                            @if($assignment->submitted->mark_by_type != 0 && $assignment->submitted->mark_from == 0)
                                <span class="data-list">
                                    <button type="button" class="btn btn-danger btn-xs btn-rounded open-modal" modal-title="Request For Revision Against This Assignment" modal-type="update" modal-size="medium" modal-class="" selector="Complain" modal-link="{{route('assignmentComplain', [$assignment->submitted->id])}}">Request For Revision</button>
                                </span>
                            @endif
                        @endif
                    @elseif(!empty($assignment->submitted) && $assignment->submitted->late_submit == 1)
                        @if (!empty($assignment->reviewedComment))
                            <span class="btn border-success text-success btn-flat btn-icon btn-rounded btn-sm" title="Late Submitted">  {{$assignment->submitted->mark}}/100</span>
                        @endif
                        @if($assignment->submitted->mark_by_type != 0 && $assignment->submitted->mark_from == 0)
                            <span class="data-list">
                                <button type="button" class="btn btn-danger btn-xs btn-rounded open-modal" modal-title="Request For Revision Against This Assignment" modal-type="update" modal-size="medium" modal-class="" selector="Complain" modal-link="{{route('assignmentComplain', [$assignment->submitted->id])}}">Request For Revision</button>
                            </span>
                        @endif
                    @else
                    <span class="btn border-danger text-danger btn-flat btn-icon btn-rounded btn-sm" title="Not Submit Yet"><i class="icon-cross2"></i></span>
                    @endif
                </div>
            </div>

            <div id="errorMsgDiv" style="padding:5px 20px">
                <div id="newsletterMsg"></div>
            </div>
    
            <div class="panel-body">
                <p class="content-group">{!! $assignment->overview !!}</p>
                @if(!empty($assignment->attachment->extention))
                <p class="text-semibold">Given Attachments</p>
                <div class="grid-demo">
                    <div class="row show-grid">
                        <div class="col-md-4">
                            <ul class="list-group border-left-info border-left-lg">
                                <li class="list-group-item upload-attachment">
                                    <a href="{{url('uploads/assignment/teacherAttachment/'.$assignment->attachment->file_name)}}" title="Click to Download">
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

                @if(empty($assignment->submitted))
                    @if($assignment->completeStatus != 1 )
                        <form class="form-horizontal form-validate-jquery assignmentForm" id="form_{{$assignment->id}}" action="#" method="POST" enctype="multipart/form-data">
                            @csrf
                            <fieldset>
                                <input type="hidden" value="{{$assignment->id}}" name="assignment_id">
                                <legend class="text-bold"></legend>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <input type="file" class="file-input" name="attachment">
                                        <span class="help-block">Allow extensions: <code>jpg/jpeg</code> , <code>png</code>, <code>pdf</code> , <code>doc</code>, <code>docx</code> and  <code>zip</code>and  Allow Size: <code>5 MB</code> Only</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <input type="hidden" name="submit_type" value="1">
                                        <textarea class="form-control" placeholder="Add Assignment Url, You can add multiple Url and comment" name="comment" id="comment" rows="5" cols="5"></textarea>
                                    </div>
                                </div>
                                <span class="input-group-btn">
                                    <button class="btn bg-teal" type="submit" class="submit_assignment">Submit</button>
                                </span>
                            </fieldset>
                        </form>
                        @else 
                        <legend class="text-bold"></legend>
                        <p class="text-semibold text-center" style="color: red;">This Class Already Completed !</p>
                    @endif
                @else 
                    <legend class="text-bold"></legend>
                    <p class="text-semibold">Submitted Attachment</p>
                    @if (!empty($assignment->submittedAttachment))
                        <div class="grid-demo">
                            <div class="row show-grid">
                                <div class="col-md-4">
                                    <ul class="list-group border-left-info border-left-lg">
                                        <li class="list-group-item upload-attachment">
                                            <a href="{{url('uploads/assignment/studentAttachment/'.@$assignment->submittedAttachment->file_name)}}" title="Click to Download">
                                                <h6 class="list-group-item-heading">
                                                    <img src="{{ asset(Helper::getFileThumb(@$assignment->submittedAttachment->extention)) }}" alt="" height="35" width="40">
                                                    {{@$assignment->submittedAttachment->file_original_name}}
                                                    <span class="label bg-teal-400 pull-right">{{Helper::fileSizeConvert(@$assignment->submittedAttachment->size)}}</span>
                                                </h6>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($assignment->completeStatus != 1 && !$is_taken_by_reviewr)
                    <form class="form-horizontal form-validate-jquery assignmentFormUpdate" id="form_{{$assignment->id}}" action="#" method="POST">
                        @csrf
                        <fieldset>
                            <input type="hidden" value="{{$assignment->id}}" name="assignment_id">
                            <legend class="text-bold"></legend>
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <input type="hidden" name="submit_type" value="2">
                                    <textarea class="form-control" placeholder="Add Assignment Url, You can add multiple Url and comment" name="comment" id="comment" rows="5" cols="5">{!! $assignment->submitted->comment !!}</textarea>
                                </div>
                            </div>
                            <span class="input-group-btn">
                                <button class="btn bg-teal" type="submit" class="submit_assignment">Update</button>
                            </span>
                        </fieldset>
                    </form>
                    @else 
                        <div class="form-group">
                            <div class="col-lg-12">
                                <p class="text-semibold"> {!! strip_tags($assignment->submitted->comment) !!} </p>
                            </div>
                        </div>
                        <legend class="text-bold"></legend>
                        <p class="text-semibold text-center" style="color: red;">This Class Already Completed / Assignment Taken by Reviewer / Assignment Reviewed.That's why update not available !</p>
                    @endif
                    
                @endif

                @if (!empty($assignment->reviewedComment))
                    <legend class="text-bold"></legend>
                    <p class="content-group text-bold">Reviewed by Someone</p>
                    <p class="content-group">Comment: {!! @$assignment->reviewedComment->comment !!}</p>
                    @if (count($reviewedCommentAttachments) > 0)
                    <p class="text-semibold">Given Attachments</p>
                    <div class="grid-demo">
                        <div class="row show-grid">
                            @foreach ($reviewedCommentAttachments as $commentAttachItem)
                                <div class="col-md-3">
                                    <ul class="list-group border-left-info border-left-lg">
                                        <li class="list-group-item upload-attachment">
                                            <a href="{{url('uploads/assignment/teacherComment/'.@$commentAttachItem->file_name)}}" title="Click to Download">
                                                <h6 class="list-group-item-heading">
                                                    <img src="{{ asset('uploads/assignment/teacherComment/thumb/'.@$commentAttachItem->file_name) }}" alt="" height="35" width="40">
                                                    <span class="label bg-teal-400 pull-right">{{Helper::fileSizeConvert(@$commentAttachItem->size)}}</span>
                                                </h6>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif
            </div>

            @if(!empty($assignment->submitted) && $is_taken_by_reviewr)
            <div class="panel panel-white">
                <div class="panel-body">
                    <input type="hidden" id="assignment_submission_id" value="{{$assignment->submitted->id}}">
                    @if (count($all_discussions) > 0)
                        <ul class="media-list chat-list content-group" id="discussion_portion" style="height: 60vh; overflow: auto;">
                            @foreach ($all_discussions as $key => $discussion)
                                @php
                                    $created_at = DateTime::createFromFormat('Y-m-d H:i:s', $discussion->created_at)->format('Y-m-d');
                                    if ($created_at == date('Y-m-d')) {
                                        $created_dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $discussion->created_at)->format('g:i A');
                                    } else {
                                        $created_dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $discussion->created_at)->format('Y-m-d g:i A');
                                    }
                                @endphp
                                @if($discussion->msg_by_type == 2 || $discussion->msg_by_type == 3) {{-- ReviewerStd/SupportManager --}}
                                    <li class="media">
                                        <div class="media-left">
                                            <a href="{{ asset('backend/assets/images/placeholder.jpg') }}">
                                                <img src="{{ asset('backend/assets/images/placeholder.jpg') }}" class="img-circle" alt="">
                                            </a>
                                        </div>
                        
                                        <div class="media-body">
                                            <div class="media-content">{{$discussion->message}}</div>
                                            <span class="media-annotation display-block mt-10">{{$created_dateTime}}</span>
                                        </div>
                                    </li>
                                @elseif($discussion->msg_by_type == 1) {{-- Student --}}
                                    <li class="media reversed">
                                        <div class="media-body">
                                            <div class="media-content">{{$discussion->message}}</div>
                                            <span class="media-annotation display-block mt-10">{{$created_dateTime}}</span>
                                        </div>
                        
                                        <div class="media-right">
                                            <a href="{{ asset('backend/assets/images/placeholder.jpg') }}">
                                                <img src="{{ asset('backend/assets/images/placeholder.jpg') }}" class="img-circle" alt="">
                                            </a>
                                        </div>
                                    </li>
                                @endif
                    
                            @endforeach
                        </ul>
                    @else
                        <ul class="media-list chat-list content-group" id="discussion_portion">
                            <li class="media date-step content-divider">
                                <span>No Discussion Found!!!</span>
                            </li>
                        </ul>
                    @endif

                    <form action="" id="discussion_form">
                        <div id="disErrorMsgDiv">
                            <div id="disNewsletterMsg"></div>
                        </div>
                        <textarea name="message" id="message" class="form-control content-group" rows="3" cols="1" placeholder="Enter your message..."></textarea>
                
                        <div class="row">
                            <div class="col-xs-6">
                            </div>
                
                            <div class="col-xs-6 text-right">
                                <button type="submit" id="submitBtn" class="btn bg-teal-400 btn-labeled btn-labeled-right"><b><i class="icon-circle-right2"></i></b> Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>

    @else
        <div class="panel panel-white">
            <div class="panel-body">
                <h6 class="panel-title text-center">Assignment Not Found !!!</h6>
            </div>
        </div>
    @endif
    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">DevsSquad IT Solutions</a>
    </div>
    <!-- /footer -->
</div>
<!-- /content area -->

{{-- @push('javascript') --}}
<script type="text/javascript" src="{{ asset('backend/assets/js/pages/uploader_bootstrap.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#comment").summernote({
            height: 150
        });
        $('.upload-attachment a').on('click', function (event) {
            event.preventDefault();
            let openLink = $(this).attr('href');
            window.open(openLink, '_blank');
        })

        // setTimeout(function() {$('#newsletterMsg').hide()}, 4000);
        $('#discussion_form').on("submit", function(e) {
            e.preventDefault();
            $selector = $(this);
            let message = $('#message').val();
            var assignment_submission_id = $('#assignment_submission_id').val();
            if (assignment_submission_id && message != '') {
                $.ajax({
                    url : "{{route('stdDiscussionMsgSend')}}",
                    type: "POST",
                    data: {"_token":"{{ csrf_token() }}", 'message': message, 'assignment_submission_id':assignment_submission_id},
                    dataType: 'json',
                    success:function(data){
                        var status = parseInt(data.status);
                        if(data.status ==1) {
                            $('#disNewsletterMsg').html('<div class="alert alert-success alert-dismissible fade show"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> <i class="fa fa-adjust alert-icon"></i> '+data.messege+'</div>');
                            $selector.closest('form').find("textarea").val("");
                            LoadDiscussionMsg(assignment_submission_id);
                        } else if(status==0) {
                            $('#disNewsletterMsg').html('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> <i class="fa fa-adjust alert-icon"></i> '+data.messege+'</div>');
                            $('#disNewsletterMsg').on('click', '#close_icon', function() {
                                $("#submitBtn").removeAttr("disabled").removeClass("disabled");
                                $selector.closest('form').find("textarea").val("");
                            })
                        }
                    }
                });
            } else {
                alert('Write Your Message!!!');
            }
        });
    });

    function LoadDiscussionMsg(assignment_submission_id) {
        $.ajax({
            mimeType: 'text/html; charset=utf-8', // ! Need set mimeType only when run from local file
            url: "{{route('stdDiscussionMsgAjax')}}",
            type: "GET",
            data: {assignment_submission_id:assignment_submission_id},
            dataType: "html",
            success: function (data) {
                if (parseInt(data) === 0) {
                    //location.replace('');
                } else {
                    $('#discussion_portion').html(data);
                }
            }
        });
    }

    $('.assignment-post-box').on('submit', '.assignmentForm', function(e) {
        e.preventDefault();
        var $form = $(this);
        var postData = new FormData(this);  
        $.ajax({
            url : "{{route('submitAssignment')}}",
            type: "POST",
            data: postData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                var status = parseInt(data.status);
                if(status==1) {
                    $("input[name='comment']").val('');
                    $form.parent().parent().find('#newsletterMsg').html('<div class="alert alert-success fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <i class="fa fa-adjust alert-icon"></i> '+data.messege+'</div>');

                    setTimeout(function() {
                        $('#newsletterMsg').hide();
                        $form.parent().parent().find('#errorMsgDiv').html('<div id="newsletterMsg"></div>');
                    }, 6000);

                    $('#newsletterMsg').on('click', '#close_icon', function() {
                        $form.parent().parent().find('#errorMsgDiv').html('<div id="newsletterMsg"></div>');
                    });

                    $form.parent().find('.assignmentForm').remove();  
                    var activeHref = $('#sub_menu .active a').attr('href');
                    if (activeHref == 'assignments') {
                        $('#sub_menu .active a').trigger('click');
                    }
                    // $form.hide();
                } else {
                    $("input[name='comment']").val('');
                    $('#newsletterMsg').html('<div class="alert alert-danger fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <i class="fa fa-adjust alert-icon"></i> '+data.messege+'</div>');

                    setTimeout(function() {
                        $('#newsletterMsg').hide();
                        $form.parent().parent().find('#errorMsgDiv').html('<div id="newsletterMsg"></div>');
                    }, 6000);

                    $('#newsletterMsg').on('click', '#close_icon', function() {
                        $form.parent().parent().find('#errorMsgDiv').html('<div id="newsletterMsg"></div>');
                    });
                }
            }
        });
    });
    // Assignment Form Update
    $('.assignment-post-box').on('submit', '.assignmentFormUpdate', function(e) {
        e.preventDefault();
        var $form = $(this);
        var postData = new FormData(this);  
        $.ajax({
            url : "{{route('submitAssignment')}}",
            type: "POST",
            data: postData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                var status = parseInt(data.status);
                if(status==1) {
                    $("input[name='comment']").val('');
                    $form.parent().parent().find('#newsletterMsg').html('<div class="alert alert-success fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <i class="fa fa-adjust alert-icon"></i> '+data.messege+'</div>');

                    setTimeout(function() {
                        $('#newsletterMsg').hide();
                        $form.parent().parent().find('#errorMsgDiv').html('<div id="newsletterMsg"></div>');
                    }, 6000);

                    $('#newsletterMsg').on('click', '#close_icon', function() {
                        $form.parent().parent().find('#errorMsgDiv').html('<div id="newsletterMsg"></div>');
                    });

                    // $form.parent().find('.assignmentFormUpdate').remove();

                } else {
                    $("input[name='comment']").val('');
                    $('#newsletterMsg').html('<div class="alert alert-danger fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <i class="fa fa-adjust alert-icon"></i> '+data.messege+'</div>');

                    setTimeout(function() {
                        $('#newsletterMsg').hide();
                        $form.parent().parent().find('#errorMsgDiv').html('<div id="newsletterMsg"></div>');
                    }, 6000);

                    $('#newsletterMsg').on('click', '#close_icon', function() {
                        $form.parent().parent().find('#errorMsgDiv').html('<div id="newsletterMsg"></div>');
                    });
                }
            }
        });
    });
    // For fist video thumb show End
</script>    
{{-- @endpush --}}
