<?php use \App\Http\Controllers\ThreadController; 
use \App\ForumDirectParsedown;
?>

<style>
  #editor-container {
  height: 200px;
}

blockquote {
  padding: 8px;
  font-size: 1.1rem;
}
</style>

@extends('layouts.app') 
@section('pageTitle', $thread->title) 
@section('content')

<div class="container pt-3 pb-3">
  {{ $posts->links() }} @if($page
  <=1 ) 
<div class="panel mb-3">
  <p class="h6 text-white bg-primary mb-0 p-3 rounded-top" style="border-bottom: var(--divider-size) solid var(--outline-color-secondary); border-top-left-radius: var(--border-radius); border-top-right-radius: var(--border-radius);">{{ $thread->title }}</p>
  <div class="panel-body">
    <div class="row">
      <div class="col-md-2"> 
        <div class="text-center forum-avatar">
          <img src="{{ ThreadController::GetUserAvatar($thread->user_id) }}" class="img rounded img-fluid" />
          <div class="text-secondary text-center user"><span class="badge badge-secondary badge-{{ strtolower(ThreadController::GetUserRoleName($thread->user_id)) }}">{{ ThreadController::getUserRoleName($thread->user_id) }}</span></div>
        </div>
      </div>
      <div class="col-md-10">
        <small class="date"><a href="../../threads/show/{{ $thread->id }}-{{ $thread->slug }}">{{ $thread->created_at }}@if($thread->updated_at), updated at: {{ $thread->updated_at }}@endif</a>
        </small>
        @if(Auth::user() && $thread->closed)
          @if(strtolower(ThreadController::getUserRoleName(Auth::id())) == 'administrator' || Auth::user() && strtolower(ThreadController::getUserRoleName(Auth::id())) == 'moderator')
            <a href="{{ route('edit_thread', ['id' => $thread->id]) }}" class="float-right btn btn-primary btn-sm ml-2"><i class="fa fa-pencil"></i>
          </a> <a href="/modcp/threads/delete/<?= $thread->id; ?>" onclick="return confirm('Are you sure you want to delete this thread?')" class="float-right btn btn-danger btn-sm ml-2"><i class="fa fa-minus-circle"></i>
          </a>
          @endif
        @endif
        @if(Auth::user() && !$thread->closed) 
        @if($thread->user_id != Auth::id())
            @if(strtolower(ThreadController::getUserRoleName(Auth::id())) == 'administrator' || Auth::user() && strtolower(ThreadController::getUserRoleName(Auth::id())) == 'moderator')
            <a href="{{ route('edit_thread', ['id' => $thread->id]) }}" class="float-right btn btn-primary btn-sm ml-2"><i class="fa fa-pencil"></i>
            </a> <a href="/modcp/threads/delete/<?= $thread->id; ?>" onclick="return confirm('Are you sure you want to delete this thread?')" class="float-right btn btn-danger btn-sm ml-2"><i class="fa fa-minus-circle"></i>
            </a>          
          @endif
        @endif
        @can('edit-thread') @if($thread->user_id == Auth::id())
        @if(strtolower(ThreadController::getUserRoleName(Auth::id())) == 'administrator' || Auth::user() && strtolower(ThreadController::getUserRoleName(Auth::id())) == 'moderator')
        <a href="/modcp/threads/delete/<?= $thread->id; ?>" onclick="return confirm('Are you sure?')" class="float-right btn btn-danger btn-sm ml-2"><i class="fa fa-minus-circle"></i>
          </a>
        @endif
        <a href="{{ route('edit_thread', ['id' => $thread->id]) }}" class="float-right btn text-white btn-primary btn-sm ml-2"><i class="fa fa-pencil"></i>
        </a> @endif @endcan
        <a class="float-right btn text-white btn-danger btn-sm" data-toggle="modal" data-target="#reportModal" data-type="thread" data-id="{{ $thread->id }}" onclick="flag(this)"> 
          <i class="fa fa-flag">
          </i>
        </a> @endif
        <article class="post">
        <p>
          <a class="{{ strtolower(ThreadController::GetUserRoleName($thread->user_id)) }}" href="/users/{{ ThreadController::getUserName($thread->user_id) }}">
            {{ ThreadController::GetUserName($thread->user_id) }}
          </a>
        </p>
        <div class="clearfix">
        </div>
        <?php $parsedown = new ForumDirectParsedown();
            $parsedown->setMarkupEscaped(true);
            $parsedown->setBreaksEnabled(true);
            echo ThreadController::GetUserTags($parsedown->text($thread->body)); 
        ?>
        </article>
        @if(Auth::user() && !$thread->closed)
        <div class="post buttons">
          @can('create-post')
          <a class="float-right btn btn-primary btn-sm ml-2" href="/threads/create/{{ $subforum[0]->id }}/?id[]={{ $thread->id }}"> 
            <i class="fa fa-reply">
            </i>&nbsp;Reply
          </a>
          @endcan
          <button class="float-right like__btn btn btn-danger btn-sm" data-tid="{{ $thread->id }}" {{ ThreadController::checkIfUserVotedThread($thread->id) }} <?php if(Auth::user()->id == $thread->user_id): ?>disabled="true"<?php endif; ?>>
            <i class="like__icon fa fa-heart">
            </i>
            Like&nbsp;<span class="like__number">{{ $thread->votes }}
            </span>
          </button>
        </div>
        @else
        <div class="post buttons">
          <button class="float-right like__btn btn btn-danger btn-sm" disabled="true">
              <i class="like__icon fa fa-heart">
              </i>
              Like&nbsp;<span class="like__number">{{ $thread->votes }}
              </span>
          </button>
        </div>
        @endif
      </div>
    </div>
  </div>
  @else
  <div class="panel mb-3"> 
    <p class="h6 text-white bg-primary mb-0 p-3 rounded-top thead">{{ $thread->title }}</p>
    @endif @foreach ($posts as $row)
    <div class="panel-body">
      <div class="row" id="pid{{ $row->id }}">
        <div class="col-md-2">
          <div class="text-center forum-avatar">
            <img src="{{ ThreadController::GetUserAvatar($row->user_id) }}" class="img rounded img-fluid" />
            <div class="text-secondary text-center user"><span class="badge badge-secondary badge-{{ strtolower(ThreadController::getUserRoleName($row->user_id)) }}">{{ ThreadController::GetUserRoleName($row->user_id) }}</span></div>
          </div>
        </div>
        <div class="col-md-10">
          <small class="date"><a href="../../threads/show/{{ $thread->id }}-{{ $thread->slug }}<?php if($page > 1): ?>?page=<?= $page; ?><?php endif; ?>#pid{{ $row->id }}">{{ $row->created_at }}@if($row->updated_at), updated at: {{ $row->updated_at }}@endif
          </small>
          @if(Auth::user() && $thread->closed)
            @if(strtolower(ThreadController::getUserRoleName(Auth::id())) == 'administrator' || Auth::user() && strtolower(ThreadController::getUserRoleName(Auth::id())) == 'moderator')
            <a href="{{ route('edit_post', ['id' => $row->id]) }}" class="float-right btn btn-primary btn-sm ml-2"><i class="fa fa-pencil"></i>
            </a> <a href="/modcp/posts/delete/<?= $row->id; ?>" onclick="return confirm('Are you sure?')" class="float-right btn btn-danger btn-sm ml-2"><i class="fa fa-minus-circle"></i>
            </a>
            @endif
          @endif
          @if(Auth::user() && !$thread->closed) @can('edit-post') 
          @if($row->user_id != Auth::id())
            @if(strtolower(ThreadController::getUserRoleName(Auth::id())) == 'administrator' || Auth::user() && strtolower(ThreadController::getUserRoleName(Auth::id())) == 'moderator')
            <a href="{{ route('edit_post', ['id' => $row->id]) }}" class="float-right btn btn-primary btn-sm ml-2"><i class="fa fa-pencil"></i>
            </a> <a href="/modcp/posts/delete/<?= $row->id; ?>" onclick="return confirm('Are you sure you want to delete this post?')" class="float-right btn btn-danger btn-sm ml-2"><i class="fa fa-minus-circle"></i>
            </a>
            @endif
          @endif
          @if($row->user_id == Auth::id())
          @if(strtolower(ThreadController::getUserRoleName(Auth::id())) == 'administrator' || Auth::user() && strtolower(ThreadController::getUserRoleName(Auth::id())) == 'moderator')
          <a href="/modcp/posts/delete/<?= $row->id; ?>" onclick="return confirm('Are you sure you want to delete this post?')" class="float-right btn btn-danger btn-sm ml-2"><i class="fa fa-minus-circle"></i></a>
          @endif
          <a href="{{ route('edit_post', ['id' => $row->id]) }}" class="float-right btn btn-primary btn-sm ml-2"><i class="fa fa-pencil"></i>
          </a> @endif @endcan
          <a class="float-right btn text-white btn-danger btn-sm" data-toggle="modal" data-target="#reportModal" data-type="post" data-id="{{ $row->id }}" onclick="flag(this)"> 
            <i class="fa fa-flag">
            </i>
          </a> @endif
          <article class="post">
          <p>
            <a class="{{ strtolower(ThreadController::getUserRoleName($row->user_id)) }}" href="/users/{{ ThreadController::getUserName($row->user_id) }}">
              {{ ThreadController::GetUserName($row->user_id) }}
            </a>
          </p>
          <div class="clearfix">
          </div>
          <?php $parsedown = new ForumDirectParsedown();
              $parsedown->setMarkupEscaped(true);
              $parsedown->setBreaksEnabled(true);
              echo ThreadController::GetUserTags($parsedown->text($row->body)); 
          ?>
          </article>
          @if(Auth::user() && !$thread->closed)
          <div class="post buttons">
            @can('create-post')
            <a class="float-right btn btn-primary btn-sm ml-2" href="/posts/create/{{ $thread->id }}/?id[]={{ $row->id }}"> 
              <i class="fa fa-reply">
              </i>&nbsp;Reply
            </a>
            @endcan
            <button class="float-right like__btn btn btn-danger btn-sm" data-pid="{{ $row->id }}" {{ ThreadController::checkIfUserVotedPost($row->id) }} <?php if(Auth::user()->id == $row->user_id): ?>disabled="true"<?php endif; ?>>
              <i class="like__icon fa fa-heart">
              </i>
              Like&nbsp;<span class="like__number">{{ $row->votes }}
              </span>
            </button>
          </div>
          @else
          <div class="post buttons">
	        <button class="float-right like__btn btn btn-danger btn-sm" disabled="true">
	            <i class="like__icon fa fa-heart">
	            </i>
	            Like&nbsp;<span class="like__number">{{ $row->votes }}
	            </span>
	        </button>
      	  </div>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  </div>
  {{ $posts->links() }}
  <?php $resultstr = array(); ?>
  @foreach($views as $item)
  <?php $resultstr[] = "<a href='/users/".ThreadController::GetUserName($item->user_id)."' class='".strtolower(ThreadController::GetUserRoleName($item->user_id))."'>".ThreadController::GetUserName($item->user_id)."</a>"; ?>
  @endforeach
  <?php $last = array_pop($resultstr); 
    $output = implode(', ', $resultstr);
    if ($output) {
      $output .= ' and ';
    }
    $output .= $last;
  ?>
  <?php if($output){ echo "<p>Read by: "; echo $output; } ?>
  <?php if(!$output){ echo "<p>Read by "; echo $thread->views; echo " guests/members </p><br>"; } ?>
  @if(!$thread->closed) @can('create-post')
  <?php if(!$posts->isEmpty()){
      $date1 = new DateTime($posts->last()->created_at); 
      $date2 = new DateTime(now());
      if($date1->diff($date2)->days >= 30){
        echo '<div class="notice notice-warning" role="alert">
          This post is over 30 days old. Please be sure that you really want to bump this thread up.
          </div><br>';
      }
    }
  ?>
  <div id="quickReplyForm" class="panel" style="display: none;">
    <div class="panel-header">Quick reply
    </div>
    <div class="panel-body">
      <form class="form" role="form" method="POST" action="{{ route('store_post', $thread->id) }}">
        {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $thread->id }}">
          <input type="hidden" name="subforum_id" value="{{ $subforum[0]->id }}">
          <div class="field{{ $errors->has('body') ? ' has-error' : '' }} form-group">
          <div id="editor-container"></div>
          <textarea id="text-body" name="body" style="display: none;"></textarea>
          </div>
          @if ($errors->has('body'))
            <div class="field">
              <span class="help-block">
                <strong>{{ $errors->first('body') }}
                </strong>
              </span>
            </div>
          @endif
        <br>
        <div class="field">
          <button type="submit" class="btn btn-primary">
          Create
          </button>
          <a href="/" class="btn btn-soft btn-primary">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
  <br>
  <a href="/posts/create/{{ $thread->id }}" class="btn btn-primary">
    <span class="icon">
      <i class="fa fa-reply">
      </i>
    </span>
    <span>&nbsp;Reply
    </span>
  </a> @else
  <div class="notice notice-info" role="alert">
    <p>Create an&nbsp;<a href="/register">account</a>&nbsp;or&nbsp;<a href="/login">login</a>&nbsp;to take part in this discussion.</p>
  </div>
  @endcan @elseif(Auth::user() && strtolower(ThreadController::GetUserRoleName(Auth::id())) == 'administrator' or Auth::user() && strtolower(ThreadController::GetUserRoleName(Auth::id())) == 'moderator')
  <br>
  <br>
  <a href="/posts/create/{{ $thread->id }}" class="btn btn-primary">
    <span class="icon">
      <i class="fa fa-reply">
      </i>
    </span>&nbsp;Reply
  </a> (topic is closed) @else
  <div class="notice notice-error" role="alert">
    <p>Thread is closed. You can no longer reply to it.</p>
  </div>
  @endif
</div>
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Report Message
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;
          </span>
        </button>
      </div>
      <div class="modal-body" id="modal-body">
        <form>
          <input class="form-control" id="reason" placeholder="Enter your reason for your report here...">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="report()">Save report
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
