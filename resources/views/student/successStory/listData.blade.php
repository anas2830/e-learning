@extends('layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><a href="{{route('mySuccessStory.index')}}">Success Story</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">

    <!-- Data Table -->
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">My Success Story List</h5>
            <div class="heading-elements">
                <ul class="icons-list" style="margin-top: 0px">
                    <li style="margin-right: 10px;"><a href="{{route('mySuccessStory.create')}}" class="btn btn-primary add-new">Add New</a></li>
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <table class="table table-bordered table-hover datatable-highlight data-list" id="successStoryTable">
            <thead>
                <tr>
                    <th width="3%">SL.</th>
                    <th width="15%">Marketplace</th>
                    <th width="15%" class="text-center">Screenshort</th>
                    <th width="10%">Amount</th>
                    <th width="30%">Comment</th>
                    <th width="17%">Status</th>
                    <th width="10%" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($all_stories))
                    @foreach ($all_stories as $key => $story)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$story->marketplace_name}}</td>
                        <td><img src="{{ asset('uploads/studentStory/thumb/'.$story->work_screenshort)}}" alt=""></td>
                        <td>${{$story->work_amount}}</td>
                        <td>{!! strip_tags(Str::words($story->own_comment, 50, '.....')) !!}</td>
                        <td class="text-center">
                            @if($story->approve_status == 1)
                                Approved
                            @else 
                                Pending
                            @endif
                        </td>
                        <td class="text-center">
                            @if($story->approve_status == 1)
                                <span class="label label-success">Approved</span>
                            @else 
                                <a href="{{route('mySuccessStory.edit', [$story->id])}}" class="action-icon"><i class="icon-pencil7"></i></a>
                                <a href="#" class="action-icon"><i class="icon-trash" id="delete" delete-link="{{route('mySuccessStory.destroy', [$story->id])}}">@csrf </i></a>
                            @endif
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
    <!-- /Data Table -->

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
        var table = $('#successStoryTable').DataTable({
            dom: 'lBfrtip',
                "iDisplayLength": 10,
                "lengthMenu": [ 10, 25,30, 50 ],
                columnDefs: [
                    {'orderable':false, "targets": 2 },
                    {'orderable':false, "targets": 4 },
                    {'orderable':false, "targets": 6 },
                ]
        });

        $(document).ready(function(){
            @if (session('msgType'))
                setTimeout(function() {$('#msgDiv').hide()}, 6000);
            @endif

            $("#successStoryTable thead th").each( function (i) {
                if ($(this).text() === 'Status') {
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