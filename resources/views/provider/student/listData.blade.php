@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.student.index')}}">Student</a></li>
            <li class="active">List Data</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- Highlighting rows and columns -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Student List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.student.create')}}" class="btn btn-primary add-new">Add New</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight data-list" id="userTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="5%">Student ID</th>
                    <th width="20%">Student Name</th>
                    <th width="20%">Email</th>
                    <th width="25%">Phone (Brilliant)</th>
                    <th width="10%">Fb Link</th>
                    <th width="10%">Status</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($all_students))
                    @foreach ($all_students as $key => $student)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$student->student_id}}</td>
                        <td>{{$student->name}} @if (isset($student->sur_name)) ({{$student->sur_name}}) @endif</td>
                        <td>{{$student->email}}</td>
                        <td>{{ $student->phone }} @if (isset($student->backup_phone)) [{{$student->backup_phone}}] @endif</td>
                        <td>
                            <a href="{{$student->fb_profile}}" target="_blank">Visit Profile</a>
                        </td>
                        <td>
                            @if ($student->active_status == 1)
                                {{-- <span class="label label-success">Active</span> --}}
                                <button data="{{$student->id}}" class="user-login btn btn-primary btn-xs mt-5" type="button">Login</button>
                            @else 
                                <span class="label label-danger">InActive</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{route('provider.student.edit', [$student->id])}}" class="action-icon"><i class="icon-pencil7"></i></a>
                            <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('provider.student.destroy', [$student->id])}}">@csrf </i></a>
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No Data Found!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <!-- /highlighting rows and columns -->

    <!-- Footer -->
    <div class="footer text-muted">
        &copy; 2015.{{date('Y')}} <a href="#">Limitless Web App Kit</a> by <a href="#" target="_blank">Anas</a>
    </div>
    <!-- /footer -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    // $('#courseTable').DataTable();
    
    $('#userTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 4 },
                {'orderable':false, "targets": 5 },
                {'orderable':false, "targets": 7 }
            ]
    });
    
    $(document).ready(function() {
        $('#userTable tbody').on('click', '.user-login', function (e) {
            e.preventDefault();
            $.ajax({
                url : '{{route("provider.traineeUserLogin")}}',
                data: {id: $(this).attr('data'), _token: "{{ csrf_token() }}"},
                type: 'GET',
                async: false,
                dataType: "json",
                success: function(data) {
                    if(data.result) {
                        window.open("{{route('home')}}");
                    } else {
                        swal("Cancelled", data.msg, "error");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal("Cancelled", errorThrown, "error");
                }
            });
        });
    });
</script>
@endpush
