<form class="form-horizontal form-validate-jquery" action="{{route('provider.remarkUpdateAction', [$student_id, $batch_id])}}" method="POST">
@csrf
<div class="panel panel-flat">
    <div class="panel-body" id="modal-container">
        <div class="form-group">
            <label class="control-label col-lg-12">Comment <span class="label label-success">{{@$remark_info->updated_at}}</span></label>
            <div class="col-lg-12">
                <input type="text" name="remark" class="form-control" placeholder="Remark" value="{{@$remark_info->remark}}">
            </div>
        </div>
    </div>
</div>
</form>
