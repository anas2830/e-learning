<form class="form-horizontal form-validate-jquery" action="{{route('provider.batchCompleteAction', [$batch_id] )}}" method="POST">
@csrf
<div class="panel panel-flat">
    <div class="panel-body" id="modal-container">
        <select class="select2 select-search col-lg-8" id="batch_complete" name="complete_status" required="">
            <option value="">Select Status</option>
                <option value="1">Complete</option>
        </select>
    </div>
</div>
</form>
<script type="text/javascript">
	$("#batch_complete").select2({ dropdownParent: "#modal-container" });
</script>
