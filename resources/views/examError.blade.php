@extends('layouts.defaulLogin')

@section('content')
<!-- Content area -->
<div class="content">
    <!-- Error wrapper -->
    <div class="container-fluid text-center">
        <h1 class="error-title">404</h1>
        <h6 class="text-semibold content-group">Oops!!! Page not found! Cause, {{$messege}}</h6>

        <div class="row">
            <div class="col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3">
                <div class="row">
                    <div class="col-sm-12">
                        <a href="{{route($back_route)}}" class="btn btn-primary btn-block content-group"><i class="icon-circle-left2 position-left"></i> Go to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /error wrapper -->
</div>
<!-- /content area -->
@endsection
