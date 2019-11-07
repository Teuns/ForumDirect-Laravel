<?php use \App\Http\Controllers\AdminController; ?>

@extends('layouts.admin')

@section('pageTitle', 'AdminCP - Users')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Users</h1>

<!-- DataTales Users -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Users</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Creation date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Creation date</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ AdminController::getUserRoleName($user->id) }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td><a class="btn btn-primary" href="/admincp/users/edit/{{ $user->id }}" role="button">Edit</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
