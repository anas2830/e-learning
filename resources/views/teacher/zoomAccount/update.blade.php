@extends('teacher.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('teacher.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('teacher.teacherZoomAcc')}}">Zoom Account Update</a></li>
        </ul>
    </div>
</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- Form validation -->
    <div class="panel panel-flat">
        <div class="panel-heading" style="border-bottom: 1px solid #ddd; margin-bottom: 20px;">
            <h5 class="panel-title">Zoom Account Update</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('teacher.saveTeacherZoomAcc')}}" method="POST">
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
                        <label class="control-label col-lg-2">Account Name</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" name="name" value="{{@$zoom_acc_info->name}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Email<span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="email" class="form-control" name="email" required  value="{{@$zoom_acc_info->email}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Password <span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <input type="password" class="form-control" name="password" required=""  value="{{@$zoom_acc_info->password}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-lg-2">Token<span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <textarea name="token" rows="5" cols="5" class="form-control" placeholder="Token">{{@$zoom_acc_info->token}}</textarea>
                        </div>
                    </div>
                    <!-- /basic text input -->
                    <div class="form-group">
                        <label class="control-label col-lg-2">Docs<span class="text-danger">*</span></label>
                        <div class="col-lg-10">
                            <span>https://marketplace.zoom.us/docs/guides/build/jwt-app</span><br>
                            <span>https://marketplace.zoom.us/develop/create</span>
                        </div>
                    </div>

                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Update <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </form>
        </div>
    </div>
    <!-- /form validation -->


    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">Rafikul Islam</a>
    </div>
    <!-- /footer -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 3000);
        @endif
    })
</script>
@endpush
