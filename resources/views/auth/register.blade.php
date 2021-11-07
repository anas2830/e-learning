@extends('layouts.defaulLogin')

@section('content')
<!-- Registrations page-->
<section class="section-bottom-border login-signup">
    <div class="container">
      <div class="row">
        <div class="login-main template-form">
          <h4>Please Register, or <a href="#">Login</a></h4>
          <div class="template-space"></div>
          <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6"> <a href="#" class="btn btn-facebook btn-block">Facebook</a> </div>
            <div class="col-xs-6 col-sm-6 col-md-6"> <a href="#" class="btn btn-google btn-block">Google</a> </div>
          </div>
          <div class="login-or">
            <hr class="hr-or">
            <span class="span-or">or</span> </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                    @error('name')
                        <strong>{{ $message }}</strong>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                        <strong>{{ $message }}</strong>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                        <strong>{{ $message }}</strong>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password-confirm">Re Enter Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn btn-primary"> Sign Up </button>
            </form>
        </div>
      </div>
    </div>
</section>
@endsection
