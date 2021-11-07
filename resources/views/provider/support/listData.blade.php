@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.support.index')}}">Support</a></li>
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
            <h5 class="panel-title">Support List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('provider.support.create')}}" class="btn btn-primary add-new">Add New</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        {{-- <div class="panel-body" style="text-align: right">
            <a href="#" class="btn btn-primary">Add New</a>
        </div> --}}
        <table class="table table-bordered table-hover datatable-highlight data-list" id="supportTable">
            <thead>
                <tr>
                    <th width="5%">SL.</th>
                    <th width="35%">Category</th>
                    <th width="20%">Name</th>
                    <th width="15%">Email</th>
                    <th width="15%">Phone</th>
                    {{-- <th width="10%">Status</th> --}}
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($all_supports))
                    @foreach ($all_supports as $key => $support)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>
                            @foreach ($support->support_cate_names as $item)
                                {{$item->category_name}},
                            @endforeach
                        </td>
                        <td>{{$support->name}}</td>
                        <td>{{$support->email}}</td>
                        <td>{{$support->phone }}</td>
                        {{-- <td>
                            @if ($support->active_status == 1)
                                <span class="label label-success">Active</span>
                            @else 
                                <span class="label label-danger">InActive</span>
                            @endif
                        </td> --}}
                        <td class="text-center">
                            <a href="{{route('provider.support.edit', [$support->id])}}" class="action-icon"><i class="icon-pencil7"></i></a>
                            <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('provider.support.destroy', [$support->id])}}">@csrf </i></a>
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
    <!-- /highlighting rows and columns -->

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
    // $('#courseTable').DataTable();
    
    $('#supportTable').DataTable({
        dom: 'lBfrtip',
            "iDisplayLength": 10,
            "lengthMenu": [ 10, 25,30, 50 ],
            columnDefs: [
                {'orderable':false, "targets": 5 },
                {'orderable':false, "targets": 1 }
            ]
    });
</script>
@endpush
