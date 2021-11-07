

<div class="content">
    <div class="panel panel-white assignment-remark">
        @if ($is_assignment_taken)
            @if (!empty($submitted_assignment))
                <div class="panel-heading">
                    <h5 class="panel-title">[ {{$submitted_assignment->title}} ] </h5>
                    <small class="display-block">{{$submitted_assignment->teacher_name}} || {{ date("jS F, Y", strtotime($submitted_assignment->start_date)) }}
                    </small>
                    <div class="heading-elements" id="answer_mark">
                        <span class="heading-text mr-10">{{ date("jS F, Y", strtotime($submitted_assignment->due_date))}} , {{Helper::timeGia($submitted_assignment->due_time)}}</span>
                        @if($submitted_assignment->mark_by != 0)
                            <span class="btn border-success text-success btn-flat btn-icon btn-rounded btn-sm" title="Assignment Submitted"> {{$submitted_assignment->mark}}/100</span>
                        @else
                            <span class="btn border-danger text-danger btn-flat btn-icon btn-rounded btn-sm" title="Mark Not Given Yet"><i class="icon-cross2"></i></span>
                        @endif
                    </div>
                </div>
        
                <div class="panel-body">
                    <p class="content-group">{!! $submitted_assignment->overview !!}</p>
                    @if(!empty($assignment_attachment))
                    <p class="text-semibold">Given Attachments</p>
                    <div class="grid-demo">
                        <div class="row show-grid">
                            <div class="col-md-4">
                                <ul class="list-group border-left-info border-left-lg">
                                    <li class="list-group-item upload-attachment">
                                        <a href="{{url('uploads/assignment/teacherAttachment/'.$assignment_attachment->file_name)}}" title="Click to Download">
                                            <h6 class="list-group-item-heading">
                                                <img src="{{ asset(Helper::getFileThumb($assignment_attachment->extention)) }}" alt="" height="35" width="40">
                                                {{$assignment_attachment->file_original_name}} 
                                                <span class="label bg-teal-400 pull-right">{{Helper::fileSizeConvert($assignment_attachment->size)}}</span>
                                            </h6>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <legend class="text-bold"></legend>
                    <h6>Student Submission Portion</h6>
                    <p class="text-semibold">Comment: {!! strip_tags($submitted_assignment->std_comment) !!}</p>
                    @if (!empty($submitted_attachment))
                        <p class="text-semibold">Submitted Attachment</p>
                        <div class="grid-demo">
                            <div class="row show-grid">
                                <div class="col-md-4">
                                    <ul class="list-group border-left-info border-left-lg">
                                        <li class="list-group-item upload-attachment">
                                            <a href="{{url('uploads/assignment/studentAttachment/'.@$submitted_attachment->file_name)}}" title="Click to Download">
                                                <h6 class="list-group-item-heading">
                                                    <img src="{{ asset(Helper::getFileThumb(@$submitted_attachment->extention)) }}" alt="" height="35" width="40">
                                                    {{@$submitted_attachment->file_original_name}}
                                                    <span class="label bg-teal-400 pull-right">{{Helper::fileSizeConvert(@$submitted_attachment->size)}}</span>
                                                </h6>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <br>
                    @endif 
                    @if (empty($assignment_comment_exits))
                        <div class="review-mark-form" style="border: 1px dashed gray; padding: 20px;">
                            <h6 class="text-center">Mark & Remark Portion</h6>
                            <legend class="text-bold"></legend>
                            <div id="errorMsgDiv" style="padding:5px 20px">
                                <div id="newsletterMsg"></div>
                            </div>
                            <form class="form-horizontal form-validate-jquery assignmentMarkingForm" action="#" method="POST" enctype="multipart/form-data">
                                @csrf
                                <fieldset>
                                    <input type="hidden" value="{{$submitted_assignment->taken_assignment_id}}" name="taken_assignment_id">
                                    <div class="form-group main">
                                        <label class="control-label col-lg-2">Mark <span class="text-danger">*</span></label>
                                        <div class="col-lg-6">
                                            <input type="text" name="mark"  maxlength="3" class="form-control" placeholder="given mark" oninput="this.value=this.value.replace(/[^0-9]/g,'');">

                                            {{-- <input type="range" name="mark" min="1" max="100" value="98">
                                            <span class="rangeslider__tooltip" id ="range-tooltip"></span> --}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Comment <span class="text-danger">*</span></label>
                                        <div class="col-lg-10">
                                            <textarea id="comment_overview" class="form-control" placeholder="Give Assignment Revision Feedback" name="comment" rows="2" cols="2">Nice work ! on average, Keep practice... <br/>
                                                Good job, You did really good assignment, Keep practice... <br/>
                                                Nice work ! Have little bit padding margin issue on the section, next time try to focus on this.</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2">Reviewed Image <span class="text-danger">Required *</span></label>
                                        <div class="col-lg-10">
                                            {{-- <input type="file" id="attachment" name="attachment" accept="image/png, image/jpeg, image/gif"/> --}}
                                            <input type="file" id="attachment" name="attachment[]" accept="image/png, image/jpeg, image/gif" multiple 
                                            data-allow-reorder="true"
                                            data-max-file-size="3MB"
                                            data-max-files="3"/>
                                            <span class="help-block">Allow extensions: <code>Image</code> Only</span>
                                        </div>
                                    </div>
                                    
                                    <span class="input-group-btn">
                                        <button class="btn bg-teal" type="submit" class="submit_assignment">Submit</button>
                                    </span>
                                </fieldset>
                            </form>
                        </div>
                    @endif
                </div>
            @else
                <div class="panel-body">
                    <h6 class="panel-title text-center">Your Taken Assignment Not Found !!!</h6>
                </div>
            @endif
        @else
            <div class="panel-body">
                <h6 class="panel-title text-center">You did not take this Assignment !!!</h6>
            </div>
        @endif
    
    </div>
</div>
<!-- /content area -->
{{-- <script type="text/javascript" src="{{ asset('backend/assets/js/pages/uploader_bootstrap.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('web/rangSlider/script.js') }}"></script> --}}

<script type="text/javascript">
    $(document).ready(function(){
        $("#comment_overview").summernote({
            height: 150
        });

        $('.upload-attachment a').on('click', function (event) {
            event.preventDefault();
            let openLink = $(this).attr('href');
            window.open(openLink, '_blank');
        })

        $('.assignment-remark').on('submit', '.assignmentMarkingForm', function(e) {
            e.preventDefault();
            var $form = $(this);
            var postData = new FormData(this);  
            $.ajax({
                url : "{{route('support.checkAssignmentMarking')}}",
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

                        $form.parent().find('.assignmentMarkingForm').remove();  
                        var activeHref = $('#sub_menu .active a').attr('href');
                        if (activeHref == 'assignmentDetails') {
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
    });

    FilePond.registerPlugin();
    const inputElement = document.querySelector('input[id="attachment"]');
    const pond = FilePond.create( inputElement );
    FilePond.setOptions({
        server: {
            process: {
                url: '/filepondUpload',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                }
            },
            revert: {
                url: '/filepondDelete',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
            // url: '/filepondUpload',
            // fetch: null,
            // revert: '/filepondDelete',
            // headers: {
            //     'X-CSRF-Token': '{{ csrf_token() }}'
            // }
        }
    });

</script>    
