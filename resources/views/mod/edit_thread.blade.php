<?php use \App\Http\Controllers\IndexController; ?>

@extends('layouts.app')

@section('pageTitle', 'ModCP - Edit Thread')

@section('content')

<div class="container my-3">
    <div class="row">
        <div class="col-md-3 mb-3">
             <div class="panel">
                <div class="panel-body">
                    <div class="list">
                      <a href="/modcp" class="list-item list-item-action">Dashboard</a>
                      <a href="/modcp/posts" class="list-item list-item-action">Posts</a>
                      <a href="/modcp/threads" class="list-item list-item-action active">Threads</a>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-md-9">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Mod Panel</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="is-size-3">Edit thread</h1>
                            <br />
                            <form role="form" method="POST" action="{{ url('/modcp/edit-thread/'.$thread->id) }}" novalidate>
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="label">Title</label>
                                    <div class="control">
                                        <input name="title" class="form-control" type="text" value="{{ $thread->title }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Body</label>
                                    <div class="control">
                                        <textarea name="body" class="form-control" type="text" rows="8">{{ $thread->body }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Closed (current: <?php if($thread->closed == 1){ echo 'YES'; }else{ echo 'NO'; } ?>)</label>
                                    <div class="control">
                                        <div class="select">
                                            <select name="closed" class="form-control">
                                                <option value="0">NO</option>
                                                <option value="1">YES</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
