@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.sendSms.index')}}">SMS</a></li>
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
            <h5 class="panel-title">SMS Create</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" id="sendSmsForm" action="{{route('provider.sendSms.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <fieldset class="content-group">
                    @if (session('msgType'))
                        <div id="msgDiv" class="alert alert-{{session('msgType')}} alert-styled-left alert-arrow-left alert-bordered">
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

                    <div class="form-group"  id="user_type_list_div">
                        <label class="control-label col-lg-3">Select User Type <span class="text-danger">*</span></label>
                        <div class="col-lg-9" id="user_type">
                            <select class="select-search" name="user_type" id="user_type">
                                <option value="1">Student</option>
                                <option value="2">Teacher</option>
                                <option value="3">Support</option>
                            </select>
                        </div>
                    </div>

                    <div id="student_sms_div">

                        <div class="form-group"  id="student_filter_div">
                            <label class="control-label col-lg-3">Select Filter Type<span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select class="select-search" name="student_filter_type" id="student_filter_type">
                                    <option value="1">With Batch</option>
                                    <option value="2">Without Batch</option>
                                </select>
                            </div>
                        </div>
                        {{-- without batch all students --}}
                        <div id="student_without_batch_filter" style="display: none">
                            <div class="form-group"  id="student_without_batch_type_div">
                                <label class="control-label col-lg-3">Select Student Type <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select class="select-search" name="student_without_batch_type" id="student_without_batch_type">
                                        <option value="">Select</option>
                                        <option value="0">All Students</option>
                                        <option value="1">Selected Student</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" id="all_students_div">
                                <label class="control-label col-lg-3">Select students <span class="text-danger">*</span></label>
                                <div class="col-lg-9" id="student_without_batch_ids">
                                    <select data-placeholder="Select Students..." multiple="multiple" class="select-search" name="student_without_batch_ids[]">
                                        @foreach ($students_list as $key => $students)
                                            <option value="{{$students->id}}">{{$students->name}} [ {{ $students->email }}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- end without batch all students --}}

                        {{-- student sms filter with batch--}}
                        <div id="student_with_batch_filter">
                            <div class="form-group"id="course_list_div">
                                <label class="control-label col-lg-3">Select Course <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    @if(count($course_list) > 0)
                                        <select class="select-search" name="course_id" id="course_id">
                                            <option value="">Select</option>
                                            @foreach ($course_list as $key => $course)
                                            <option value="{{$course->id}}">{{$course->course_name}}</option>
                                            @endforeach
                                        </select>
                                    @else 
                                        <span class="label label-danger">No Course Available</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group" id="batch_list_div">
                                <label class="control-label col-lg-3">Select Batch <span class="text-danger">*</span></label>
                                <div class="col-lg-9" id="batch_id_view">
                                    <select class="select-search" name="batch_id" id="batch_id">
                                        <option value="">Select Batch</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"  id="student_type_div">
                                <label class="control-label col-lg-3">Select Student Type <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select class="select-search" name="student_type" id="student_type">
                                        <option value="">Select</option>
                                        <option value="0">All Students</option>
                                        <option value="1">Selected Student</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group"  id="student_list_div" style="display: none">
                                <label class="control-label col-lg-3">Selected Student <span class="text-danger">*</span></label>
                                <div class="col-lg-9" id="student_id_view">
                                    <select class="select-search" name="student_id" id="student_id">
                                        <option value="">Select Student</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- end student sms filter --}}

                    {{-- teacher sms filter --}}
                    <div class="form-group"  id="teacher_list_div" style="display: none">
                        <div class="form-group" id="teacher_type_div">
                            <label class="control-label col-lg-3">Select Type <span class="text-danger">*</span></label>
                            <div class="col-lg-9" id="teacher_id">
                                <select class="select-search" name="teacher_type" id="teacher_type">
                                    <option value="">Select</option>
                                    <option value="0">All Teacher</option>
                                    <option value="1">Selected Teacher</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="selected_teacher_div" style="display: none;">
                            <label class="control-label col-lg-3">Select Teacher <span class="text-danger">*</span></label>
                            <div class="col-lg-9" id="teacher_id">
                                <select data-placeholder="Select Teacher..." multiple="multiple" class="select-search" name="teacher_ids[]" id="select_teacher">
                                    @foreach ($teachers_list as $key => $teacher)
                                        <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- end teacher sms filter --}}


                    {{-- support sms filter --}}
                    <div id="support_list_div" style="display: none">
                        <div class="form-group">
                            <label class="control-label col-lg-3">Select Type <span class="text-danger">*</span></label>
                            <div class="col-lg-9" id="support_type_div">
                                <select class="select-search" name="support_type" id="support_type">
                                    <option value="">Select</option>
                                    <option value="0">All Support</option>
                                    <option value="1">Selected Support</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="display: none;" id="selected_support_div">
                            <label class="control-label col-lg-3">Select Support <span class="text-danger">*</span></label>
                            <div class="col-lg-9" id="support_id">
                                <select data-placeholder="Select Support..." multiple="multiple" name="support_ids[]"  id="select_support" class="select-search">
                                    @foreach ($supports_list as $key => $support)
                                        <option value="{{$support->id}}">{{$support->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- end support sms filter --}}
                    
                    <div class="form-group">
                        <label class="control-label col-lg-3">Message <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <span>use @name@ for name</span>
                            <textarea name="message" cols="5" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <!-- /basic textarea -->
                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="reset" class="btn btn-default" id="reset">Reset <i class="icon-reload-alt position-right"></i></button>
                    <a href="{{route('provider.sendSms.index')}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
                </div>
            </form>
        </div>
    </div>
    <!-- /form validation -->
