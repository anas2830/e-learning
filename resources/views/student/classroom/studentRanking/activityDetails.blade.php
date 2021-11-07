<div class="panel panel-flat">
    <div class="panel-body">
        <table class="table table-bordered table-hover datatable-highlight" id="courseTable">
            <thead>
                <tr>
		  	    	<th width="70%">Activity Topic</th>
		  	 		<th width="30%">Progress In(%)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Video Watch Time</td>
                    <td>{{ $running_watch_time }}</td>
                </tr>
                <tr>
                    <td>Practice Time</td>
                    <td>{{ $running_practice_time }}</td>
                </tr>
                <tr>
                    <td>Quiz Mark</td>
                    <td>{{ $running_exam_result }}</td>
                </tr>
                <tr>
                    <td>Class Assignment</td>
                    <td>{{ $running_assignment_mark }}</td>
                </tr>
                <tr>
                    <td>Class Attendence</td>
                    <td>{{ $running_class_attendance_mark }}</td>
                </tr>
                <tr>
                    <td>Class Performance Mark</td>
                    <td>{{ $running_class_perform_mark }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
