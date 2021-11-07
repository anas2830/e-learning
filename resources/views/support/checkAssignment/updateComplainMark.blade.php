@if ($updateAccess)
    <div class="panel-body">
        <p>Request Note: {{$assignment_complain->complain}}</p>
        <span>Requested At: {{ date("jS F, Y", strtotime($assignment_complain->complain_date)) }}</span>
        <hr>
        <form class="form-horizontal form-validate-jquery" action="{{route('support.updateComplaintMark', ['complain_id'=> $complain_id])}}" method="POST">
            @csrf
            <fieldset class="content-group">
                <!-- Basic text input -->
                <input type="hidden" name="submission_id" value="{{ $assignment_submission_info->id }}">
                <div class="form-group">
                    <label class="control-label col-lg-3">Reviewer Mark<span class="text-danger"></span></label>
                    <div class="col-lg-9">
                        <input type="text" class="form-control" value="{{ $assignment_submission_info->mark }}" name="mark">
                    </div>
                </div>
                <!-- /basic text input -->
            </fieldset>
        </form>
    </div>
@else
    <h4>You Have No Access For Update Mark</h4>
@endif

{{-- <script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif
    });
</script> --}}