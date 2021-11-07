<form class="form-horizontal form-validate-jquery" action="{{route('submitAssignmentComplain')}}" method="POST">
    @csrf
    <fieldset class="content-group">
        <!-- Basic text input -->
        <input type="hidden" name="submission_id" value="{{ $submission_id }}">
        <div class="form-group">
            <label class="control-label col-lg-2">Complain<span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <textarea name="assignment_complain" class="form-control" rows="2" cols="3" required="required">{{ @$complain_info->complain }}</textarea>
            </div>
        </div>
        <!-- /basic text input -->

    </fieldset>

</form>
      

<script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif
    });
</script>

