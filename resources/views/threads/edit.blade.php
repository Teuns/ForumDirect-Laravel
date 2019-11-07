<style>
    .CodeMirror {
        max-height: 350px;
    }
</style>

@extends('layouts.app') 
@section('pageTitle', 'Edit thread') 
@section('content')

<section>
    <div class="container pt-3 pb-3">
        <div class="panel">
            <div class="panel-body">
                <div class="panel-title">Update Post</div>
                <form class="form-horizontal" role="form" method="POST" action="{{ route('update_thread', ['thread' => $thread->id]) }}">
                    {{ csrf_field() }}

                    <div class="field{{ $errors->has('title') ? ' has-error' : '' }} form-group">
                        <label for="title" class="col-md-4 control-label">Title</label>

                        <input id="title" type="text" class="form-control" name="title" value="{{ old('title', $thread->title) }}" required autofocus> @if ($errors->has('title'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                 </span> @endif
                    </div>

                    <div class="field{{ $errors->has('body') ? ' has-error' : '' }} form-group">
                        <label for="body" class="col-md-4 control-label">Body</label>

                        <textarea name="body" id="body" cols="30" rows="10" class="form-control" required>{{ old('body', $thread->body) }}</textarea>
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
                            @can('publish-thread')
                                <a href="{{ route('publish_thread', ['thread' => $thread->id]) }}" class="button is-link">
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