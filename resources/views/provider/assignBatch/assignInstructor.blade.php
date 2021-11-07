<form class="form-horizontal form-validate-jquery" action="{{route('provider.assignInstructor', [$batch_id] )}}" method="POST">
@csrf
<div class="panel panel-flat">
    <div class="panel-body" id="modal-container">
        <div class="form-group">
            <label class="control-label col-lg-12">Fb Messenger Link <span class="text-danger">*</span></label>
            <div class="col-lg-12">
                <input type="text" name="instructor_chat_link" class="form-control" placeholder="Link" required="required">
            </div>
        </div>
    </div>
</div>
</form>
