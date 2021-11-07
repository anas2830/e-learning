<!-- Content area -->
<div class="content">
    <div class="panel panel-body">
        @foreach ($examQuestions as $key => $question)
        @php
            $givenAnswer = (!empty($question->answer)) ? unserialize($question->answer) : [];
        @endphp

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label><b style="font-size: 18px">Q{{++$key}}) {!! $question->question !!}</b></label>
                    @if ($question->answer_type == 1) {{-- 1 = True/False  --}}
                    @php
                        if($question->answered==0) { $givenAnswer[0]=2; /*Not_Answered*/ }
                    @endphp
                        <div class="radio">
                            <label class="
                                @if($givenAnswer[0]==1)@if($question->answerSet[0]->true_answer==1){{'text-success'}}@else($answer->true_answer==1){{'text-danger'}}@endif @endif" for="ans-{{$question->question_id}}-01">
                                    <input name="answer_{{$question->question_id}}" id="ans-{{$question->question_id}}-01" type="radio" disabled @if($givenAnswer[0]==1){{'checked='}}@endif>
                                    <span class="@if($question->answerSet[0]->true_answer==1 && $givenAnswer[0]!=1){{'text-danger'}}@endif">True</span> 
                                    @if($question->answerSet[0]->true_answer==1)| <span class="text-success"><i class="icon-checkmark4"></i> Correct</span>
                                @endif
                            </label>
                        </div>
                        <div class="radio">
                            <label class="
                                @if($givenAnswer[0]==0)@if($question->answerSet[0]->true_answer==0){{'text-success'}}@else{{'text-danger'}}@endif @endif" for="ans-{{$question->question_id}}-02"><input name="answer_{{$question->question_id}}" id="ans-{{$question->question_id}}-02" type="radio" disabled @if($givenAnswer[0]==0){{'checked='}}@endif>
                                <span class="@if($question->answerSet[0]->true_answer==0 && $givenAnswer[0]!=0){{'text-danger'}}@endif">False</span> @if($question->answerSet[0]->true_answer==0)| 
                                <span class="text-success"><i class="icon-checkmark4"></i> Correct</span>
                                @endif
                            </label>
                        </div>
                    @else {{-- 2 = Signle MCQ , 3 = Multiple MCQ --}}
                    @php
                        $labelClass = $question->answer_type == 2 ? 'radio' : 'checkbox';
                        $labelType = $question->answer_type == 2 ? 'radio' : 'checkbox';
                    @endphp
                        @foreach($question->answerSet as $answer)
                        <div class="{{$labelClass}}">
                            <label class="@if(in_array($answer->id,$givenAnswer))@if($answer->true_answer==1){{'text-success'}}@else($answer->true_answer==1){{'text-danger'}}@endif @endif" for="ans-{{$answer->id}}">
                                <input type="{{$labelType}}" name="answer_{{$question->question_id}}" id="ans-{{$answer->id}}" disabled @if(in_array($answer->id,$givenAnswer)){{'checked='}}@endif class="styled">
                                <span class="@if($answer->true_answer==1 && !in_array($answer->id,$givenAnswer)){{'text-danger'}}@endif">@php echo $answer->answer; @endphp</span>@if($answer->true_answer==1) | 
                                <span class="text-success"><i class="icon-checkmark4"></i> Correct</span>@endif</label>
                        </div>
                        @endforeach
                    @endif
                </div>
                <legend class="text-bold"></legend>
            </div>
        </div>
        @endforeach
    </div>
</div>
<!-- /content area -->