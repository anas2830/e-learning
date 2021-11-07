<form class="form-horizontal form-validate-jquery" action="{{route('provider.stdClassMarkAction', ['assign_batch_std_id' => $assign_batch_std_id, 'assign_batch_class_id' => $assign_batch_class_id, 'type' => $type])}}" method="POST">
@csrf
    <div class="panel panel-flat">
        <table class="table table-hover">
            <tbody>
              <tr>
                <td>Batch</td>
                <td>:</td>
                <td>{{Helper::batchName($assign_batch_student_info->batch_id)}}</td>
              </tr>
              <tr>
                <td>Class</td>
                <td>:</td>
                <td>{{Helper::getClassName($assign_batch_class_id)}}</td>
              </tr>
              <tr>
                <td>Student</td>
                <td>:</td>
                <td>{{Helper::studentInfo($assign_batch_student_info->student_id)->name}}</td>
              </tr>
            </tbody>
        </table>
        <div class="panel-body" id="modal-container">
            <div class="form-group">
              <label class="control-label col-lg-12"> @if ($type == 1) 
                Attendance/Class Mark <span style="color: red;">Give 0 for Absent him in this class</span>
                @else 
                Assignment Mark
                @endif
              </label>
              <div class="col-lg-12">
                  <input type="hidden" name="primary_key" @if ($type == 1) value="{{@$student_class_info->id}}" @else value="{{@$student_class_info->id}}" @endif>
                  <input type="text" name="mark" class="form-control" placeholder="Mark" @if ($type == 1) value="{{@$student_class_info->mark}}" @else value="{{@$student_class_info->mark}}" @endif maxlength="3" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
              </div>
          </div>
        </div>
    </div>
</form>

