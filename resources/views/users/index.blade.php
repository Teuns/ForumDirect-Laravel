<?php use \App\Http\Controllers\IndexController; ?>

@extends('layouts.app')

@section('pageTitle', 'User')

@section('content')

<div class="container my-3">
    <div class="row">
        <div class="col-md-3 mb-3">
          <div class="panel">
            <div class="panel-body">
              <div class="list">
                <a href="#" class="list-item list-item-action active">Dashboard</a>
                <a href="/users/edit-account" class="list-item list-item-action">Edit Account</a>
                <a href="#" class="list-item list-item-action">Change signature</a>
                <a href="/users/upload-avatar" class="list-item list-item-action">Upload avatar</a>
                <a href="/direct/inbox" class="list-item list-item-action">Inbox</a>
                <a href="/direct/outbox" class="list-item list-item-action">Outbox</a>
                <a href="#" class="list-item list-item-action">Sessions</a>
              </div> 
            </div>
          </div>
        </div>
        <div class="col-md-9">
            <nav class="panel">
                <div class="panel-body" style="display: block;">
                    <div class="row">
                        <div class="col-md-4">
                            <figure class="text-center">
                                <strong>{{ $user->name }}</strong>
                                <p class="image">
                                    <img src="{{ $user->user_avatar }}" style="width: 150px;">
                                </p>
                            </figure>
                        </div>
                        <div class="col-md-8">
                            <div class="content ml-2">
                                <p><i>{{ $user->bio ? $user->bio : "This user has no bio." }}</i></p>
                                <p><span>Registered on: {{ $user->created_at }}</span></p>
                                <span>Posts: {{ $count_posts }}</span>
                                <br>
                                <span>Threads: {{ $count_threads }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                           <h5>Your threads</h5>
                           <table class="table table-stripede">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Created</th>
                                        <th>Modified</th>
                                        <th>Last post</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($threads as $row)
                                        <tr>
                                            <td><a href="/threads/show/{{ $row->id }}-{{ $row->slug }}">{{ $row->title }}</a></td>
                                            <td>{{ $row->created_at }}</td>
                                            <td>{{ $row->updated_at }}</td>
                                            <td><a href="/threads/show/{{ $row->id }}-{{ $row->slug }}?action=lastpost">{{ IndexController::GetUserName($row->lastpost_uid) }}</td>
                                        </tr>
                                    @endforeach
                                    @if($threads->isEmpty())
                                        <td colspan="7">No data to show here.</td>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
