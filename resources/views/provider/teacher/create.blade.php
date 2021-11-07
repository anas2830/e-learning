@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{route('provider.home')}}"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="{{route('provider.teacher.index')}}">Teacher</a></li>
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
            <h5 class="panel-title">Teacher Create</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form class="form-horizontal form-validate-jquery" action="{{route('provider.teacher.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <fieldset class="content-group">
                    @if (session('msgType'))
                        <div id="msgDiv" class="alert alert-{{session('msgType')}} alert-styled-left alert-arrow-left alert-bordered">
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
                        <label class="control-label col-lg-3">Teacher ID <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" value="{{Helper::generateAutoID('edu_teachers','teacher_id')}}" disabled>
                            <input type="hidden" name="teacher_id" value="{{Helper::generateAutoID('edu_teachers','teacher_id')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Full Name <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="text" name="name" class="form-control" placeholder="Teacher Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Address <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <textarea name="address" class="form-control" placeholder="Teacher's address"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Phone <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="enter your valid phone number" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Email <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="email" id="email_address" name="email" class="form-control" placeholder="enter your valid email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3">Password <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input type="password" id="password" name="password" class="form-control" placeholder="enter your password" required>
                            <input type="checkbox" onclick="showPassword()"> Show Password 
                        </div>
                        
                    </div>
                    <!-- /basic textarea -->
                    

                </fieldset>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="reset" class="btn btn-default" id="reset">Reset <i class="icon-reload-alt position-right"></i></button>
                    <a href="{{route('provider.teacher.index')}}" class="btn btn-default">Back To List <i class="icon-backward2 position-right"></i></a>
                </div>
            </form>
        </div>
    </div>
    <!-- /form validation -->


    <!-- Footer -->
    <div class="footer text-muted">
        &copy; 2015.{{date('Y')}} <a href="#">Limitless Web App Kit</a> by <a href="#" target="_blank">Anas</a>
    </div>
    <!-- /footer -->

</div>
<!-- /content area -->
@endsection

@push('javascript')
<script type="text/javascript">
    $(document).ready(function () {
        @if (session('msgType'))
            setTimeout(function() {$('#msgDiv').hide()}, 6000);
        @endif
        
    });

    $('#phone').keypress(function (event) {
        var keycode = event.which;
        if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
            event.preventDefault();
        }
    });

    function showPassword() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    } 
</script>
@endpush
