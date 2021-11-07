<form class="form-horizontal form-validate-jquery" action="{{route('provider.courseFreezAction', ['assign_batch_std_id' => $assign_batch_std_id])}}" method="POST">
    @csrf
    <div class="panel panel-flat">
        <div class="panel-body" id="modal-container">
            <div class="form-group">
                <label class="control-label col-lg-3">Select Status</label>
                <div class="col-lg-9">
                    <select class="select2 select-search" id="is_freez" name="is_freez" required>
                        <option value="">Select Status</option>
                        <option value="0" @if($assign_batch_std_info->is_freez == 0) selected @endif>Active</option>
                        <option value="1" @if($assign_batch_std_info->is_freez == 1) selected @endif>Freez</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">Why Freez?</label>
                <div class="col-lg-9">
                    <textarea class="form-control" name="freez_reason" id="freez_reason" cols="3" rows="3">{{ $assign_batch_std_info->freez_reason }}</textarea>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $("#is_freez").select2({ dropdownParent: "#modal-container" });
    $("#freez_reason").summernote({
        height: 150
    });
</script>
    