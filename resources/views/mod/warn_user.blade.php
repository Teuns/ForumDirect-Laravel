<?php use \App\Http\Controllers\IndexController; ?>

@extends('layouts.app')

@section('pageTitle', 'ModCP - Warn User')

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
                            <h1 class="is-size-3">Warn user</h1>
                            <br />
                            <form role="form" method="POST" action="/modcp/warnUser" novalidate>
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="label">To username</label>
                                    <div class="control">
                                        <input type="text" name="to_name" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Percentage</label>
                                    <div class="control">
                                        <input type="text" name="percentage" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Reason</label>
                                    <div class="control">
                                        <textarea name="reason" class="form-control" type="text" rows="8"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Valid until</label>
                                    <div class="control">
                                        <input type="date" name="valid_until" class="form-control">
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
