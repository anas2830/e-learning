@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.stdPaymentHistory')}}">Payment History</a></li>
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
            <h5 class="panel-title">Payment History of {{$student_name}}</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.stdPayment')}}" class="btn btn-info add-new"><i class="icon-point-left mr-10"></i>Go Back</a></li>
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

        <table class="table table-bordered table-hover datatable-highlight data-list" id="paymentHistoryTable">
            <thead>
                <tr>
                    <th width="5%">SL</th>
                    <th width="5%">Serial</th>
                    <th width="30%">Amount</th>
                    <th width="50%">Start-End Date</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($payment_histories))
                     @foreach($payment_histories as $key => $payment)
				  	 	<tr>
				  	 		<td>{{++$key}}</td>
				  	 		<td>{{$payment->serial_no}}</td>
				  	 		<td>{{$payment->amount}}</td>
				  	 		<td>{{$payment->start_date}} - {{$payment->end_date}}</td>
                            <td>
                                @if ($payment->is_running == 1)
                                    <span class="label label-success">Paid</span>
                                @elseif($payment->is_running == 2)
                                    @if ($payment->start_date <= date('Y-m-d') && $payment->end_date >= date('Y-m-d'))
                                        <button type="button" class="btn btn-info btn-sm open-modal" modal-title="Menual Payment" modal-type="update" modal-size="medium" modal-class="" selector="MenualPayment" modal-link="{{route('provider.stdPaymentManual', ['pay_history_id'=>$payment->id])}}">Pay Manual</button>
                                    @else 
                                        <span class="label label-info">Not Live Yet</span>
                                    @endif
                                @else 
                                    <span class="label label-warning">Upcomming</span>
                                @endif
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
</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif
    });
    $('#paymentHistoryTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 3 },
                {'orderable':false, "targets": 4 }
            ]
    });
</script>
@endpush
