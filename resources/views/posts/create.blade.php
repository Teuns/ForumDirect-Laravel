<?php use \App\Http\Controllers\IndexController; ?>

<style>
    .CodeMirror {
        max-height: 350px;
    }
</style>

@extends('layouts.app') 
@section('pageTitle', 'Create New Post') 
@section('content')

<section>
    <div class="container pt-3 pb-3">
        <div class="panel">
            <div class="panel-body">
                <div class="panel-header-title">New Post</div>
                @if($draft && IndexController::GetUserRoleName(Auth::user()->id) == 'Member')
                    <p><b>Note:</b> Your post is going to be reviewed by one of our moderators/administrators.</p>
                    <p>This can take approx. 24 hours. We do this to be sure that the discussions here remain of high quality.</p>
                    <p>Thanks for your understanding.</p>
                    <br />
                @endif
                <form class="form" role="form" method="POST" action="{{ route('store_post', $id) }}">
                    {{ csrf_field() }}

                    <input type="hidden" name="id" value="{{$id}}">

                    <input type="hidden" name="subforum_id" value="{{$subforum_id}}">

                    <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }} form-group">
                        <label for="body">Body</label>

                        <textarea class="form-control" name="body" id="body" cols="30" rows="10" class="form-control"><?php if(isset($post)){ echo $post; }else{ if(isset($thread)){ echo $thread; }} ?></textarea>
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

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
        var body = document.getElementById("body");
        if (body.value) {
            console.log(true);
            body.value = body.value.split('\n').map(function(line) {
                return '> ' + line;
            }).join('\n') + '\n\n';
        }
    });
</script>