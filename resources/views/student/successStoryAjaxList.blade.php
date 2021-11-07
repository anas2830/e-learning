@if (count($student_success_stories) > 0)
    @foreach ($student_success_stories as $key => $success_item)
    <div class="col-sm-6 col-md-4">
        <div class="slider-item" style="min-height: 420px;">
            <div class="slider-content">
                <div class="slider-top">
                    <input type="hidden" name="success_story_id" value="{{$success_item->id}}">
                    <img src="{{asset('uploads/studentStory/usedImg/'.$success_item->work_screenshort)}}" alt="">
                    <!-- Slider Hover Item -->
                    <div class="slider-hover-react">
                        <div class="slider-icon-inner">
                            <div class="slider-icon-container">
                                <div class="love-icon @if (@Helper::getReactStatus($success_item->id, Auth::id()) == 1) love-checked @endif">
                                    {{-- <i class="fa fa-heart" aria-hidden="true"></i>
                                    i class="fa fa-heart-o" aria-hidden="true"></i> --}}
                                    <i class="icon-heart5 love-fill"></i>
                                    <i class="icon-heart6 love-outline"></i>
                                    <span class="like-counter">{{$success_item->total_reaction}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slider-mid">
                    <div class="slider-left">
                        <div class="left-thumb">
                            <img src="{{asset('uploads/studentProfile/thumb/'.Helper::studentProfileImg($success_item->created_by))}}" alt="">
                        </div>
                        <div class="left-student-info">
                            <h2 class="slider-student-name">{{Helper::studentName($success_item->created_by)}}</h2>
                            <h5 class="slider-course-name">{{Helper::courseName($success_item->course_id)}}</h5>
                            <h3 class="slider-batch-name">{{Helper::batchName($success_item->batch_id)}}</h3>
                        </div>
                    </div>
                    <div class="slider-right">
                        <h3 class="marketplace-name">{{$success_item->marketplace_name}}</h3>
                        <h4 class="job-ammount">${{$success_item->work_amount}}</h4>
                    </div>
                </div>
                <div class="slider-bottom">
                    <p> <span>"</span> {!! $success_item->own_comment !!} <span>"</span></p>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="col-sm-6 col-md-4">
        <div style="height: 400px; line-height: 400px; text-align: center; font-size: 24px; color: 999;">
            <p>Sorry! Success Story not found.. </p>
        </div>
    </div>
@endif

<script type="text/javascript">
    $(document).ready(function(){
        // Love Toggle class
        $("#class-content-grid .slider-top").click(function(){
            var $selector = $(this);
            $(this).find(".love-icon").toggleClass("love-checked");
            let success_story_id = $selector.find("[name='success_story_id']").val();
            let count = $selector.find('.like-counter').text();

            $.ajax({
                url: "{{route('storyReactUpdate')}}",
                data: {success_story_id:success_story_id, _token: '{{csrf_token()}}'},
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if(parseInt(response.auth)===0) {
                        swal({
                            title: "Sorry!!",
                            text: "You have logged out.",
                            type: "error",
                            showCancelButton: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "Login Now!",
                            closeOnConfirm: false
                        },
                        function(){
                            location.replace('{{route("login")}}');
                        });
                    } else if(parseInt(response.status) == 1){
                        $selector.find('.like-counter').text(response.total_reactions);
                        console.log(response.message, response.total_reactions);
                    } else {
                        console.log(response.message);
                    }
                }
            });
        });
    });

</script>