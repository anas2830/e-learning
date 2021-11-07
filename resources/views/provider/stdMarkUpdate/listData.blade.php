@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.powerMenuStdList')}}">Power Menu</a></li>
            <li class="active">Student List</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Power Menu Student List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight data-list" id="studentTable">
            <thead>
                <tr>
                    <th width="3%">SL.</th>
                    <th width="37%">Batch</th>
                    <th width="30%">Std Name</th>
                    <th width="15%">Phone</th>
                    <th width="15%" class="text-center">Freez Action</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($assign_students))
                    @foreach ($assign_students as $key => $studentData)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$studentData->batch_no}}</td>
                        <td>{{$studentData->name}}</td>
                        <td>{{$studentData->phone}}</td>
                        <td class="text-center">
                            <a href="{{route('provider.powerMenuStdClassList', ['assign_batch_std_id'=> $studentData->id])}}" class="btn btn-primary btn-sm">Class List <i class="icon-circle-right2 position-right"></i></a>
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No Data Found!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    var table = $('#studentTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 4 },
            ]
    });


    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif

        $("#studentTable thead th").each( function (i) {
            if ($(this).text() === 'Batch') {
                var select = $('<select class="filter-select" data-placeholder="Filter"><option value=""></option></select>')
                    .appendTo( $(this).empty())
                    .on('change', function () {
                        var val = $(this).val();
                        
                        table.column(i)
                            .search( val ? '^'+$(this).val()+'$' : val, true, false )
                            .draw();
                    });

                table.column(i).data().unique().sort().each( function ( d, j ) {  
                    select.append( '<option value="'+d+'">'+d+'</option>' );
                });	
            }
        });

        $('.filter-select').select2({
            width: '100%'
        });
    });
</script>
@endpush
