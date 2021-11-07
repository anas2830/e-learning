
<!-- Line content divider -->
<div class="panel panel-white">
    <div class="panel-heading">
        <h6 class="panel-title">Assignment Review Discussion</h6>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
                <li><a data-action="reload"></a></li>
                <li><a data-action="close"></a></li>
            </ul>
        </div>
    </div>

    <div class="panel-body">
        @if ($is_assignment_taken)
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
                        @if ($discussion->msg_by_type == 1)
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
                        @elseif($discussion->msg_by_type == 2)
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
                <div id="errorMsgDiv">
                    <div id="newsletterMsg"></div>
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
        @else
            <h6 class="panel-title text-center">You did not take this Assignment !!!</h6>
        @endif
    </div>
</div>
<!-- /line content divider -->

<script type="text/javascript">
    $(document).ready(function(){
        $('#discussion_form').on("submit", function(e) {
            e.preventDefault();
            $selector = $(this);
            let message = $('#message').val();
            var taken_assignment_id = $('#taken_assignment_id').val();
            if (taken_assignment_id && message != '') {
                $.ajax({
                    url : "{{route('discussionMsgSend')}}",
                    type: "POST",
                    data: {"_token":"{{ csrf_token() }}", 'message': message, 'taken_assignment_id':taken_assignment_id},
                    dataType: 'json',
                    success:function(data){
                        var status = parseInt(data.status);
                        if(data.status ==1) {
                            $('#newsletterMsg').html('<div class="alert alert-success alert-dismissible fade show"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> <i class="fa fa-adjust alert-icon"></i> '+data.messege+'</div>');
                            $selector.closest('form').find("textarea").val("");
                            LoadDiscussionMsg(taken_assignment_id);
                        } else if(status==0) {
                            $('#newsletterMsg').html('<div class="alert alert-danger alert-dismissible fade show"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> <i class="fa fa-adjust alert-icon"></i> '+data.messege+'</div>');
                            $('#newsletterMsg').on('click', '#close_icon', function() {
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

    function LoadDiscussionMsg(taken_assignment_id) {
        $.ajax({
            mimeType: 'text/html; charset=utf-8', // ! Need set mimeType only when run from local file
            url: "{{route('discussionMsgAjax')}}",
            type: "GET",
            data: {taken_assignment_id:taken_assignment_id},
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

</script>    
