<form class="form-horizontal form-validate-jquery" action="{{route('provider.stdSuccessStoryApprovalAction', ['story_id' => $std_success_story_info->id])}}" method="POST">
@csrf
    <div class="panel panel-flat">
          <table class="table table-hover">
            <tbody>
              <tr>
                <td>Screenshort</td>
                <td>:</td>
                <td><img src="{{asset('uploads/studentStory/usedImg/'.$std_success_story_info->work_screenshort)}}" alt="" height="300" width="450"></td>
              </tr>
              <tr>
                <td>Student</td>
                <td>:</td>
                <td>{{Helper::studentName($std_success_story_info->created_by)}}</td>
              </tr>
              <tr>
                <td>Course</td>
                <td>:</td>
                <td>{{Helper::courseName($std_success_story_info->course_id)}}</td>
              </tr>
              <tr>
                <td>Batch</td>
                <td>:</td>
                <td>{{Helper::batchName($std_success_story_info->batch_id)}}</td>
              </tr>
              <tr>
                <td>Amount</td>
                <td>:</td>
                <td>{{$std_success_story_info->work_amount}}</td>
              </tr>
              <tr>
                <td>Comment</td>
                <td>:</td>
                <td>{{$std_success_story_info->own_comment}}</td>
              </tr>
            </tbody>
        </table>
        <div class="panel-body" id="modal-container">
            <div class="form-group">
              <select class="select2 select-search col-lg-12" id="approve_status" name="approve_status" required="">
                  <option value="">Select Status</option>
                  <option value="1" @if($std_success_story_info->approve_status == 1) selected @endif>Approved</option>
                  <option value="0" @if($std_success_story_info->approve_status == 0) selected @endif>Pending</option>
              </select>
          </div>
        </div>
    </div>
</form>
<script type="text/javascript">
	$("#approve_status").select2({ dropdownParent: "#modal-container" });
</script>

