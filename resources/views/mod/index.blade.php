<?php use \App\Http\Controllers\IndexController; ?>

@extends('layouts.app')

@section('pageTitle', 'ModCP')

@section('content')

<div class="container my-3">
    <div class="row">
        <div class="col-md-3 mb-3">
             <div class="panel">
                <div class="panel-body">
                    <div class="list">
                      <a href="/modcp" class="list-item list-item-action active">Dashboard</a>
                      <a href="/modcp/posts" class="list-item list-item-action">Posts</a>
                      <a href="/modcp/threads" class="list-item list-item-action">Threads</a>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-md-9">
            <div class="panel">
                <div class="panel-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h4>Mod Panel</h4>
                            <hr />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Reports</h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Reason</th>
                                            <th>Reporter</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reports as $row)
                                            <tr>
                                                <td>{{ $row->type }}</td>
                                                <td>{{ $row->reason }}</td>
                                                <td>{{ IndexController::getUserName($row->from_uid) }}</td>
                                                <td><a href="/modcp/report/{{ $row->id }}">view</a></td>
                                            </tr>
                                        @endforeach
                                        @if($reports->isEmpty())
                                            <td colspan="6">No data to show here.</td>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <h3>Warnings</h3>
                            <div class=" table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Reason</th>
                                            <th>To</th>
                                            <th>From</th>
                                            <th>Valid until</th>
                                            <th>Percentage</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($warnings as $row)
                                            <tr>
                                                <td>{{ $row->reason }}</td>
                                                <td>{{ IndexController::getUserName($row->to_user_id) }}</td>
                                                <td>{{ IndexController::getUserName($row->from_user_id) }}</td>
                                                <td>{{ $row->valid_until }}</td>
                                                <td>{{ $row->percentage }}</td>
                                                <td><a href="/modcp/warning/{{ $row->id }}">view</a></td>
                                            </tr>
                                        @endforeach
                                        @if($warnings->isEmpty())
                                            <td colspan="6">No data to show here.</td>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <h3>Users <a class="btn btn-primary float-right" href="/modcp/warnUser" role="button">Warn user</a></h3>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Role</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $row)
                                            <tr>
                                                <td>{{ IndexController::getUserRoleName($row->id) }}</td>
                                                <td>{{ $row->email }}</td>
                                                <td><a href="/modcp/ban/{{ $row->id }}" onclick="return confirm('Are you sure you want to perform this action?');">ban member</a> | <a href="/modcp/unban/{{ $row->id }}" onclick="return confirm('Are you sure you want to perform this action?');">unban member</a></td>
                                            </tr>
                                        @endforeach
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