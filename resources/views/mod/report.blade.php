@extends('layouts.app')

@section('pageTitle', 'ModCP - Reports')

@section('content')

<?php use \App\Http\Controllers\ModController; ?>
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
                             <h1>Report {{ $report[0]->id }} | Type: {{ $report[0]->type }} (ID : {{ $report[0]->_id }})</h1>
                            <hr>
                                <p><strong>Reason:</strong> {{ $report[0]->reason }}</p>
                            <hr>
                            <p>@if($report[0]->type == "post") {{ ModController::getPost($report[0]->_id) }} @else {{ ModController::getThread($report[0]->_id) }} @endif</p>
                            <hr>
                            <a href="#">go to {{ $report[0]->type }}</a> | <a href="/modcp/{{ $report[0]->type }}s/delete/{{ $report[0]->_id }}" onclick="return confirm('Are you sure you want to perform this action?');">delete {{ $report[0]->type }}</a> | <a href="/modcp/report/delete/{{ $report[0]->id }}" onclick="return confirm('Are you sure you want to perform this action?');">delete report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
