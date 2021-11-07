@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.batchstuClassList', [$assign_class_id])}}">Batch Student List</a></li>
            <li class="active">Create</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- Form validation -->
    <div class="panel panel-flat">
        <div class="panel-heading" style="border-bottom: 1px solid #ddd; margin-bottom: 20px;">
            <h5 class="panel-title">{{$course_name}}({{$assignBatchClassInfo->batch_no}}) {{$assignBatchClassInfo->class_name}} Class Attendence</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('teacher.classExamConfig', ['assign_batch_class_id'=> $assign_class_id])}}" method="POST">
                @csrf
                <fieldset class="content-group">
                    @if (session('msgType'))
                        <div id="msgDiv" class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            <span class="text-semibold">{{ session('msgType') }}!</span> {{ session('messege') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-styled-left alert-bordered">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            <span class="text-semibold">Opps!</span> {{ $error }}.
                        </div>
                        @endforeach
                    @endif

                    <!-- Basic text input -->
                    <div class="form-group">
                        <label class="control-label col-lg-2">Details <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <textarea id="overviewDetails" name="exam_overview" class="form-control" required>{{@$examConfig->exam_overview}}
                            </textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Duration <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-watch2"></i></span>
                                <input type="number" name="exam_duration" value="{{@$examConfig->exam_duration}}" placeholder="Ex: 30" class="form-control" data-fv-icon="false" data-fv-greaterthan="true" data-fv-greaterthan-value="1" required>
                                <span class="input-group-addon">Minutes</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">Per Question Mark <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="number" name="per_question_mark" value="{{@$examConfig->per_question_mark}}" placeholder="Ex: 5" class="form-control" data-fv-icon="false" data-fv-greaterthan="true" data-fv-greaterthan-value="1" required>
                                <span class="input-group-addon">Mark</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" style="overflow-x:auto; max-height: 500px;">
                        <table class="table table-bordered table-framed">
                            <thead>
                                <tr>
                                    <th width="10%">SL.</th>
                                    <th width="90%">Question</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($questions) > 0)
                                    @if (!empty($examConfig->questions))
                                        @php $config_questions = json_decode(@$examConfig->questions); @endphp
                                    @endif
                                    @foreach ($questions as $key => $question)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="question_id[]" value="{{$question->id}}"
                                                        @if (!empty($examConfig->questions))
                                                            @if(in_array($question->id, $config_questions)) checked @endif
                                                        @endif
                                                        >
                                                        {!! $question->question !!}
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="2">No Questions Found!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /basic text input -->

                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary submintBtn">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <a href="{{route('teacher.classExamBatchClassList', [$assignBatchClassInfo->batch_id])}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
                </div>
            </form>
        </div>
    </div>
    <!-- /form validation -->


    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">DevsSquad IT Solutions</a>
    </div>
    <!-- /footer -->

</div>
<!-- /content area -->
@endsection

@push('javascript')


<script type="text/javascript">
    $(document).ready(function (e) {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif

        $("#overviewDetails").summernote({
            height: 150
        });
    })
</script>
@endpush
