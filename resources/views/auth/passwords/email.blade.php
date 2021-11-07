@extends('layouts.defaulLogin')


@section('content')
<!-- Advanced login -->
<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="panel panel-body login-form">
        <div class="text-center">
            <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
            <h5 class="content-group">Reset Password <small class="display-block">Your credentials</small></h5>
        </div>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="form-group has-feedback has-feedback-left">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
            <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
            </div>
            @error('email')
                <strong style="color:red;">{{ $message }}</strong>
            @enderror
        </div>

        <div class="form-group">
            <button type="submit" class="btn bg-blue btn-block">Send Password Reset Link <i class="icon-arrow-right14 position-right"></i></button>
        </div>

    </div>
</form>
<!-- /advanced login -->
@endsection
