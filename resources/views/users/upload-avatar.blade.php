@extends('layouts.app')

@section('pageTitle', 'User')

@section('content')

<div class="container my-3">
    <div class="row">
        <div class="col-md-3 mb-3">
          <div class="panel">
            <div class="panel-body">
              <div class="list">
                <a href="/users" class="list-item list-item-action">Dashboard</a>
                <a href="/users/edit-account" class="list-item list-item-action">Edit Account</a>
                <a href="#" class="list-item list-item-action">Change signature</a>
                <a href="/users/upload-avatar" class="list-item list-item-action active">Upload avatar</a>
                <a href="/direct/inbox" class="list-item list-item-action">Inbox</a>
                <a href="/direct/outbox" class="list-item list-item-action">Outbox</a>
                <a href="#" class="list-item list-item-action">Sessions</a>
              </div> 
            </div>
          </div>
        </div>
        <div class="col-md-9">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Your Profile</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <form role="form" enctype="multipart/form-data" method="POST" action="{{ url('/users/upload-avatar') }}" novalidate class="form-horizontal">
                              {{ csrf_field() }}
                          <div class="form-group">
                            <label class="label">Avatar</label>
                            <div class="form-group">
                                <input name="input_img" type="file" required>
                              </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </form>
                        </div>
                         <div class="col-md-4">
                        <label class="label">Current avatar</label>
                           <p class="image">
                                    <img src="{{ $user->user_avatar }}" style="width: 150px;">
                                </p>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
