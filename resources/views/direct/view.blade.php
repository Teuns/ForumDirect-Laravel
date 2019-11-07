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

@section('pageTitle', 'User - View')

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
                            <h4>View</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>{{ $rows[0]->name }}</h4>
                            @foreach($rows as $row)
                              <div class="panel">
                                <div class="panel-body">
                                  <h6 class="panel-title">
                                    {{ IndexController::getUserName($row->user_id) }}
                                    <small class="float-right">
                                      {{ Carbon\Carbon::parse($row->created_at)->diffForHumans() }}
                                    </small>
                                  </h6>
                                  <?php $parsedown = new ForumDirectParsedown();
                                    $parsedown->setMarkupEscaped(true);
                                    $parsedown->setBreaksEnabled(true);
                                    echo ThreadController::getUserTags($parsedown->text($row->body)); 
                                  ?>
                                </div>
                              </div>
                              <br>
                            @endforeach
                            <br>
                            <div class="panel">
                              <div class="panel-header">Reply
                              </div>
                              <div class="panel-body">
                                <form class="form" role="form" method="POST" action="/direct/reply/{{ $rows[0]->direct_id }}">
                                  <input name="to_uid" value="{{ $rows->last()->user_id }}" hidden>
                                  <input name="name" value="{{ $rows[0]->name }}" hidden>
                                  {{ csrf_field() }}
                                    <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }} form-group">
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
                                    <br>
                                    <div class="form-group">
                                      <button type="submit" class="btn btn-primary">
                                      Create
                                      </button>
                                      <a href="/" class="btn btn-soft btn-primary">
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
