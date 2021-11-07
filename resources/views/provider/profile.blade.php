@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">

    <!-- Header content -->
    {{-- <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">User Pages</span> - Profile</h4>

            <ul class="breadcrumb position-right">
                <li><a href="index.html">Home</a></li>
                <li><a href="user_pages_profile.html">User pages</a></li>
                <li class="active">Profile</li>
            </ul>
        </div>

        <div class="heading-elements">
            <div class="heading-btn-group">
                <a href="#" class="btn btn-link btn-float has-text"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a>
                <a href="#" class="btn btn-link btn-float has-text"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a>
                <a href="#" class="btn btn-link btn-float has-text"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a>
            </div>
        </div>
    </div> --}}
    <!-- /header content -->


    <!-- Toolbar -->
    <div class="navbar navbar-default navbar-xs">
        <ul class="nav navbar-nav visible-xs-block">
            <li class="full-width text-center"><a data-toggle="collapse" data-target="#navbar-filter"><i class="icon-menu7"></i></a></li>
        </ul>

        <div class="navbar-collapse collapse" id="navbar-filter">
            <ul class="nav navbar-nav element-active-slate-400">
<!--                 <li><a href="#activity" data-toggle="tab"><i class="icon-menu7 position-left"></i> Activity</a></li> -->
                <li class="active"><a href="#edit-Profile" data-toggle="tab"><i class="icon-calendar3 position-left"></i> Update Profile</a></li>
    <!--             <li><a href="#settings" data-toggle="tab"><i class="icon-cog3 position-left"></i> Settings</a></li> -->
            </ul>

            <div class="navbar-right">
                <ul class="nav navbar-nav">
                    <li><a href="#"><i class="icon-stack-text position-left"></i> Notes</a></li>
                    <li><a href="#"><i class="icon-collaboration position-left"></i> Friends</a></li>
                    <li><a href="#"><i class="icon-images3 position-left"></i> Photos</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-gear"></i> <span class="visible-xs-inline-block position-right"> Options</span> <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="#"><i class="icon-image2"></i> Update cover</a></li>
                            <li><a href="#"><i class="icon-clippy"></i> Update info</a></li>
                            <li><a href="#"><i class="icon-make-group"></i> Manage sections</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><i class="icon-three-bars"></i> Activity log</a></li>
                            <li><a href="#"><i class="icon-cog5"></i> Profile settings</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
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
                                <form class="form-horizontal form-validate-jquery" action="{{route('provider.profileUpdate', [$provider_info->id])}}" method="POST" enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Username</label>
                                                <input type="text" name="name" value="{{$provider_info->name}}" class="form-control" required="">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Email</label>
                                                <input type="text" readonly="readonly" value="{{$provider_info->email}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Address</label>
                                                <input type="text" name="address" value="{{$provider_info->address}}" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Phone #</label>
                                                <input type="text" id="phone" name="phone" value="{{$provider_info->phone}}" class="form-control">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Upload profile image</label>
                                                @if( $provider_info->image != Null || !empty($provider_info->image)) 

                                                    <div class="file-preview" id="custom_file_preview">
                                                        <div class="close fileinput-remove text-right" id="custom_close">×</div>
                                                        <div class="file-preview-thumbnails">
                                                            <div class="file-preview-frame" id="preview-1603644588432-0">
                                                                <img src="{{ asset('uploads/providerProfile/'.$provider_info->image)}}" class="file-preview-image" alt="{{$provider_info->image}}" style="width:auto;height:200px;">
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>   
                                                        <div class="file-preview-status text-center text-success"></div>
                                                        <div class="kv-fileinput-error file-error-message" style="display: none;"></div>
                                                        <input type="hidden" name="provider_image" value="{{$provider_info->image}}">
                                                    </div>
                                                    <div id="custom_file_input" style="display: none;">
                                                        <input type="file" name="provider_image" class="file-input-extensions">
                                                        <span class="help-block">Allow extensions: <code>jpg</code>, <code>png</code> and <code>jpeg</code> and  Allow Size: <code>640 * 426</code> Only</span>
                                                    </div>

                                                @else

                                                    <input type="file" name="provider_image" class="file-input-extensions">
                                                    <span class="help-block">Allow extensions: <code>jpg</code>, <code>png</code> and <code>jpeg</code> and  Allow Size: <code>640 * 426</code> Only</span>

                                                @endif

                                                
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
                                <form class="form-horizontal form-validate-jquery" action="{{route('provider.profilePassUpdate', [$provider_info->id])}}" method="POST" enctype="multipart/form-data">
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
       <!--              <div class="tab-pane fade" id="settings">

                        <!-- Profile info -->
                      <!--   <div class="panel panel-flat">
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

                            <div class="panel-body">
                                <form action="#">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Username</label>
                                                <input type="text" value="Eugene" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Full name</label>
                                                <input type="text" value="Kopyov" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Address line 1</label>
                                                <input type="text" value="Ring street 12" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Address line 2</label>
                                                <input type="text" value="building D, flat #67" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>City</label>
                                                <input type="text" value="Munich" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label>State/Province</label>
                                                <input type="text" value="Bayern" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label>ZIP code</label>
                                                <input type="text" value="1031" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Email</label>
                                                <input type="text" readonly="readonly" value="eugene@kopyov.com" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label>Your country</label>
                                                <select class="select">
                                                    <option value="germany" selected="selected">Germany</option> 
                                                    <option value="france">France</option> 
                                                    <option value="spain">Spain</option> 
                                                    <option value="netherlands">Netherlands</option> 
                                                    <option value="other">...</option> 
                                                    <option value="uk">United Kingdom</option> 
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Phone #</label>
                                                <input type="text" value="+99-99-9999-9999" class="form-control">
                                                <span class="help-block">+99-99-9999-9999</span>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Upload profile image</label>
                                                <input type="file" class="file-styled">
                                                <span class="help-block">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div> -->
                        <!-- /profile info -->


                        <!-- Account settings -->
                    <!--     <div class="panel panel-flat">
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
                                <form action="#">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Username</label>
                                                <input type="text" value="Kopyov" readonly="readonly" class="form-control">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Current password</label>
                                                <input type="password" value="password" readonly="readonly" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>New password</label>
                                                <input type="password" placeholder="Enter new password" class="form-control">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Repeat password</label>
                                                <input type="password" placeholder="Repeat new password" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Profile visibility</label>

                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="visibility" class="styled" checked="checked">
                                                        Visible to everyone
                                                    </label>
                                                </div>

                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="visibility" class="styled">
                                                        Visible to friends only
                                                    </label>
                                                </div>

                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="visibility" class="styled">
                                                        Visible to my connections only
                                                    </label>
                                                </div>

                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="visibility" class="styled">
                                                        Visible to my colleagues only
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Notifications</label>

                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="styled" checked="checked">
                                                        Password expiration notification
                                                    </label>
                                                </div>

                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="styled" checked="checked">
                                                        New message notification
                                                    </label>
                                                </div>

                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="styled" checked="checked">
                                                        New task notification
                                                    </label>
                                                </div>

                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="styled">
                                                        New contact request notification
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div> -->
                        <!-- /account settings -->

                    <!-- </div>  -->
                </div>
            </div>
        </div>

        <div class="col-lg-3">

            <!-- User thumbnail -->
            <div class="thumbnail">
                <div class="thumb thumb-rounded thumb-slide">
                    @if( !empty($provider_info->image) || $provider_info->image != Null)
                        <img src="{{ asset('uploads/providerProfile/'.$provider_info->image)}}" alt="{{$provider_info->image}}">
                    @else
                        <img src="{{ asset('backend/assets/images/placeholder.jpg') }}" alt="">
                    @endif
                </div>
            
                <div class="caption text-center">
                    <h6 class="text-semibold no-margin">{{$provider_info->name}}</h6>
                    <ul class="icons-list mt-15">
                        <li><a href="#" data-popup="tooltip" title="Google Drive"><i class="icon-google-drive"></i></a></li>
                        <li><a href="#" data-popup="tooltip" title="Twitter"><i class="icon-twitter"></i></a></li>
                        <li><a href="#" data-popup="tooltip" title="Github"><i class="icon-github"></i></a></li>
                    </ul>
                </div>
            </div>
            <!-- /user thumbnail -->


            <!-- Navigation -->
            {{-- <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Navigation</h6>
                    <div class="heading-elements">
                        <a href="#" class="heading-text">See all &rarr;</a>
                    </div>
                </div>

                <div class="list-group list-group-borderless no-padding-top">
                    <a href="#" class="list-group-item"><i class="icon-user"></i> My profile</a>
                    <a href="#" class="list-group-item"><i class="icon-cash3"></i> Balance</a>
                    <a href="#" class="list-group-item"><i class="icon-tree7"></i> Connections <span class="badge bg-danger pull-right">29</span></a>
                    <a href="#" class="list-group-item"><i class="icon-users"></i> Friends</a>
                    <div class="list-group-divider"></div>
                    <a href="#" class="list-group-item"><i class="icon-calendar3"></i> Events <span class="badge bg-teal-400 pull-right">48</span></a>
                    <a href="#" class="list-group-item"><i class="icon-cog3"></i> Account settings</a>
                </div>
            </div> --}}
            <!-- /navigation -->

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
