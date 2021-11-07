<select class="select-search" name="student_id" id="student_id">
    <option value="0">All Students</option>
    @foreach ($students as $key => $student)
    <option value="{{$student->id}}">{{$student->name}} [{{$student->email}}]</option>
    @endforeach
</select>
