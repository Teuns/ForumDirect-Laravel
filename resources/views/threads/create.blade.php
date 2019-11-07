<?php use \App\Http\Controllers\IndexController; ?>

<style>
    .CodeMirror {
        max-height: 350px;
    }
</style>

@extends('layouts.app') 
@section('pageTitle', 'Create new thread') 
@section('content')

<section>
    <div class="container pt-3 pb-3">
        <div class="panel">
            <div class="panel-body">
                <div class="panel-title">New Thread</div>
                @if($draft && IndexController::GetUserRoleName(Auth::user()->id) == 'Member')
                    <p><b>Note:</b> Your thread is going to be reviewed by one of our moderators/administrators.</p>
                    <p>This can take approx. 24 hours. We do this to be sure that the discussions here remain of high quality.</p>
                    <p>Thanks for your understanding.</p>
                @endif
                <br />
                <form class="form" role="form" method="POST" action="{{ route('store_thread', $id) }}">
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{$id}}">

                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }} form-group">
                        <label for="title">Title</label>

                        <input class="form-control" id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required autofocus> @if ($errors->has('title'))
                        <span class="help-block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span> @endif
                    </div>

                    <div class="field{{ $errors->has('body') ? ' has-error' : '' }} form-group">
                        <label for="body">Body</label>

                        <textarea class="form-control" name="body" id="body" cols="1" rows="1" class="form-control">{{ old('body') }}</textarea>
                        @if ($errors->has('body'))
                        <span class="help-block">
                            <strong>{{ $errors->first('body') }}</strong>
                        </span> @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            Create
                        </button>
                        <a href="/" class="btn btn-soft btn-primary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection