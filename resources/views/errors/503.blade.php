@extends('layouts.app')

@section('pageTitle', 'Error - 503')

@section('content')

<div class="container my-3">
    <div class="panel">
        <div class="panel-body">
            <h5 class="panel-title">
                503
            </h5>
            Oops! The website is currently in maintance. The given reason for this is:<br>
            <?php if(json_decode(file_get_contents(storage_path('framework/down')), true)){ $message = json_decode(file_get_contents(storage_path('framework/down')), true)['message']; echo $message; }else{ echo "error"; } ?>
            <br>
            Come back later!
        </div>
    </div>
</div>

@endsection