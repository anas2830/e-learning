@extends('support.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('support.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('support.stdRequest')}}">Request List</a></li>
            <li class="active">Create</li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- Form validation -->
    <div class="panel panel-flat">
        <div class="panel-heading" style="border-bottom: 1px solid #ddd; margin-bottom: 20px;">
            <h5 class="panel-title"> Create Schedule</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('support.stdRequestScheduleAction', ['std_req_id' => $std_req_id])}}" method="POST">
                @csrf
                <fieldset class="content-group">
                    @if (session('msgType'))
                        <div id="msgDiv" class="alert alert-{{ session('msgType') }} alert-styled-left alert-arrow-left alert-bordered">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            <span class="text-semibold">{{ session('msgType') }}!</span> {{ session('messege') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-styled-left alert-bordered">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            <span class="text-semibold">Opps!</span> {{ $error }}.
                        </div>
                        @endforeach
                    @endif

                    <!-- Basic text input -->
                        <div class="form-group">
                            <label class="control-label col-lg-2">Select Account <span class="text-danger">*</span></label>
                            <div class="col-lg-10">
                                <input type="hidden" class="form-control" name="zoom_acc_id" required="" value="{{@$zoom_account_info->id}}">
                                <input type="email" class="form-control" value="{{@$zoom_account_info->email}}" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-2">Start Date <span class="text-danger">*</span></label>
                            <div class="col-lg-10">
                                <input type="date" class="form-control" name="start_date" required="" @if(isset($liveClass_Schedule->start_date)) value="{{@$liveClass_Schedule->start_date}}" @else value="@php echo date('Y-m-d'); @endphp" @endif>
                            </div>
                        </div>
    
                        <div class="form-group">
                            <label class="control-label col-lg-2">Start Time <span class="text-danger">*</span></label>
                            <div class="col-lg-10">
                                <input type="time" class="form-control time_picker" name="start_time" required="" @if(isset($liveClass_Schedule->start_time)) value="{{@$liveClass_Schedule->start_time}}" @else value="@php echo date("H:i"); @endphp" @endif>
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 control-label required">Duration</label>
                            <div class="col-lg-3 col-md-3">
                                <input required="" type="number" name="d_hour" placeholder="e.g. 3" id="number" class="form-control duration_hour" min="0" data-fv-field="d_hour" value="{{@$liveClass_Schedule->hour}}">
                            </div>
                            <div class="col-lg-1 col-md-1" style="margin-top: 5px;">Hour</div>

                            <div class="col-lg-3 col-md-3">
                                <select class="select-search" name="d_min" required="">
                                    <option value="0" @if(@$liveClass_Schedule->min == 0) Selected @endif>0</option>
                                    <option value="15" @if(@$liveClass_Schedule->min == 15) Selected @endif>15</option>
                                    <option value="30" @if(@$liveClass_Schedule->min == 30) Selected @endif>30</option>
                                    <option value="45" @if(@$liveClass_Schedule->min == 45) Selected @endif>45</option>
                                </select>
                            </div>
                            <div class="col-lg-1 col-md-1" style="margin-top: 5px;">Minute</div>
                        </div>
                  
                    <!-- /basic text input -->

                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary submintBtn">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="reset" class="btn btn-default" id="reset">Reset <i class="icon-reload-alt position-right"></i></button>
                    <a href="{{route('support.stdRequest')}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
                </div>
            </form>
        </div>
    </div>
    <!-- /form validation -->


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
    $(document).ready(function (e) {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif

        $("#overviewDetails").summernote({
            height: 150
        });

        $('.time_picker').datetimepicker({
			format: 'LT'
		});
    })
</script>
@endpush
