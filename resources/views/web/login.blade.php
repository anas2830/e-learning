@extends('student.layouts.defaulLogin')

@section('content')
<!-- Advanced login -->
<form method="POST" action="{{ route('student.login') }}">
    @csrf
    <div class="panel panel-body login-form">
        <div class="text-center">
            <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
            <h5 class="content-group">Login to your account <small class="display-block">Your credentials</small></h5>
        </div>

        <div class="form-group has-feedback has-feedback-left">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
            <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
            </div>
            @if (session('error'))
            <strong style="color:red;">{!! session('error') !!}</strong>
            @endif
        </div>

        <div class="form-group has-feedback has-feedback-left">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
            <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
            </div>
            @if (session('error'))
            <strong style="color:red;">{!! session('error') !!}</strong>
            @endif
        </div>

        <div class="form-group login-options">
            <div class="row">
                <div class="col-sm-6">
                    <label class="checkbox-inline">
                        <input type="checkbox" class="styled" checked="checked" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember
                    </label>
                </div>

                {{-- <div class="col-sm-6 text-right">
                    <a href="login_password_recover.html">Forgot password?</a>
                </div> --}}
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn bg-blue btn-block">Login <i class="icon-arrow-right14 position-right"></i></button>
        </div>

        {{-- <div class="content-divider text-muted form-group"><span>Don't have an account?</span></div>
        <a href="login_registration.html" class="btn btn-default btn-block content-group">Sign up</a>
        <span class="help-block text-center no-margin">By continuing, you're confirming that you've read our <a href="#">Terms &amp; Conditions</a> and <a href="#">Cookie Policy</a></span> --}}
    </div>
</form>
<!-- /advanced login -->
@endsection
