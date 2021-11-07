@extends('layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('todayGoal')}}">Today Goal</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    @if ($hasAnyImprovements)
        @if (count($need_improve_quiz_assign_class_ids) > 0)
        <div class="row">
            <div class="panel panel-body">
                <div class="media">
                    <div class="media-body text-center">
                        <h2 class="media-heading text-semibold">Give this Exam Again for Improve Your Score! </h2>
                        <h4>Reminder: Your Improve Exam Mark will be deduct 10% </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($need_improve_quiz_assign_class_ids as $class_id)
                <div class="col-md-3">
                    <div class="panel panel-body border-top-primary text-center">
                        <h6 class="no-margin text-semibold">Quiz of: {{Helper::getClassName($class_id)}}</h6>
                        <p class="content-group-sm text-muted"></p>
                        <a href="{{route('class', ['class_id' => $class_id, '#quiz'] )}}">
                            <button type="button" class="btn bg-teal-400" id="spinner-dark"><i class="icon-undo2 position-left"></i> Go To Quiz</button>
                        </a>
                    </div>
                </div>
            @endforeach
            @endif
        </div>
    <hr>
    @if (count($need_improve_assignment_assign_class_ids) > 0)
    <div class="row">
        <div class="panel panel-body">
            <div class="media">
                <div class="media-body text-center">
                    <h2 class="media-heading text-semibold">Give this Assignment Again for Improve Your Score! </h2>
                    <h4>Reminder: Your Improve Assignment Mark will be deduct 10% </h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    @foreach ($need_improve_assignment_assign_class_ids as $class_id)
        <div class="col-md-3">
            <div class="panel panel-body border-top-primary text-center">
                <h6 class="no-margin text-semibold">Assignment of: {{Helper::getClassName($class_id)}}</h6>
                <p class="content-group-sm text-muted"></p>
                <a href="{{route('class', ['class_id' => $class_id, '#assignments'] )}}">
                    <button type="button" class="btn bg-brown" id="spinner-dark"><i class="icon-undo2 position-left"></i> Go To Assignment</button>
                </a>
            </div>
        </div>
    @endforeach
    @endif
    </div>
    @else
    <div class="row">
        <div class="panel panel-body">
            <div class="media">
                <div class="media-body text-center">
                    <h2 class="media-heading text-semibold">Well done! You have not any Improvement, It'll be applied if your main score have under 60%</h2>
                </div>
            </div>
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
@endsection

@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
        });
    </script>
@endpush