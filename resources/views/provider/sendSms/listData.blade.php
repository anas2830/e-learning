@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.sendSms.index')}}">SMS</a></li>
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
            <h5 class="panel-title">SMS List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.sendSms.create')}}" class="btn btn-primary add-new">Add New</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        {{-- <div class="panel-body" style="text-align: right">
            <a href="#" class="btn btn-primary">Add New</a>
        </div> --}}
        <table class="table table-bordered table-hover datatable-highlight data-list" id="sendSmsTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="10%">Course</th>
                    <th width="10%">Batch</th>
                    <th width="15%">User Name</th>
                    <th width="15%">User Type</th>
                    <th width="30%">Message</th>
                    <th width="10%">Date</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($all_sms))
                    @foreach ($all_sms as $key => $sms)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>@if($sms->batch_id != null) {{$sms->batch_id}}  @endif</td>
                        <td>@if($sms->course_id != null) {{$sms->course_id}}  @endif</td>
                        <td>@if($sms->sms_receiver_id == 0) All User @else {{Helper::getUserName($sms->sms_receiver_type,$sms->sms_receiver_id)}}@endif</td>
                        <td>@if($sms->sms_receiver_type == 1) Student @elseif($sms->sms_receiver_type == 2) Teacher @elseif($sms->sms_receiver_type == 3) Support @endif</td>
                        <td>{!! $sms->message !!}</td>
                        <td>{!! $sms->date !!}</td>

                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Data Found!</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>User Type</th>
                    <th></th>
                    <th></th>
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

    var table = $('#sendSmsTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 6 }
            ]
    });

    $(document).ready(function() {
            
        $("#sendSmsTable tfoot th").each( function ( i ) {

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

    });

</script>
@endpush
