@extends('layouts.masterDefault')

@push('styles')
<link href="{{ asset('web_graph/line_chart/css/lineStyle.css') }}" rel="stylesheet" type="text/css"/>
@endpush
@section('content')

<!-- Content area -->
<div class="content">
    <div class="row">
        @foreach($all_courses as $key => $course)
            <div class="col-md-4">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">{{ $course->courseInfo->course_name }}</h6>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                <li><a data-action="reload"></a></li>
                                <li><a data-action="close"></a></li>
                            </ul>
                        </div>
                    <a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

                    <div class="panel-body">
                        <p class="mb-15">{!! $course->courseInfo->course_overview !!}</p>

                        <div class="thumbnail" style="height:300px; width:400px">
                            <div class="thumb">
                                <img src="{{ asset('uploads/course/thumb/'.$course->courseInfo->course_thumb) }}" alt="course_img" style="width:400px;height:300px">
                                <div class="caption-overflow caption-zoom">
                                    <span>
                                        <button class="btn btn bg-warning-300 btn-icon couseLinkBtn"  data-id={{$course->courseInfo->id}}><i class="icon-link"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
 
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

    $('.couseLinkBtn').on('click', function(e){
        e.preventDefault;
        var course_id = $(this).attr('data-id');
        $.ajax({ 
            url : "{{route('updateRunningCourse')}}",
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            data: {"course_id":course_id},
            type: 'GET',
            success: function(response)
            {   
                window.location.href = "{{ route('home')}}";
            }
        });
    });

</script>

@endpush

