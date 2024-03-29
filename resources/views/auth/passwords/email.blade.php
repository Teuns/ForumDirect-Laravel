@extends('layouts.app') @section('content')
<section>
    <div class="container my-3">
        <div class="panel">
            <div class="panel-body">
                <div class="panel-header-title">{{ __('Reset Password') }}</div>
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">{{ __('E-Mail Address') }}</label>

                        <input class="form-control" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required> @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            strong>{{ $errors->first('email') }}</strong>
                        </span> @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection