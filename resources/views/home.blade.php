@extends('layouts.app')

@section('pageTitle', 'Home')

@section('content')
<section>
    <div class="row">
        <div class="hero-body container is-fluid">
            <div class="card">
                <div class="card-header"><div class="card-header-title">Dashboard</div></div>

                <div class="card-content">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
