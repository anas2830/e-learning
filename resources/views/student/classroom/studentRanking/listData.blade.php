@extends('layouts.default')
@push('styles')
    <style>
        td img {
            width: 80px;
            height: 80px;
        }
        .lfwf-batch-captain{}
        .lfwf-batch-captain td:nth-child(2){
            position: relative;
        }
        .lfwf-batch-captain .captain-ribbon{
            position: absolute;
            top: -6.1px;
            right: 10px;
        }
        .lfwf-batch-captain .captain-ribbon::after{
            position: absolute;
            content: "";
            width: 0;
            height: 0;
            border-left: 53px solid transparent;
            border-right: 53px solid transparent;
            border-top: 10px solid #2c8f9f;
        }
        .lfwf-batch-captain .captain-ribbon span{
            position: relative;
            display: block;
            text-align: center;
            background: #2c8f9f;
            font-size: 14px;
            line-height: 1;
            padding: 14px 8px 10px;
            border-top-right-radius: 8px;
            width: 115px;
            color: #fff;
        }
        .lfwf-batch-captain .captain-ribbon span::before {
            position: absolute;
            content: "";
            height: 6px;
            width: 6px;
            left: -6px;
            top: 0;
            background: #2c8f9f;
        }
        .lfwf-batch-captain .captain-ribbon span::after {
            position: absolute;
            content: "";
            height: 6px;
            width: 8px;
            left: -8px;
            top: 0;
            border-radius: 8px 8px 0 0;
            background: #1d5963;
        }
    </style>
@endpush
@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('studentRanking')}}">My Batch Students Ranking</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <!-- Data Table -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Students Ranking List</h5>
            <div class="heading-elements">
                <ul class="icons-list data-list" style="margin-top: 0px">
                    @isset($batch_instructor_link)
                    <li>
                        <a href="{{$batch_instructor_link}}" target="_blank" class="btn btn-primary btn-xs add-new" style="background-color: #a430f6;">Instructor <i class="icon-new-tab position-right"></i></a>
                    </li>
                    @endisset
                    <li>
                        <a href="{{route('availableAssignment')}}" class="btn btn-primary btn-xs add-new">Available Assignment({{$assignment_submission_data}}) <i class="icon-circle-right2 position-right"></i></a>
                    </li>
                    @if($access_gs)
                    <li>
                        @if($done_attendence > 0)
                            <button type="button" class="btn btn-primary btn-xs open-modal" modal-title="Show Group Study for {{Helper::className($course_class_id)}}" modal-type="show" modal-size="large" modal-class="" selector="Assign" modal-link="{{route('groupStudyAttendence')}}"> Show Group Study </button>
                        @else 
                            <button type="button" class="btn btn-primary btn-xs open-modal" modal-title="Take Group Study for {{Helper::className($course_class_id)}}" modal-type="update" modal-size="large" modal-class="" selector="Assign" modal-link="{{route('groupStudyAttendence')}}"> Take Group Study </button>
                        @endif
                    </li>
                    @endif
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        @if (session('msgType'))
            <div id="msgDiv" class="alert alert-{{ session('msgType') }} alert-styled-left alert-arrow-left alert-bordered">
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

        <table class="table table-bordered table-hover datatable-highlight data-list" id="rankingTable">
            <thead>
                <tr>
                    <th width="5%">Position</th>
                    <th width="30%">Student Name</th>
                    <th width="20%">Image</th>
                    <th width="10%">Total Reviewed Assignments</th>
                    <th width="15%">Gained Score</th>
                    <th width="20%" class="text-center">Last Completed Class</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($student_ranking_list))
                    @foreach ($student_ranking_list as $key => $student)
                    <tr @if (in_array($student->student_id, $captain_ids)) class="lfwf-batch-captain" @endif>
                        <td>
                            {{++$key}}
                        </td>
                        <td>
                            {{$student->name}}
                            @if (in_array($student->student_id, $captain_ids))
                            <span class="captain-ribbon"><span> Class Captain </span></span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if (empty($student->image))
                                <img src="{{ asset('backend/assets/images/placeholder.jpg')}}" alt="Profile Img">
                            @else
                            <img src="{{ asset('uploads/studentProfile/thumb/'.$student->image)}}" alt="Profile Img">
                            @endif
                        </td>
                        <td>{{$student->total_reviewed_assignments}}</td>
                        <td>{{$student->final_mark}}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm open-modal" modal-title="{{$student->name}}'s Last Class Progress" modal-type="show" modal-size="medium" modal-class="" selector="progressDetails" modal-link="{{route('stdRankRunningProgress', ['student_id'=>$student->student_id])}}">Last Class Progress <i class="icon-play3 position-right"></i></button>
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No Data Found!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <!-- /Data Table -->

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
        $('#rankingTable').DataTable({
            dom: 'lBfrtip',
                "iDisplayLength": 10,
                "lengthMenu": [ 10, 25,30, 50 ],
                columnDefs: [
                    {'orderable':false, "targets": 4 },
                    {'orderable':false, "targets": 5 }
                ]
        });

        $(document).ready(function(){
            @if (session('msgType'))
                setTimeout(function() {$('#msgDiv').hide()}, 3000);
            @endif
        });
    </script>
@endpush