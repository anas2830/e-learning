<form class="form-horizontal form-validate-jquery" action="{{route('teacher.assignedBatchClass.store', ['live_schedule_id' => @$liveClass_Schedule->id])}}" method="POST">
    @csrf
    <div class="panel panel-flat">
        <div class="panel-body" id="modal-container">
            <fieldset class="content-group">
            <!-- Basic text input -->
            <input type="hidden" name="assign_batch_classs_id" value="{{$assign_batch_class_id}}" />

            <div class="form-group">
                <label class="control-label col-lg-2">Account <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                    <input type="hidden" class="form-control" name="zoom_acc_id" required="" value="{{@$zoom_account_info->id}}">
                    <input type="email" class="form-control" value="{{@$zoom_account_info->email}}" disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">Start Date <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                    <input type="date" class="form-control" name="start_date" required="" value="{{@$liveClass_Schedule->start_date}}">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-2">Start Time <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                    <input type="time" class="form-control time_picker" name="start_time" required="" value="{{@$liveClass_Schedule->start_time}}">
                </div>
            </div>
        
            <div class="form-group">
                <label class="col-lg-2 col-md-2 control-label required">Duration</label>
                <div class="col-lg-3 col-md-3">
                    <input required="" type="number" name="d_hour" placeholder="e.g. 3" id="number" class="form-control duration_hour" min="0" data-fv-field="d_hour" value="{{@$liveClass_Schedule->hour}}">
                </div>
                <div class="col-lg-1 col-md-1" style="margin-top: 5px;">Hour</div>

                <div class="col-lg-3 col-md-3">
                    <select class="select2 select-search" name="d_min" required="" id="duration_minit">
                        <option value="0" @if(@$liveClass_Schedule->min == 0) Selected @endif>0</option>
                        <option value="15" @if(@$liveClass_Schedule->min == 15) Selected @endif>15</option>
                        <option value="30" @if(@$liveClass_Schedule->min == 30) Selected @endif>30</option>
                        <option value="45" @if(@$liveClass_Schedule->min == 45) Selected @endif>45</option>
                    </select>
                </div>
                <div class="col-lg-1 col-md-1" style="margin-top: 5px;">Minute</div>
            </div>
            </fieldset>

        </div>
    </div>
</form>
<script type="text/javascript">
    $("#duration_minit").select2({ dropdownParent: "#modal-container" });
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif

        $('.time_picker').datetimepicker({
			format: 'LT'
		});
    })
</script>

