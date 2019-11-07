<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

<style>
    .just-padding {
        padding: 15px;
    }

    .list-group.list-group-root {
        padding: 0;
        overflow: hidden;
    }

    .list-group.list-group-root .list-group {
        margin-bottom: 0;
    }

    .list-group.list-group-root .list-group-item {
        border-radius: 0;
        border-width: 1px 0 0 0;
    }

    .list-group.list-group-root > .list-group-item:first-child {
        border-top-width: 0;
    }

    .list-group.list-group-root > .list-group > .list-group-item {
        padding-left: 30px;
    }

    .list-group.list-group-root > .list-group > .list-group > .list-group-item {
        padding-left: 45px;
    }

    .list-group-item .glyphicon {
        margin-right: 5px;
    }
</style>

@extends('layouts.admin')

@section('pageTitle', 'AdminCP - Forums')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Forums</h1>
</div>
<a href="/admincp/forums/create" class="btn btn-primary">Create</a> <a href="/admincp/subforums/create" class="btn btn-primary">Create subforum</a>
<div class="mt-4 row">

    <div class="col-lg-12">

        <!-- Forums Card -->
        <div class="card mb-4">
            <div class="card-header">
                Forums
            </div>
            <div class="card-body">
                <?php $lastcat = 0; 
                    $close_previous = 0;  
                ?>

                <div class="list-group list-group-root card">

                @foreach ($forums as $row) @if($lastcat != $row->id)
                    <?php $lastcat = $row->id; ?>
                        @if($close_previous)
                            </div>
                        @else
                            <?php $close_previous = 1; ?>
                        @endif
                            <div class="list-group-item">
                              <a href="#item-<?= $row->id; ?>" class="pull-left" data-toggle="collapse"><i class="fas fa-angle-right mr-2"></i><?= $row->name; ?></a>
                              <a class="pull-right" href="/admincp/forums/edit/<?= $row->id; ?>"><i class="fas fa-pencil-alt fa-xs ml-2"></i></a>
                              <a class="pull-right" href="/admincp/forums/delete/<?= $row->id; ?>" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt fa-xs ml-2"></i></a>
                            </div>
                            <div class="list-group collapse" id="item-<?= $row->id; ?>">
                                @endif
                                <div class="list-group-item">
                                    <a class="pull-left" href="/admincp/subforums/edit/<?= $row->subforum_id; ?>"><?= $row->subforum_name; ?></a>
                                    <a class="pull-right" href="/admincp/subforums/delete/<?= $row->subforum_id; ?>" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt fa-xs ml-2"></i></a>
                                </div>
                            @endforeach 
                            @if(!$forums->isEmpty())
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @if(!$forums_without_subforums->isEmpty())
                    <p>The following forums don't have a subforum yet.</p>
                    <ul>
                    @foreach($forums_without_subforums as $forum)
                        <li><p><?= $forum->name; ?></p></li>
                    @endforeach
                    </ul>
                @endif
            </div>
        </div>
<script>
    $(function() {
    $('.list-group-item').on('click', function() {
        $('.fas', this)
          .toggleClass('fa-angle-right')
          .toggleClass('fa-angle-down');
      });
    });

    function redirect(url) {
      if (confirm('Are you sure you want to delete your post?')) {
        window.location.href=url;
      }
      return false;
    }
</script>
@endsection
