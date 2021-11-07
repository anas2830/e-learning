@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.eventSms.index')}}">SMS</a></li>
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
            <h5 class="panel-title">Event SMS List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.eventSms.create')}}" class="btn btn-primary add-new">Add New</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        {{-- <div class="panel-body" style="text-align: right">
            <a href="#" class="btn btn-primary">Add New</a>
        </div> --}}
        <table class="table table-bordered table-hover datatable-highlight data-list" id="eventSmsTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="10%">SMS Type</th>
                    <th width="30%">Message</th>
                    <th width="10%">Status</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($allevent_sms))
                    @foreach ($allevent_sms as $key => $sms)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>@if($sms->type == 1) Register Student @elseif($sms->type == 2) Assugn Student @elseif($sms->type == 3) Class Schedule Change @elseif($sms->type == 4) Absent Class  @endif</td>
                        <td>{!! $sms->message !!}</td>
                        <td>@if($sms->status ==1) Active @else Inactive @endif</td>
                        <td class="text-center">
                            <a href="{{route('provider.eventSms.edit', [$sms->id])}}" class="action-icon"><i class="icon-pencil7"></i></a>
                            {{-- <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('provider.eventSms.destroy', [$sms->id])}}">@csrf </i></a> --}}
                        </td>
                    </tr> 
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No Data Found!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <!-- /highlighting rows and columns -->
</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">

    var table = $('#eventSmsTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 3 }
            ]
    });

</script>
@endpush
