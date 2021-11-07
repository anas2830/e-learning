@extends('provider.layouts.default')

@section('content')
<!-- Page header -->
<div class="page-header">
    {{-- <div class="page-header-content">
        <div class="page-title">
            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Icons</span> - Glyphicons</h4>
        </div>
    </div> --}}

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="icons_glyphicons.html">Icons</a></li>
            <li class="active">Glyphicons</li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Content area -->
<div class="content">
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">Welcome Page</h6>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    <li><a data-action="reload"></a></li>
                    <li><a data-action="close"></a></li>
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <div style="text-align: center; padding: 40px;">
                <div style="border-bottom: 1px solid #dddddd;">
                    <span style="font-size: 18px;">Hi! <strong style="font-size: 24px; letter-spacing: 2px;">{{$userInfo->name}}</strong></span> 
                </div>
                <h6>Welcome to Admin Panel</h6>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer text-muted">
        &copy; {{date('Y')}}. <a href="#">Developed</a> by <a href="#" target="_blank">DevsSquad IT Solutions</a>
    </div>
    <!-- /footer -->
</div>
<!-- /content area -->
@endsection
