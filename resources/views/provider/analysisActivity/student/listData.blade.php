@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.analysisBatchStudents')}}">Student</a></li>
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
            <h5 class="panel-title">Student List ( Student Performance Average Value )</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.analysisActivity')}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        @if (session('msgType'))
            @if(session('msgType') == 'danger')
                <div id="msgDiv" class="alert alert-danger alert-styled-left alert-arrow-left alert-bordered">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                    <span class="text-semibold">{{ session('msgType') }}!</span> {{ session('messege') }}
                </div>
            @else
            <div id="msgDiv" class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">{{ session('msgType') }}!</span> {{ session('messege') }}
            </div>
            @endif
        @endif
        
        @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-styled-left alert-bordered">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">Opps!</span> {{ $error }}.
            </div>
            @endforeach
        @endif

        {{-- <table class="table table-bordered data-list datatable-column-search-selects" id="userTable"> --}}
        <table class="table table-bordered data-list datatable-column-search-selects" id="userTable">
            <thead>
                <tr>
                    <th width="8%">Student Name</th>
                    <th width="8%">Phone</th>
                    <th width="10%">Attendence</th>
                    <th width="8%">L.C.A</th>
                    <th width="8%">L.M.C</th>
                    <th width="8%">Assignment</th>
                    <th width="8%">T Mark</th>
                    <th width="10%">Practice</th>
                    <th width="8%">Watch T</th>
                    <th width="8%">Quiz</th>
                    <th width="8%">Main</th>
                    <th width="8%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($all_students))
                    @foreach ($all_students as $key => $student)
                    <tr>
                        <td>{{ $student->name }} ({{$student->student_id}})</td>
                        <td>{{ $student->phone }}</td>
                        <td>{{ $student->attend }}/{{ $student->total_attendence }}</td>
                        <td>{{ $student->last_class_attend }}</td>
                        <td>{{ $student->total_last_missing_class }}</td>
                        <td>{{ $student->assignment }}</td>
                        <td>{{ $student->class_mark }}</td>
                        <td>{{ $student->practice_time }}</td>
                        <td>{{ $student->video_watch_time }}</td>
                        <td>{{ $student->quiz }}</td>
                        <td>{{ $student->main_score }}</td>
                        <td>
                            @if ($student->active_status == 1)
                                {{-- <span class="label label-success">Active</span> --}}
                                <button data="{{$student->id}}" class="user-login btn btn-primary btn-xs mt-5" type="button">Login</button>
                            @else 
                                <span class="label label-danger">InActive</span>
                            @endif
                            <button type="button" class="btn btn-danger btn-sm open-modal mt-5" modal-title="Comment Update" modal-type="update" modal-size="medium" modal-class="" selector="remarkUpdate" modal-link="{{route('provider.remarkUpdate', [$student->id, $batch_id])}}">
                                Comment
                            </button>
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="11">No Data Found!</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th width="8%"></th>
                    <th width="8%"></th>
                    <th width="10%"></th>
                    <th width="8%">L.C.A</th>
                    <th width="8%">L.M.C</th>
                    <th width="8%"></th>
                    <th width="10%"></th>
                    <th width="10%"></th>
                    <th width="8%"></th>
                    <th width="8%"></th>
                    <th width="8%"></th>
                    <th width="8%"></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- /highlighting rows and columns -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    // $('#userTable').DataTable();
    var table = $('#userTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 8 }
            ]
    });
    $(document).ready(function() {

        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif

        // var table = $('#userTable').DataTable({
        //     dom: 'lBfrtip',
        //         "iDisplayLength": 10,
        //         "lengthMenu": [ 10, 25,30, 50 ],
        //         columnDefs: [
        //             {'orderable':false, "targets": 8 }
        //         ]
        // });
            
        $("#userTable tfoot th").each( function ( i ) {
		
            if ($(this).text() !== '') {

                var select = $('<select class="filter-select" data-placeholder="Filter"><option value=""></option></select>')
                    .appendTo( $(this).empty() )
                    .on( 'change', function () {
                        var val = $(this).val();
                        
                    table.column( i )
                        .search( val ? '^'+$(this).val()+'$' : val, true, false )
                        .draw();
                } );
 
            
                table.column( i ).data().unique().sort().each( function ( d, j ) {  
                    select.append( '<option value="'+d+'">'+d+'</option>' );
                } );	

            }
        } );

        $('.filter-select').select2({
            width: '100%'
        });


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
