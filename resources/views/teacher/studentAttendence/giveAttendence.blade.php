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
            <h5 class="panel-title">{{$course_info->course_name}}({{$batch_no}}) {{$assignBatchClassInfo->class_name}} Class Attendence</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('teacher.batchstuSaveAttendence')}}" method="POST">
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
                    <input type="hidden" name="batch_id" value="{{$assignBatchClassInfo->batch_id}}">
                    <input type="hidden" name="course_id" value="{{$course_info->id}}">
                    <input type="hidden" name="class_id" value="{{$assign_class_id}}">
                    <div class="table-responsive" style="overflow-x:auto; max-height: 500px;">
                        <table class="table table-bordered table-framed">
                            <thead>
                                <tr>
                                    <th width="10%">SL.</th>
                                    <th width="55%">Student Name</th>
                                    <th width="15%">Performance Mark</th>
                                    <th width="20%">Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($assign_students))
                                @foreach ($assign_students as $key => $student)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="inputCheckbox" name="student_id[{{$student->user_id}}]" value="{{$student->user_id}}">
                                                    [{{$student->gen_student_id}}] {{$student->name}}
                                                    @if(isset($previous_studence_attendence[$student->user_id])) 
                                                    @if($previous_studence_attendence[$student->user_id]['attendence'] != 1)
                                                        <span style="color: red">[Absent P.C]</span>
                                                    @endif
                                                    @if($previous_studence_attendence[$student->user_id]['watchVideo'])
                                                        <span style="color: red">[0 Video Watch P.C]</span>
                                                    @endif
                                                    @if($previous_studence_attendence[$student->user_id]['practiceTime'])
                                                        <span style="color: red">[0 Practice P.C]</span>
                                                    @endif
                                                    @if($previous_studence_attendence[$student->user_id]['quiz'])
                                                        <span style="color: red">[No Quiz P.C]</span>
                                                    @endif
                                                    @if($previous_studence_attendence[$student->user_id]['assignment'])
                                                        <span style="color: red">[No Assignment Submitted P.C]</span>
                                                    @endif
                                                    @if($previous_studence_attendence[$student->user_id]['groupStudy'])
                                                        <span style="color: red">[Don't attend G.S on P.C]</span>
                                                    @endif
                                                
                                                @endif
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="icon-check"></i></span>
                                                {{-- <input type="text" class="form-control" placeholder="0" name="mark[{{$key}}]"> --}}
                                                <input type="text" name="mark[{{$student->user_id}}]" maxlength="3" class="form-control classMark" placeholder="0" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="remark[{{$student->user_id}}]" class="form-control" placeholder="remark">
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="3">No Data Found!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /basic text input -->

                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary submintBtn">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="reset" class="btn btn-default" id="reset">Reset <i class="icon-reload-alt position-right"></i></button>
                    <a href="{{route('teacher.batchstuClassList', [$assignBatchClassInfo->batch_id])}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
                </div>
            </form>
        </div>
    </div>
    <!-- /form validation -->


    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">Rafikul Islam</a>
    </div>
    <!-- /footer -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif

        window.onbeforeunload = function (e) {
            e = e || window.event;
            if (e) {
                e.returnValue = 'Sure?';
            }
            return 'Sure?';
        };

        $('.inputCheckbox').on('click', function(e){
            if($(this).prop("checked") == true){
                $(this).closest('td').next('td').find('.classMark').prop('required',true);
            }
            else if($(this).prop("checked") == false){
                $(this).closest('td').next('td').find('.classMark').prop('required',false);
            }
            
        });
        
    })
</script>
@endpush
