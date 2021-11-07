<form class="form-horizontal form-validate-jquery" action="{{route('provider.assignCaptain', [$batch_id] )}}" method="POST">
@csrf
<div class="panel panel-flat">
    <div class="panel-body" id="modal-container">
        <select data-placeholder="Select Captain..." multiple="multiple" name="captains[]"  id="select_captain" class="select" required="">
                @if(count($selected_captain) > 0)
                    @foreach($batch_students as $key => $student)
                        <option value="{{$student->id}}" @if(in_array($student->id, $selected_captain)) selected @endif>{{$student->name}} ({{$student->email}})</option>
                    @endforeach
                @else 
                    @foreach($batch_students as $key => $student)
                        <option value="{{$student->id}}">{{$student->name}} ({{$student->email}})</option>
                    @endforeach
                @endif
        </select>
    </div>
</div>
</form>
<script type="text/javascript">
	$("#select_captain").select2({ dropdownParent: "#modal-container" });
</script>
