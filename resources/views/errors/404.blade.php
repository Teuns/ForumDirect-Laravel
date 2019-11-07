@extends('layouts.app')

@section('pageTitle', 'Error - 404')

@section('content')

<div class="container my-3">
    <div class="panel">
        <div class="panel-body">
            <h5 class="panel-title">
                404
            </h5>
            Oops! Cannot find the page you've requested.<br>
            <a href="#">Go back</a>, use the <a href="#">search</a> or ask the administrator of this website if you think this is a mistake.
            <br>Good luck!
        </div>
    </div>
</div>

@endsection