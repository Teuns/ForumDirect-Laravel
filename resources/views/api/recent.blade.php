<?php

use App\Http\Controllers\IndexController;

?>

<div class="list mb-0">
    @foreach ($last_threads as $row)
        <div class="list-item"><div class="list-item-caption"><a href="/threads/show/{{ $row->id }}-{{ $row->slug }}?action=lastpost">{{ \Illuminate\Support\Str::limit($row->title, 50) }}</a><br><small>by <a href="/users/{{ $row->lastUser->name }}" class="{{ strtolower($row->lastUser->role->name) }}">{{ $row->lastUser->name }}</a> <span>created at {{ Carbon\Carbon::parse($row->lastpost_date)->diffForHumans() }}</span></small>
        </div></div>
    @endforeach
    @if($last_threads->isEmpty())
        <p>-</p>
    @endif
</div>
