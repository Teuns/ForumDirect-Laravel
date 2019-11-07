@extends('layouts.admin')

@section('pageTitle', 'AdminCP - Create Subforum')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create subforum</h1>
</div>

<div class="row">
    <div class="col-12">
    <form role="form" method="POST" action="{{ url('/admincp/subforums/create') }}">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="label">Name</label>
                <div class="control">
                    <input name="name" class="form-control" type="text" required>
                </div>
        </div>
        <div class="form-group">
            <label class="label">Slug</label>
                <div class="control">
                    <input name="slug" class="form-control" type="text">
                    <p><i>leave it if you want to slugify it automatically</i></p>
                </div>
        </div>
        <div class="form-group">
            <label class="label">Description</label>
                <div class="control">
                    <textarea name="description" class="form-control"></textarea>
                </div>
        </div>
        <div class="form-group">
            <label class="label">Forum ID</label>
                <div class="control">
                    <select name="forum_id" class="form-control" required>
                      @foreach($forums as $row)
                        <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                      @endforeach
                    </select>
                </div>
        </div>
        <div class="form-group">
            <label class="label">Pos</label>
                <div class="control">
                    <input name="pos" class="form-control" type="text">
                    <p><i>Leave it if you want to have it at the last</i></p>
                </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>
</div>

@endsection
