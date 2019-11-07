@extends('layouts.app') @section('pageTitle', 'Login') @section('content')
<section>
    <div class="container pt-3 pb-3">
        <div class="columns is-centered">
            <div class="panel">
                <div class="panel-body">
                    <div class="panel-title">{{ __('Login') }}</div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">{{ __('E-Mail Address') }}</label>

                            <input class="form-control" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus> @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span> @endif
                        </div>
                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>

                            <input class="form-control" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required> @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span> @endif
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old( 'remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mb-sm-0 mb-1">
                                {{ __('Login') }}
                            </button>
                            @if (Route::has('password.request'))
                            <a class="ml-0 btn btn-soft btn-primary" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a> @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection