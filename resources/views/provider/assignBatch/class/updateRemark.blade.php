<form class="form-horizontal form-validate-jquery" action="{{route('provider.batchSaveAttendenceRemark', ['attendence_id'=>$attendence_id])}}" method="POST">
@csrf
    <div class="panel panel-flat">
        <div class="panel-body" id="modal-container">
            <input type="text" class="form-control" value="{{@$attend_student->remark}}" name="remark" required>
        </div>
    </div>
</form>
