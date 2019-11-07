<?php use \App\Http\Controllers\IndexController; ?>

@extends('layouts.app')

@section('pageTitle', 'ModCP - Threads')

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
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="is-size-3">Threads</h1>
                            <br />
                            <div class="table-responsive">
                                <table class="table table-stripede">
                                    <thead>
                                        <tr>
                                            <th>Author</th>
                                            <th>Created</th>
                                            <th>Modified</th>
                                            <th>Title</th>
                                            <th>Message</th>
                                            <th>Closed</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($threads as $row)
                                            <tr>
                                                <td>{{ IndexController::getUserName($row->user_id) }}</td>
                                                <td>{{ $row->created_at }}</td>
                                                <td>{{ $row->updated_at }}</td>
                                                <td>{{ $row->title }}</td>
                                                <td>{{ Str::limit($row->body, 80) }}</td>
                                                <td>{{ $row->closed }}</td>
                                                <td><a href="/modcp/edit-thread/{{ $row->id }}">edit</a></td>
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
</div>
@endsection
