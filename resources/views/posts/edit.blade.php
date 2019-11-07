<style>
    .CodeMirror {
        max-height: 350px;
    }
</style>

@extends('layouts.app') 
@section('pageTitle', 'Edit Post')
@section('content')

<section>
    <div class="container pt-3 pb-3">
        <div class="panel">
            <div class="panel-body">
                <div class="panel-title">Update Post</div>
                <form class="form-horizontal" role="form" method="POST" action="{{ route('update_post', ['post' => $post->id]) }}">
                    {{ csrf_field() }}

                    <div class="field{{ $errors->has('body') ? ' has-error' : '' }} form-group">
                        <label for="body" class="col-md-4 control-label">Body</label>

                        <textarea class="form-control" name="body" id="body" cols="30" rows="10" class="form-control" required>{{ old('body', $post->body) }}</textarea>
                        @if ($errors->has('body'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('body') }}</strong>
                                </span> @endif
                    </div>

                    <div class="field">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>
                            <a href="/" class="btn btn-soft btn-primary">
                                Cancel
                             </a> 
                            @can('publish-post')
                                <a href="{{ route('publish_post', ['post' => $post->id]) }}">
                                    Publish
                                </a> 
                            @endcan
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection