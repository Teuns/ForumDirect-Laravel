@extends('layouts.app') @section('pageTitle', 'Drafts (Posts)') @section('content')
<section>
    <div class="container bg-white pt-3 pb-3">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Drafts <a class="button is-link pull-right" href="/">Return</a>
                </div>
                <div class="row">
                    @foreach($posts as $post)
                    <div class="thread">
                        <h3><a href="{{ route('index', ['id' => $post->id]) }}">{{ $post->title }}</a></h3>
                        <p>{{ str_limit($post->body, 50) }}</p>
                        <p>
                            @can('publish-post')
                            <a href="{{ route('publish_post', ['id' => $post->id]) }}" class="btn btn-primary" role="button">Publish</a> @endcan
                            <a href="{{ route('edit_post', ['id' => $post->id]) }}" class="btn btn-secondary" role="button">Edit</a>
                        </p>
                    </div>
                    <br> @endforeach @if($posts->isEmpty())
                    <p>There is no data.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection