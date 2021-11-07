@if ($updateAccess)
    <form class="form-horizontal form-validate-jquery" action="{{route('teacher.updateAssignmentMark', ['complain_id'=> $complain_id])}}" method="POST">
        @csrf
        <fieldset class="content-group">
            <!-- Basic text input -->
            <input type="hidden" name="submission_id" value="{{ $submission_info->id }}">
            <div class="form-group">
                <label class="control-label col-lg-3">Reviewer Mark<span class="text-danger"></span></label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" value="{{ $submission_info->mark }}" name="mark">
                </div>
            </div>
            <!-- /basic text input -->
        </fieldset>
    </form>
@else
    <h4>You Have No Access for Update Mark</h4>
@endif
      

<script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif
    });
</script>