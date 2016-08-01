@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text">
            <h1><strong>Social Login Demo</strong></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 form-box">
            <div class="form-top">
                <div class="form-top-left">
                    <h4>Enter your username and password to log in:</h4>

                </div>

            </div>
            <div class="form-bottom">
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form class="form-horizontal login-form" role="form" method="post" action="{{ url('/login') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="sr-only" for="form-username">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email..." class="form-username form-control" id="form-username">

                        @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="sr-only" for="form-password">Password</label>
                        <input type="password" name="password" placeholder="Password..." class="form-password form-control" id="form-password">
                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <button type="submit" class="btn">Sign in!</button>
                    <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 social-login">
            <h3>...or login with:</h3>
            <div class="social-login-buttons">
                <a class="btn btn-link-1 btn-link-1-facebook" href="{{url('login/fb')}}">
                    <i class="fa fa-facebook"></i> Facebook
                </a>
                <a class="btn btn-link-1 btn-link-1-google-plus" href="{{url('login/google')}}">
                    <i class="fa fa-google"></i> Google
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
