<!-- Content area -->
<div class="content">

    <!-- Form validation -->
    <div class="panel panel-flat">

        <div class="panel-body">
            <div class="table-responsive" style="overflow-x:auto; max-height: 500px;">
                <table class="table table-bordered table-framed">
                    <thead>
                        <tr>
                            <th width="10%">SL.</th>
                            <th width="30%">Student Name</th>
                            <th width="20%">Phone</th>
                            <th width="20%">Mark</th>
                            <th width="20%">Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($attendenceLists))
                        @foreach ($attendenceLists as $key => $student)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" @if($student->is_attend == 1) checked @endif disabled>
                                            [{{$student->student_id}}] {{$student->name}}
                                        </label>
                                    </div>
                                </td>
                                <td> {{$student->phone}} </td>
                                <td> {{$student->mark}} </td>
                                <td> {{$student->remark}} </td>
                            </tr>
                        @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="3">No Data Found!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /form validation -->



</div>


