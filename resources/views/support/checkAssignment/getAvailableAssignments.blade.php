<select data-placeholder="Select Students..." multiple="multiple" class="select-search" name="std_assignment_submission_ids[]" id="std_assignment_submission_ids">
    <option value="">Select Student</option>
    @foreach($available_submitted_assignments as $assignment)
    <option value="{{$assignment->id}}">{{$assignment->student_name}} ({{Helper::getClassName($assignment->assign_batch_class_id)}})</option>
    @endforeach
</select>