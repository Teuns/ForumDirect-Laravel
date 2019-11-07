<?php use \App\Http\Controllers\IndexController; ?>

    @extends('layouts.app') 
    @section('pageTitle', 'Home') 
    @section('content')

    <section>
        <div class="container pt-3">
            @if (session('alert-danger'))
                <div class="notice notice-error" role="alert">
                    <p>{{ session('alert-danger') }}</p>
                </div>
            @endif @if (session('alert'))
                <div class="notice notice-warning" role="alert">
                    <p>{{ session('alert') }}</p>
                </div>
            @endif @if(Auth::user() && strtolower(IndexController::getUserRoleName(Auth::user()->id)) == 'administrator' or Auth::user() && strtolower(IndexController::getUserRoleName(Auth::user()->id) == 'moderator')) @if($count_reports)
                <div class="notice notice-info" role="alert">
                    <p>Note: some reports are waiting to being reviewed.&nbsp;<a href="/modcp">Please take a look and perform an action.</a></p>
                </div>
            @endif @if($count_drafts_threads)
                <div class="notice notice-info" role="alert">
                    <p>Note: Some threads are not &nbsp;<b>published</b> yet.&nbsp;<a href="{{ route('list_drafts_threads') }}">Please take a look and perform an action.</a></p>
                </div>
            @endif @if($count_drafts_posts)
            <div class="notice notice-info" role="alert">
                <p>Note: Some posts are not &nbsp;<b>published</b> yet.&nbsp;<a href="{{ route('list_drafts_posts') }}">Please take a look and perform an action.</a></p>
            </div>
            @endif @endif @if(!Auth::user())
                <div class="notice notice-info" role="alert">
                    <p>Welcome! If you are new here, why don't you&nbsp;<a href="/register">become a member</a>&nbsp;to our awesome community? If not,&nbsp;<a href="/login">log in</a>&nbsp;instead.</p>
                </div>
            @endif

            @if(Auth::user() && !$direct_messagesQuery->isEmpty())
                <div class="notice notice-info" role="alert">
                    <p>You have received a direct message titled '{{  $direct_messagesQuery->last()->name }}' from {{ IndexController::getUserName($direct_messagesQuery->last()->user_id) }}, click&nbsp;<a href="/direct/view/{{ $direct_messagesQuery->last()->direct_id }}">here</a>&nbsp;to view it.</p>
                </div>
            @endif

            @if(Auth::user() && IndexController::getUserRoleName(Auth::user()->id) == 'Banned')
                <div class="notice notice-error" role="alert">
                    <p>Your account is banned. You cannot perform any action that requires permissions.</p>
                </div>
            @endif
            <br>

            <div class="row">
                <div class="col-12 col-xl-8">
                   @if(Auth::user() && IndexController::getUserRoleName(Auth::user()->id) !== 'Banned')
                        {!! $chatbox !!}
                    @endif

                    <?php $lastcat = 0; 
                        $close_previous = 0;  
                    ?>

                    @foreach ($forums as $row) @if($lastcat != $row->cat_id)
                        <?php $lastcat = $row->cat_id; ?>
                            @if($close_previous)
                                </div>
                                </div>
                                </div>
                            @else
                                <?php $close_previous = 1; ?>
                            @endif
                                <div class="panel">
                                <div class="panel-header"><span class="panel-title">{{ $row->cat_name }}</span></div>
                                <div class="panel-body">
                                <div class="list">
                                        @endif
                                        <div class="list-item">
                                            <i class="mdi mdi-36px mdi-comment mdi-is-medium list-item-prefix"></i>
                                            <div class="list-item-caption">
                                            <a href="/subforums/show/{{ $row->subcat_id }}-{{ $row->slug }}">{{ $row->name }}</a>
                                              <span class="text-soft">{{ $row->description }}</span>
                                            </div>
                                            <div class="list-item-caption list-item-suffix">
                                                {!! IndexController::getLastPost($row->subcat_id) !!}
                                            </div>
                                        </div>
                                        @endforeach 
                                        @if($forums->isEmpty())
                                            <p>Nothing to see here.</p>
                                        @else
                                    </div>
                                </div>
                            </div>
                        @endif
                        <br>
                        <p><i>Powered by ForumDirect</i></p><br>
                    </div>

                <div class="col-12 col-xl-4 order-first order-xl-last">
                    <aside>
                        <div class="row">
                            <div class="col-12 col-sm-6 col-xl-12">
                                <div class="panel mb-3 mb-sm-0 mb-xl-3">
                                    <div class="panel-body">
                                        <div class="panel-title d-flex"><h4 class="h4 m-0">Recent posts</h4><button data-tooltip="Reload recent posts" class="btn btn-icon btn-text btn-dark ml-auto" id="recentButton"><i class="mdi mdi-refresh mdi-is-medium"></i></button></div>
                                        <div id="recent">
                                            <div class="list mb-0">
                                                @foreach ($last_threads as $row)
                                                    <div class="list-item"><div class="list-item-caption"><a href="/threads/show/{{ $row->id }}-{{ $row->slug }}?action=lastpost">{{ str_limit($row->title, 50) }}</a><br><small>by <a href="/users/{{ IndexController::GetUserName($row->lastpost_uid) }}" class="{{ strtolower(IndexController::GetUserRoleName($row->lastpost_uid)) }}">{{ IndexController::getUserName($row->lastpost_uid) }}</a> <span>created at {{ Carbon\Carbon::parse($row->lastpost_date)->diffForHumans() }}</span></small>
                                                    </div></div>
                                                @endforeach
                                                @if($last_threads->isEmpty())
                                                    <p>-</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-xl-12">
                                <div class="panel mb-3 mb-sm-0 mb-xl-3">
                                    <div class="panel-body">
                                        <h2 class="h4 panel-title">Online members</h2>
                                        <div class="list mb-0">
                                            <?php $resultstr = array(); ?>
                                            @foreach($users as $item)
                                                @if($item->user_timestamp >= (new \Carbon\Carbon())->subMinutes(15))
                                                    <div class="list-item"><div class="list-item-caption"><strong><a href='/users/{{ $item->name }}' class='{{ strtolower(IndexController::GetUserRoleName($item->id)) }}'>{{ $item->name }}</a></strong></div></div>
                                                    <?php $resultstr[] = "<li><a href='/users/".$item->name."'>".$item->name."</a></li>"; ?>
                                                @endif
                                            @endforeach
                                            <?php if(!$resultstr){ echo "-"; } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-xl-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <h2 class="h4 panel-title">Forum statistics</h2>
                                        <dl class="row mb-0">
                                            <dt class="col-8">Total topics:</dt>
                                            <dd class="col-4 mb-0">
                                                <?= $count_topics; ?>
                                            </dd>
                                        </dl>
                                        <dl class="row mb-0">
                                            <dt class="col-8">Total posts:</dt>
                                            <dd class="col-4 mb-0">
                                                <?= $count_posts; ?>
                                            </dd>
                                        </dl>
                                        <dl class="row mb-0">
                                            <dt class="col-8">Total users:</dt>
                                            <dd class="col-4 mb-0">
                                                <?= $count_users; ?>
                                            </dd>
                                        </dl>
                                    </div>
                                    <div class="panel-footer">
                                        <div>Newest user:&nbsp;</div>
                                        @if(!$users->isEmpty())
                                            <div><a href="/users/{{ $users[0]->name }}" class='<?php echo strtolower(IndexController::getUserRoleName($users[0]->id)); ?>'><?php if(!empty($users[0])){ echo $users[0]->name; }else{ echo '-'; } ?></a></div>
                                        @else
                                            <div><p>-</p></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection