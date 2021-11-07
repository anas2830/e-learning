<form class="form-horizontal form-validate-jquery" action="{{route('submitGroupStudyAttendence')}}" method="POST">
    @csrf
    <fieldset class="content-group">

        <!-- Basic text input -->
        <input type="hidden" name="batch_id" value="{{ $student_course_info->batch_id }}">
        <input type="hidden" name="course_id" value="{{ $student_course_info->course_id }}">
        <input type="hidden" name="assign_batch_class_id" value="{{ $assign_batch_class_id }}">
        <div class="table-responsive" style="overflow-x:auto; max-height: 500px;">
            <table class="table table-bordered table-framed">
                <thead>
                    <tr>
                        <th width="10%">SL.</th>
                        <th width="40%">Student Name</th>
                        <th width="30%">Remark</th>
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
                                        <input type="checkbox" class="inputCheckbox" name="student_id[{{$student->user_id}}]" value="{{$student->user_id}}" @if($student->is_attend == 1) checked @endif @if(!empty($done_attendence)) disabled @endif>
                                            [{{$student->gen_student_id}}] {{$student->name}}
                                    </label>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="remark[{{$student->user_id}}]" value="{{ $student->remark  }}" class="form-control" placeholder="remark" @if(!empty($done_attendence)) readonly @endif>
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

</form>
      

<script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif


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

