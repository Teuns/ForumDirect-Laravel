@extends('layouts.admin')

@section('pageTitle', 'AdminCP - Edit Account')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit account</h1>
</div>

<div class="row">
    <div class="col-12">
    <form role="form" method="POST" action="{{ url('/admincp/users/edit/'.$user->id) }}" novalidate>
        {{ csrf_field() }}
        <div class="form-group">
            <label class="label">Name</label>
                <div class="control">
                    <input name="name" class="form-control" type="text" value="{{ $user->name }}">
                </div>
        </div>
        <div class="form-group">
            <label class="label">Role</label>
                <div class="control">
                    <input name="role" class="form-control" type="text" value="{{ $user->roles[0]->name }}">
                </div>
        </div>
        <div class="form-group">
            <label class="label">Primary role</label>
                <div class="control">
                    <input name="primary_role" class="form-control" type="text" value="{{ $user->primary_role }}">
                </div>
        </div>
        <div class="form-group">
            <label class="label">Email address</label>
                <div class="control">
                    <input name="email" class="form-control" type="text" value="{{ $user->email }}">
                </div>
        </div>
        <div class="form-group">
            <label class="label">Avatar</label>
                <div class="control">
                    <input name="avatar" class="form-control" type="text" value="{{ $user->user_avatar }}">
                </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>
</div>

@endsection
