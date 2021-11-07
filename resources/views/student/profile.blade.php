@extends('layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    <!-- Toolbar -->
    <div class="navbar navbar-default navbar-xs">
        <ul class="nav navbar-nav visible-xs-block">
            <li class="full-width text-center"><a data-toggle="collapse" data-target="#navbar-filter"><i class="icon-menu7"></i></a></li>
        </ul>

        <div class="navbar-collapse collapse" id="navbar-filter">
            <ul class="nav navbar-nav element-active-slate-400">
                <li class="active"><a href="#edit-Profile" data-toggle="tab"><i class="icon-calendar3 position-left"></i> Update Profile</a></li>
            </ul>
        </div>
    </div>
    <!-- /toolbar -->

</div>
<!-- /page header -->


<!-- Content area -->
<div class="content">

    <!-- User profile -->
    <div class="row">
        <div class="col-lg-9">
            <div class="tabbable">
                <div class="tab-content">

                    <div class="tab-pane fade in active" id="edit-Profile">

                        <!-- Profile info -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">Profile information</h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                        <li><a data-action="close"></a></li>
                                    </ul>
                                </div>
                            </div>

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

                            <div class="panel-body">
                                <form class="form-horizontal form-validate-jquery" action="{{route('profileUpdate', [$student_info->id])}}" method="POST" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Username</label>
                                                <input type="text" name="name" value="{{$student_info->name}}" class="form-control" required="">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Sur Name</label>
                                                <input type="text" name="sur_name" value="{{$student_info->sur_name}}" class="form-control" required="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Email</label>
                                                <input type="text" readonly="readonly" value="{{$student_info->email}}" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Phone #</label>
                                                <input type="text" id="phone" name="phone" value="{{$student_info->phone}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Fb Link</label>
                                                <input type="text" name="fb_profile" value="{{$student_info->fb_profile}}" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Address</label>
                                                <input type="text" name="address" value="{{$student_info->address}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /profile info -->


                        <!-- Account settings -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">Account settings</h6>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                        <li><a data-action="close"></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="panel-body">
                                <form class="form-horizontal form-validate-jquery" action="{{route('profilePassUpdate', [$student_info->id])}}" method="POST" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Password <span class="text-danger"></span></label>
                                        <div class="col-lg-9">
                                            <input type="password" id="password" name="password" class="form-control" placeholder="enter new password" required="">
                                            <input type="checkbox" onclick="showPassword()"> Show Password 
                                        </div>
                                        
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /account settings -->
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="thumbnail no-padding">
                <div class="thumb">
                    @if( !empty($student_info->image) || $student_info->image != Null)
                        <img src="{{ asset('uploads/studentProfile/'.$student_info->image)}}" alt="{{$student_info->image}}">
                    @else
                        <img src="{{ asset('backend/assets/images/placeholder.jpg') }}" alt="">
                    @endif
                    <div class="caption-overflow">
                        <span>
                            <a href="{{route('changeProfile')}}" title="Update Image" class="btn bg-success-400 btn-icon btn-xs"><i class="icon-plus2"></i></a>
                        </span>
                    </div>
                </div>
            
                <div class="caption text-center">
                    <h6 class="text-semibold no-margin">{{$student_info->name}} <small class="display-block">Learner</small></h6>
                </div>
            </div>
        </div>
    </div>
    <!-- /user profile -->


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

    $('#custom_file_preview').on('click', '#custom_close', function() {
        $('#custom_file_preview').remove();
        $('#custom_file_input').show();
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
