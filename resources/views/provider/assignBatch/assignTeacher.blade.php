<form class="form-horizontal form-validate-jquery" action="{{route('provider.assignTeacher', [$batch_id] )}}" method="POST">
@csrf
<div class="panel panel-flat">
    <div class="panel-body" id="modal-container">
        <select class="select2 select-search col-lg-8" id="select_teacher" name="teacher_id" required="">
            <option value="">Select Teacher</option>
        	@foreach($teachers as $teacher)
                <option value="{{$teacher->id}}" @if (@$batch_info->teacher_id == $teacher->id) selected @endif>
                   {{$teacher->name}}
                </option>
        	@endforeach
        </select>
    </div>
</div>
</form>
<script type="text/javascript">
	$("#select_teacher").select2({ dropdownParent: "#modal-container" });
</script>
