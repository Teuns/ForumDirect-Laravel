@extends('layouts.app') @section('pageTitle', 'Drafts (Threads)') @section('content')
<section>
    <div class="container bg-white pt-3 pb-3">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Drafts <a class="button is-link pull-right" href="/">Return</a>
                </div>
                <div class="row">
                    @foreach($threads as $thread)
                    <div class="thread">
                        <h3><a href="{{ route('show_thread', ['id' => $thread->id, 'slug' => $thread->slug]) }}">{{ $thread->title }}</a></h3>
                        <p>{{ str_limit($thread->body, 50) }}</p>
                        <p>
                            <hr /> @can('publish-thread')
                            <a href="{{ route('publish_thread', ['id' => $thread->id]) }}" class="btn btn-primary" role="button">Publish</a> @endcan
                            <a href="{{ route('edit_thread', ['id' => $thread->id]) }}" class="btn btn-secondary" role="button">Edit</a>
                        </p>
                    </div>
                    <br> @endforeach @if($threads->isEmpty())
                    <p>There is no data.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection