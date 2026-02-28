@php use App\ForumDirectParsedown; @endphp
<style>
    #editor-container {
        height: auto;
    }

    .ql-editor {
        min-height: 200px !important;
        height: auto !important;
    }

    .ql-editor p, .ql-editor ol, .ql-editor ul, .ql-editor pre, .ql-editor blockquote, .ql-editor h1, .ql-editor h2, .ql-editor h3, .ql-editor h4, .ql-editor h5, .ql-editor h6 {
        margin-bottom: 1rem !important;
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

                    <div class="field{{ $errors->has('body') ? ' has-error' : '' }} form-group mb-3">
                        <label for="body" class="col-md-4 control-label">Body</label>

                            <?php $parsedown = new ForumDirectParsedown();
                            $parsedown->setMarkupEscaped(true);
                            $parsedown->setBreaksEnabled(true);
                            $text = $parsedown->text($thread->body);
                        ?>

                        <div id="editor-container">{!! $text  !!}</div>
                        <textarea id="text-body" name="body" style="display: none;" required></textarea>

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
