<?php use \App\Http\Controllers\IndexController; 
use \App\Http\Controllers\ThreadController; 
use \App\ForumDirectParsedown;
?>

<style>
  #editor-container {
  height: 200px;
}
</style>

@extends('layouts.app')

@section('pageTitle', 'User - Create DM')

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
                  <a href="/direct/inbox" class="list-item list-item-action">Inbox</a>
                  <a href="/direct/outbox" class="list-item list-item-action active">Outbox</a>
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
                            <h4>Outbox</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel">
                              <div class="panel-header">Create DM
                              </div>
                              <div class="panel-body">
                                <form class="form" role="form" method="POST" action="/direct/create">
                                  {{ csrf_field() }}
                                    <div class="field{{ $errors->has('body') ? ' has-error' : '' }} form-group">
                                      <div class="field form-group">
                                        <label>Name</label>
                                        <input name="name" class="form-control">
                                      </div>
                                      <div class="field form-group">
                                        <label>To username</label>
                                        <input name="to_name" class="form-control">
                                      </div>
                                      <div class="form-group">
                                        <label>body</label>
                                        <div id="editor-container"></div>
                                        <textarea id="text-body" name="body" style="display: none;"></textarea>
                                        @if ($errors->has('body'))
                                          <div class="form-group">
                                            <span class="help-block">
                                              <strong>{{ $errors->first('body') }}
                                              </strong>
                                            </span>
                                          </div>
                                        @endif
                                      </div>
                                      <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                        Create
                                        </button>
                                        <a href="/" class="btn btn-secondary">
                                          Cancel
                                        </a>
                                      </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
