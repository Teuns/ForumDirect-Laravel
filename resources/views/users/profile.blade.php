@extends('layouts.app')

@section('pageTitle', 'User')

@section('content')

<section>
    <div class="container my-3">
        <div class="row">
            <div class="ccol-12 col-xl-9 mb-3">
                <nav class="panel">
                    <p class="panel-header">
                        Profile information
                    </p>
                    <div class="panel-body" style="display: block;">
                        <div class="row">
                            <div class="col-md-4">
                                <figure class="text-left">
                                    <strong>{{ $user->name }}</strong>
                                    <p class="image">
                                        <img src="{{ $user->user_avatar }}" style="width: 150px;">
                                    </p>
                                </figure>
                            </div>
                            <div class="col-md-8">
                                <div class="content ml-2">
                                    <ul class="float-right" style="margin-top: unset;">
                                        <li>Posts: {{ $count_posts }}</li>
                                        <li>Threads: {{ $count_threads }}</li>
                                    </ul>
                                    <i>{{ $user->bio ? $user->bio : "This user has no bio." }}</i>
                                    <br>
                                    <span>Registered on: {{ $user->created_at }}</span>
                                    <br>
                                    <span>Last activity at: {{ Carbon\Carbon::parse($user->user_timestamp)->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-12 col-xl-3 mb-3">
                <nav class="panel">
                    <p class="panel-header">
                        Contact information
                    </p>
                    <div class="panel-body">
                        <div class="notice notice-error" role="alert">This functionality does currently not work.</div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="columns">
            <div class="column is-full">
                <nav class="panel">
                     <p class="panel-header">
                        Friends
                    </p>
                    <div class="panel-body" style="display: block;">
                        <div class="notice notice-error" role="alert">This functionality does currently not work.</div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>
@endsection
