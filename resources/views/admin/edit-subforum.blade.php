@extends('layouts.admin')

@section('pageTitle', 'AdminCP - Edit Subforum')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit subforum</h1>
</div>

<div class="row">
    <div class="col-12">
    <form role="form" method="POST" action="{{ url('/admincp/subforums/edit/'.$subforum[0]->id) }}" novalidate>
        {{ csrf_field() }}
        <div class="form-group">
            <label class="label">Name</label>
                <div class="control">
                    <input name="name" class="form-control" type="text" value="{{ $subforum[0]->name }}">
                </div>
        </div>
        <div class="form-group">
            <label class="label">Slug</label>
                <div class="control">
                    <input name="slug" class="form-control" type="text" value="{{ $subforum[0]->slug }}">
                    <p><i>Leave it if you want to slugify it automatically</i></p>
                </div>
        </div>
        <div class="form-group">
            <label class="label">Description</label>
                <div class="control">
                    <textarea name="description" class="form-control">{{ $subforum[0]->description }}</textarea>
                </div>
        </div>
        <div class="form-group">
            <label class="label">Forum ID</label>
                <div class="control">
                    <select name="forum_id" id="forum_id" class="form-control" required>
                      @foreach($forums as $row)
                        <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                      @endforeach
                    </select>
                </div>
        </div>
        <div class="form-group">
            <label class="label">Pos</label>
                <div class="control">
                    <input name="pos" class="form-control" type="text" value="{{ $subforum[0]->pos }}">
                </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>
</div>

<script>
    var temp = <?= $subforum[0]->forum_id; ?>;
    var mySelect = document.getElementById('forum_id');

    for(var i, j = 0; i = mySelect.options[j]; j++) {
        if(i.value == temp) {
            mySelect.selectedIndex = j;
            break;
        }
    }
</script>

@endsection
