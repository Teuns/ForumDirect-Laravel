@extends('layouts.app')

<?php use \App\Http\Controllers\IndexController; 
?>

@section('pageTitle', 'User - Inbox')

@section('content')

<div class="container my-3">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="panel">
              <div class="panel-body">
               <div class="list">
                  <a href="#" class="list-item list-item-action">Dashboard</a>
                  <a href="/users/edit-account" class="list-item list-item-action">Edit Account</a>
                  <a href="#" class="list-item list-item-action">Change signature</a>
                  <a href="/users/upload-avatar" class="list-item list-item-action">Upload avatar</a>
                  <a href="/direct/inbox" class="list-item list-item-action active">Inbox</a>
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
                            <h4>Inbox <a class="btn btn-primary float-right" href="/direct/create" role="button">Create new DM</a></h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                              <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th>Name</th>
                                    <th>From</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                              <tbody>
                              @foreach ($direct_messages as $row)
                              <tr>
                                  <td>{{ $row->name }}</td>
                                  <td>{{  IndexController::GetUserName($row->from_uid) }}</td>
                                  <td><a href="/direct/view/{{ $row->direct_id }}">view</a></td>
                              </tr>
                              @endforeach
                              @if($direct_messages->isEmpty())
                                  <td colspan="4">No data to show here.</td>
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
</div>
@endsection
