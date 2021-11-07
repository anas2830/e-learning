@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Success Story</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">
    <!-- Highlighting rows and columns -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Success Story List</h5>
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
                    <th width="25%">Batch</th>
                    <th width="25%">Std Name</th>
                    <th width="15%">Phone</th>
                    <th width="10%">Amount</th>
                    <th width="7%">Status</th>
                    <th width="15%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($student_successes))
                    @foreach ($student_successes as $key => $storyData)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$storyData->batch_no}}</td>
                        <td>{{$storyData->name}}</td>
                        <td>{{$storyData->phone}}</td>
                        <td>{{$storyData->work_amount}}</td>
                        <td>
                            @if ($storyData->approve_status == 1)
                               Approved
                            @else
                                Pending
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn @if ($storyData->approve_status == 1) btn-success @else btn-warning @endif btn-sm open-modal" modal-title="Success Story Approval" modal-type="update" modal-size="large" modal-class="" selector="AprovalStatus" modal-link="{{route('provider.stdSuccessStoryApproval', ['story_id'=> $storyData->id])}}">Approval</button>
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Data Found!</td>
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
                {'orderable':false, "targets": 6 },
            ]
    });


    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif

        $("#studentTable thead th").each( function (i) {
            if ($(this).text() === 'Batch' || $(this).text() === 'Status') {
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
