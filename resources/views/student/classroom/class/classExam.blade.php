@extends('layouts.default')

@push('styles')
    <style>
        /* body{ 
            margin-top:40px; 
        } */

        .stepwizard-step p {
            margin-top: 10px;
        }

        .stepwizard-row {
            display: table-row;
        }

        .stepwizard {
            display: table;
            width: 100%;
            position: relative;
        }

        .stepwizard-step button[disabled] {
            opacity: 1 !important;
            filter: alpha(opacity=100) !important;
        }

        .stepwizard-row:before {
            top: 14px;
            bottom: 0;
            position: absolute;
            content: " ";
            width: 100%;
            height: 1px;
            background-color: #ccc;
            z-order: 0;

        }

        .stepwizard-step {
            display: table-cell;
            text-align: center;
            position: relative;
        }

        .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
        }
    </style>
@endpush
@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('class')}}">Class</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <!-- Basic setup -->
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">Class: [{{$courseClassInfo->class_name}}] Exam</h6>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li>
                        <div id="examTimeCountDown"></div>
                    </li>
                    {{-- <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li> --}}
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <div class="stepwizard mb-15">
                <div class="stepwizard-row setup-panel">
                    @foreach ($examQuestions as $key => $question)
                        <div class="stepwizard-step">
                            @if($key == 0)
                                <a href="#step-{{$key+1}}" type="button" class="btn btn-primary btn-circle step-{{$key+1}}">{{$key+1}}</a>
                                <p>Q-{{$key+1}}</p>
                            @else 
                                <a href="#step-{{$key+1}}" type="button" class="btn btn-default btn-circle disabled step-{{$key+1}}" disabled="disabled">{{$key+1}}</a>
                                <p>Q-{{$key+1}}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <form class="form-horizontal" id="qustion-form" role="form">
                @csrf
                <fieldset>
                    <input type="hidden" name="exam_config_id" value="{{$examConfig->id}}" />
                    <input type="hidden" id="current_time" name="current_time" value="" />
                    @php $total_q = count($examQuestions); @endphp
                    @foreach ($examQuestions as $key => $question)
                    <div class="row setup-content step-{{$key+1}}" id="step-{{$key+1}}" q_id={{$question->id}}>
                        <div class="col-xs-12">
                            <div class="col-md-12">
                                @if ($question->answer_type == 1) {{-- 1 = True/False  --}}
                                    <div class="form-group">
                                        <label><b style="font-size: 18px">Q) {!! strip_tags($question->question) !!}</b></label>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" id="{{$question->id}}" name="answer[{{$question->id}}][]" value="1" class="styled">
                                                True
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" id="{{$question->id}}" name="answer[{{$question->id}}][]" value="0" class="styled">
                                                False
                                            </label>
                                        </div>
                                    </div>
                                @else {{-- 2 = Signle MCQ , 3 = Multiple MCQ --}}
                                @php
                                    $labelClass = $question->answer_type == 2 ? 'radio' : 'checkbox';
                                    $labelType = $question->answer_type == 2 ? 'radio' : 'checkbox';
                                @endphp
                                    <div class="form-group">
                                        <label><b style="font-size: 18px">Q) {!! strip_tags($question->question) !!}</b></label>
                                        @foreach ($question->answers as $option)
                                            <div class="{{$labelClass}}">
                                                <label>
                                                    <input type="{{$labelType}}" id="{{$question->id}}" name="answer[{{$question->id}}][]" value="{{$option->id}}" class="styled" />
                                                    {{strip_tags($option->answer)}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <legend class="text-bold"></legend>
                                @if($total_q == $key+1)
                                    <span id="submitBtnArea">
                                        <button id="submitBtn" class="btn btn-success btn-lg pull-right" type="submit">Submit</button>
                                    </span>
                                @else  
                                    <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Next</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </fieldset>
            </form>
        </div>
       
    </div>
    <!-- /basic setup -->

    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">DevsSquad IT Solutions</a>
    </div>
    <!-- /footer -->
</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript" src="{{ asset('backend/assets/js/duplicate.js') }}"></script>
<script type="text/javascript">
    //Disabled Back
    // window.location.hash="classExamRunning";
    // window.location.hash="classExamRunning";
    // window.onhashchange=function(){window.location.hash="classExamRunning";}
    function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
    $(document).on("keydown", disableF5);
    
    $(document).ready(function () {
        // Start Page Refresh Bindings
        // window.onbeforeunload = function() {
        //     return "Leave this page ?";
        // };
        if (window.IsDuplicate()) {
            swal ("Oops!!","This exam tab is already running on a tab!!!","error");
            window.close();
        };
        // End Page Refresh Bindings


        // start pipeline
        var navListItems = $('div.setup-panel div a'),
                allWells = $('.setup-content'),
                allNextBtn = $('.nextBtn'),
                navList = $('div.setup-panel div');

        allWells.hide();

        navListItems.click(function (e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                    $item = $(this);

            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-primary').addClass('btn-default');
                $item.addClass('btn-primary');
                allWells.hide();
                $target.show();
                $target.find('input:eq(0)').focus();
            }

        });

        allNextBtn.click(function(){
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                curQnsId = curStep.attr("q_id"),
                nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='text'],input[type='url']"),
                isValid = true;

            $(".form-group").removeClass("has-error");
            for(var i=0; i<curInputs.length; i++){
                if (!curInputs[i].validity.valid){
                    isValid = false;
                    $(curInputs[i]).closest(".form-group").addClass("has-error");
                }
            }

            if(curStep.hasClass(curStepBtn)){

                if(navListItems.hasClass(curStepBtn)){

                    if( curStep.find("input[id="+curQnsId+"]").is(':checked') )
                    {
                        navList.find('.'+curStepBtn).removeClass('btn-default');
                        navList.find('.'+curStepBtn).removeClass('btn-danger');
                        navList.find('.'+curStepBtn).removeClass('btn-primary');
                        navList.find('.'+curStepBtn).addClass('btn-success');
                    }
                    else
                    {
                        navList.find('.'+curStepBtn).removeClass('btn-primary');
                        navList.find('.'+curStepBtn).removeClass('btn-default');
                        navList.find('.'+curStepBtn).removeClass('btn-success');
                        navList.find('.'+curStepBtn).addClass('btn-danger');
                    }
                }
                
            }


            if (isValid)
                nextStepWizard.removeClass('disabled');
                nextStepWizard.removeAttr('disabled').trigger('click');
        });

        $('div.setup-panel div a.btn-primary').trigger('click');
        // end pipeline

        // Start Countdown
        String.prototype.toHHMMSS = function () {
            var sec_num = parseInt(this, 10); // don't forget the second parm
            var hours = Math.floor(sec_num / 3600);
            var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
            var seconds = sec_num - (hours * 3600) - (minutes * 60);

            if (hours < 10) {
                hours = "0" + hours;
            }
            if (minutes < 10) {
                minutes = "0" + minutes;
            }
            if (seconds < 10) {
                seconds = "0" + seconds;
            }
            var time = hours + ':' + minutes + ':' + seconds;
            return time;
        }

        var count =  '{{$total_exam_dur}}';
        // var count = '10'; // it's 00:01:02
        var counter = setInterval(examTimeCountDown, 1000);
        function examTimeCountDown() {
            if (parseInt(count) <= 0) {
                clearInterval(counter);
                // $('#qustion-form').submit();
                swal({
                    title: "Oops!!",
                    text: "Your Exam Time has finished",
                    type: "error",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Submit & Get Exam Result!",
                    closeOnConfirm: false
                },
                function(){
                    $('#qustion-form').submit();
                    // location.replace('{{route("class")}}');
                });
                return;
            }
            var temp = count.toHHMMSS();
            count = (parseInt(count) - 1).toString();
            $('#examTimeCountDown').html(temp);
            $('#current_time').val(temp);
        }
        // End Countdown

        $('#qustion-form').submit(function(e){
            e.preventDefault();
            var data = $(this).serializeArray();
            var givenAnswer = data.length;

            if(givenAnswer>3) {
                $button = $('#submitBtn');
                $button.button('loading');
                $.ajax({
                    url : '{{route("classExamSubmit")}}',
                    data: data,
                    type: 'POST',
                    dataType: "json",
                    success: function(response) 
                    {
                        console.log(response);
                        if(response.msgType=='success') {
                            swal({
                                title: "Congratulation!!",
                                text: response.messege,
                                type: "success",
                                showCancelButton: false,
                                confirmButtonClass: "btn-success",
                                confirmButtonColor: "#66bb6a",
                                confirmButtonText: "Get Your Exam Result!",
                                closeOnConfirm: false
                            },
                            function(){
                                window.location.replace('{{route("class", ["class_id"=>$assign_batch_class_id,"#quiz"])}}');
                            });
                        }  else {
                            swal({
                                title: "Sorry!!",
                                text: response.messege,
                                type: "error",
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonColor: "#FF7043",
                                confirmButtonText: "Go Back To Class Quiz!",
                                closeOnConfirm: false
                            },
                            function(){
                                window.location.replace('{{route("class", ["class_id"=>$assign_batch_class_id,"#quiz"])}}');
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal("Cancelled", errorThrown, "error");
                    }
                });
            } else {
                swal("Give your answer, please!");
            }
        });
    });

</script>    
@endpush
