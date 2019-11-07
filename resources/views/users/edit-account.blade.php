<?php use \App\Http\Controllers\UsersController; ?>

@extends('layouts.app')

@section('pageTitle', 'User')

@section('content')

<div class="container my-3">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="panel">
                <div class="panel-body">
                    <div class="list">
                        <a href="/users" class="list-item list-item-action">Dashboard</a>
                        <a href="/users/edit-account" class="list-item list-item-action active">Edit Account</a>
                        <a href="#" class="list-item list-item-action">Change signature</a>
                        <a href="/users/upload-avatar" class="list-item list-item-action">Upload avatar</a>
                        <a href="/direct/inbox" class="list-item list-item-action">Inbox</a>
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
                            <h4>Your Profile</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form role="form" method="POST" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="label">Gender</label>
                                    <select class="form-control" name="sex" required>
                                        <option value="{{ Auth::user()->sex }}" selected="selected" hidden="true">{{ Auth::user()->sex }}</option>
                                        <option value="I prefer not to say">I prefer not to say</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="label">Date of Birth</label>
                                    <input class="form-control" type="date" name="date_of_birth" value="{{ Auth::user()->date_of_birth }}">
                                </div>
                                <div class="form-group">
                                    <label class="label">Display my age and date of birth</label>
                                    <select class="form-control" name="display_date_of_birth" id="display_date_of_birth">
                                        <option value="{{ Auth::user()->display_date_of_birth }}" selected="selected" hidden="true">{{ Auth::user()->display_date_of_birth }}</option>
                                        <option value="Hide date and age">Hide date and age</option>
                                        <option value="Display age">Display age</option>
                                        <option value="Display date and age">Display date and age</option>
                                        <option value="Display date">Display date</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="label">Bio</label>
                                    <div class="control">
                                        <textarea class="form-control" name="bio" placeholder="Biography">{{ Auth::user()->bio }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Email address</label>
                                    <input name="email" class="form-control" type="text" value="{{ Auth::user()->email }}">
                                </div>
                                <div class="form-group">
                                    <label class="label">Password</label>
                                    <input name="password" class="form-control" type="password" placeholder="Password">
                                    <p><i>Leave it if you don't want to change your password</i></p>
                                </div>
                                <div class="form-group">
                                    <label class="label">Primary role</label>
                                    <select name="primary_role" class="form-control">
                                        <option value="{{ $roles[0]->primary_role }}" selected="selected" hidden="true">{{ UsersController::getUserRoleName($roles[0]->id) }}</option>
                                        @for($i = 0; $i < count($roles[0]->roles); $i++)
                                            <option value="{{ $roles[0]->roles[$i]->id }}">{{ $roles[0]->roles[$i]->name }}</option>
                                        @endfor
                                    </select>
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
