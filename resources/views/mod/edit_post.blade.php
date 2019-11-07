<?php use \App\Http\Controllers\IndexController; ?>

@extends('layouts.app')

@section('pageTitle', 'ModCP - Edit Post')

@section('content')

<div class="container my-3">
    <div class="row">
        <div class="col-md-3 mb-3">
             <div class="panel">
                <div class="panel-body">
                    <div class="list">
                      <a href="/modcp" class="list-item list-item-action">Dashboard</a>
                      <a href="/modcp/posts" class="list-item list-item-action">Posts</a>
                      <a href="/modcp/threads" class="list-item list-item-action">Threads</a>
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
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="is-size-3">Edit post</h1>
                            <br />
                            <form role="form" method="POST" action="{{ url('/modcp/edit-post/'.$post->id) }}" novalidate>
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="label">Body</label>
                                    <div class="control">
                                        <textarea name="body" class="form-control" type="text" rows="8">{{ $post->body }}</textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
