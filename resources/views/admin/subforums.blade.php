<?php use \App\Http\Controllers\AdminController; ?>

@extends('layouts.admin')

@section('pageTitle', 'AdminCP - Subforums')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Subforums</h1>
</div>
<p class="mb-4">Drag and drop the element to change the position of the subforum. <a href="/admincp/subforums/create" class="btn btn-primary float-right">Create</a></p>
<div class="row">

    <div class="col-lg-12">

        <!-- Forums Card -->
        <div class="card mb-4">
            <div class="card-header">
                Subforums
            </div>
            <div class="card-body">
                <ul class="list-group sortable">
                    
                    @foreach($subforums as $subforum)
                    
                    <li class="list-group-item"><strong>{{  AdminController::GetForumName($subforum->forum_id) }}</strong><br><?= $subforum->name; ?> <span class="float-right"><a href="/admincp/subforums/edit/<?= $subforum->id; ?>" style="color: inherit;"><i class="fa fa-edit" aria-hidden="true"></i></a> <a href="/admincp/subforums/delete/<?= $subforum->id; ?>" onclick="return confirm('Are you sure you want to delete this subforum?');" style="color: inherit;"><i class="fa fa-times fa-xs" aria-hidden="true"></i></a></span></li>

                    @endforeach
                </ul>
            </div>
        </div>

    </div>

</div>

<script>
    new Sortable(document.getElementsByClassName('sortable')[0]);
</script>
@endsection