</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 6000);
        @endif
        
        $("#sendSmsForm").on('change', '#user_type', function(){
            var user_type=$(this).val();
            if(user_type){
                if(user_type == 1){
                    $('#student_sms_div').show();

                    $('#teacher_list_div').hide();
                    $('#support_list_div').hide();

                }else if(user_type == 2){
                    $('#teacher_list_div').show();

                    $('#student_sms_div').hide();
                    $('#support_list_div').hide();
                }else if(user_type == 3){
                    $('#support_list_div').show();

                    $('#teacher_list_div').hide();

                    $('#student_sms_div').hide();
                }
            }
        });


        
        $("#sendSmsForm").on('change', '#student_filter_type', function(){
            var std_filter_type=$(this).val();

            if(std_filter_type){
                if(std_filter_type == 1){
                    $('#student_with_batch_filter').show();
                    $('#all_students_div').hide();
                    $("#student_without_batch_filter").hide();
                }else{
                    $('#student_with_batch_filter').hide();
                    $("#student_without_batch_filter").show();
                    $('#all_students_div').hide();
                }
            }
        });

        $("#sendSmsForm").on('change', '#student_without_batch_type', function(){
            var student_whitout_batch_type=$(this).val();

            if(student_whitout_batch_type){
                if(student_whitout_batch_type == 1){
                    $('#all_students_div').show();
                }else{
                    $('#all_students_div').hide();
                }
            }
        });

        $("#sendSmsForm").on('change', '#student_type', function(){
            var student_type=$(this).val();
            var std_filter_type = $("#student_filter_type").val();
            if(student_type){
                if(student_type == 1 && std_filter_type == 1){
                    $('#student_list_div').show();
                }else{
                    $('#student_list_div').hide();
                }
            }
        });

        $("#sendSmsForm").on('change', '#support_type', function(){
            var support_id=$(this).val();
            if(support_id){
                if(support_id == 1){
                    $('#selected_support_div').show();
                }else{
                    $('#selected_support_div').hide();
                }
            }
        });
        
        $("#sendSmsForm").on('change', '#teacher_type', function(){
            var teacher_id=$(this).val();
            if(teacher_id){
                if(teacher_id == 1){
                    $('#selected_teacher_div').show();
                }else{
                    $('#selected_teacher_div').hide();
                }
            }
        });

        $("#sendSmsForm").on('change', '#course_id', function(){
            var course_id=$(this).val();
            if(course_id){

                $.ajax({
                    url: "{{route('provider.getCourseWiseBatch')}}",
                    type: "GET",
                    data: {course_id:course_id},
                    success: function (data) {
                        $("#batch_id_view").html(data);
                        $("#batch_id").select2({placeholder:"Select Batch"});
                    }
                });
            }
        });

        $("#sendSmsForm").on('change', '#batch_id', function(){
            var batch_id=$(this).val();
            var course_id = $('#course_id').val();

            if(batch_id){
                $.ajax({
                    url: "{{route('provider.getBatchWiseStudents')}}",
                    type: "GET",
                    data: {batch_id:batch_id,course_id:course_id},
                    success: function (data) {
                        $("#student_id_view").html(data);
                        $("#student_id").select2({placeholder:"Select Student"});
                    }
                });
            }
        });

    });



</script>
@endpush
