@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.stdPayment')}}">Payment</a></li>
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
            <h5 class="panel-title">Student List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
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
        @if (session('danger'))
            
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-styled-left alert-bordered">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                <span class="text-semibold">Opps!</span> {{ $error }}.
            </div>
            @endforeach
        @endif

        <table class="table table-bordered table-hover datatable-highlight data-list" id="studentPaymentTable">
            <thead>
                <tr>
                    <th width="3%">SL.</th>
                    <th width="15%">Std Name</th>
                    <th width="10%">Phone</th>
                    <th width="10%">Batch</th>
                    <th width="7%">Freez</th>
                    <th width="7%">Pay Type</th>
                    <th width="8%">Paid Status</th>
                    <th width="10%">Due Date</th>
                    <th width="10%">Gateway</th>
                    <th width="10%">Upcomming Payment</th>
                    <th width="10%" class="text-center">Freez Action</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($batch_student_list))
                    @foreach ($batch_student_list as $key => $studentData)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$studentData->name}}</td>
                        <td>{{$studentData->phone}}</td>
                        <td>{{$studentData->batch_no}}</td>
                        <td>
                            @if ($studentData->is_freez == 1)
                                <span class="label label-danger">Freezed</span>
                            @else
                                <span class="label label-success">No</span>
                            @endif
                        </td>
                        <td>
                            @if ($studentData->payment_system_id == 1)
                                Full
                            @elseif($studentData->payment_system_id == 2)
                                Installment
                            @elseif($studentData->payment_system_id == 3)
                                Monthly
                            @else 
                                N/A
                            @endif
                        </td>
                        <td>
                            @if (!empty($studentData->runningPayment) && $studentData->runningPayment->is_running == 1)
                                Done
                            @elseif(!empty($studentData->runningPayment) && $studentData->runningPayment->is_running != 1)
                                Due
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if (!empty($studentData->runningPayment) && isset($studentData->runningPayment->payment_date))
                                {{ date("jS F, Y", strtotime($studentData->runningPayment->end_date)) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if (!empty($studentData->runningPayment) && isset($studentData->runningPayment->payment_date))
                                @if ($studentData->runningPayment->paid_from == 1)
                                    SSL 
                                @else
                                    Offline
                                @endif
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{route('provider.stdPaymentHistory', ['assign_batch_std_id'=> $studentData->id])}}" class="btn btn-primary btn-sm">Payment List <i class="icon-circle-right2 position-right"></i></a>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-info btn-xs open-modal" modal-title="Update Payment" modal-type="update" modal-size="medium" modal-class="" selector="Assign" modal-link="{{route('provider.updatePayment', ['assign_batch_std_id' => $studentData->id])}}"> Generate Payment </button>
                            <button type="button" class="btn btn-danger btn-xs open-modal mt-5" modal-title="Course Account Freez" modal-type="update" modal-size="large" modal-class="" selector="AccountFreez" modal-link="{{route('provider.courseFreez', ['assign_batch_std_id' => $studentData->id])}}">Account Freez</button>
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="10">No Data Found!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">DevsSquad IT Solutions</a>
    </div>

    <!-- /footer -->
</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    var table = $('#studentPaymentTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 9 },
            ]
    });


    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif

        $("#studentPaymentTable thead th").each( function (i) {
            if ($(this).text() === 'Pay Type' || $(this).text() === 'Paid Status') {
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
