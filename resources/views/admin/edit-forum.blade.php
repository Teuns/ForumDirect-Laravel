@extends('layouts.admin')

@section('pageTitle', 'AdminCP - Edit Forum')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit forum</h1>
</div>

<div class="row">
    <div class="col-12">
    <form role="form" method="POST" action="{{ url('/admincp/forums/edit/'.$forum[0]->id) }}" novalidate>
        {{ csrf_field() }}
        <div class="form-group">
            <label class="label">Name</label>
                <div class="control">
                    <input name="name" class="form-control" type="text" value="{{ $forum[0]->name }}">
                </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>
</div>

@endsection
