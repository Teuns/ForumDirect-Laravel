@extends('layouts.app') @section('pageTitle', 'Register') @section('content')
<section>
    <div class="container pt-3 pb-3">
        <div class="card">
            <div class="card-body">
                <div class="card-header-title">{{ __('Register') }}</div>
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>

                        <input class="form-control" id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus> @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span> @endif
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('E-Mail Address') }}</label>

                        <input class="form-control" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required> @if ($errors->has('email'))
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
                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                        <input class="form-control" id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" required> I have read, understand and agree to the Terms of use and privacy policy and the forum rules.
                    </div>
                    
                    {!! NoCaptcha::display() !!}

                    <div class="field">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection