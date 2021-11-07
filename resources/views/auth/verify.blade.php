@extends('layouts.defaulLogin')

@section('content')

<!-- Email Verification page-->
<section class="section-bottom-border login-signup">
    <div class="container">
      <div class="row">
        <div class="login-main template-form">
          <h4>Please Verify Your Email Address</h4>
          <div class="template-space">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif
            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }},
          </div>
          <div class="login-or">
            <hr class="hr-or">
            <span class="span-or">or</span> </div>
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn btn-primary">{{ __('click here to request another') }}</button>.
            </form>
        </div>
      </div>
    </div>
</section>
@endsection
