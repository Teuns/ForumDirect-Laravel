<?php use \App\Http\Controllers\IndexController; ?>

@extends('layouts.app')

@section('pageTitle', $subforum->name)

@section('content')

<section>
    <div class="container pt-3 pb-3">
        @if (session('alert'))
            <div class="alert alert-primary" role="alert">
                {{ session('alert') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12 col-xl-8">
                <div class="panel">
                <div class="panel-header"><span class="panel-title">{{ $subforum->name }}</span>
                    @can('create-thread')
                        <a class="btn btn-primary" href="{{ route('create_thread', $subforum->id) }}" style="margin-left: auto;">New</a>
                    @else
                        <a class="btn btn-primary" href="/login" style="margin-left: auto;">Log in to create</a>
                    @endcan
                </div>
                <div class="panel-body">
                @foreach ($threads as $row)
                     <div class="list">
                        <div class="list-item">
                            <i class="mdi mdi-36px mdi-comment mdi-is-medium list-item-prefix"></i>
                            <div class="list-item-caption">
                            <a href="/threads/show/{{ $row->id }}-{{ $row->slug }}">{{ $row->title }}</a>
                            <span class="text-soft"><div>by <a href="/users/{{ IndexController::GetUserName($row->user_id) }}" class="{{ IndexController::GetUserRoleName($row->user_id) }}">{{ IndexController::getUserName($row->user_id) }}</a></div><div>{{ $row->created_at }}</div></span>
                        </div>
                        <div class="list-item-caption list-item-suffix">
                            {!! IndexController::getLastPostByThread($row->id) !!}
                        </div>
                    </div>
                </div>
                @endforeach
                @if($threads->isEmpty())
                    <div class="list-item"><span>No data to show here.</span></div>
                @endif
                </div>
                </div>
                <br><p><i>Powered by ForumDirect</i></p><br>
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
                                                <div class="list-item"><div class="list-item-caption"><a href="/threads/show/{{ $row->id }}-{{ $row->slug }}?action=lastpost">{{ str_limit($row->title, 50) }}</a><br><small>by <a href="/users/{{ IndexController::GetUserName($row->lastpost_uid) }}" class="{{ IndexController::getUserRoleName($row->lastpost_uid) }}">{{ IndexController::getUserName($row->lastpost_uid) }}</a> <span>created at {{ Carbon\Carbon::parse($row->lastpost_date)->diffForHumans() }}</span></small>
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
                                                    <div class="list-item"><div class="list-item-caption"><strong><a href='/users/{{ $item->name }}' class='{{ IndexController::getUserRoleName($item->id) }}'>{{ $item->name }}</a></strong></div></div>
                                                    <?php $resultstr[] = "<li><a href='/users/".$item->name."'>".$item->name."</a></li>"; ?>
                                                @endif
                                            @endforeach
                                            <?php if(!$resultstr){ echo "-"; } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-xl-12">
                                <div class="panel mb-3">
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
                                            <div><a href="/users/{{ $users[0]->name }}" class='<?php echo IndexController::getUserRoleName($users[0]->id); ?>'><?php if(!empty($users[0])){ echo $users[0]->name; }else{ echo '-'; } ?></a></div>
                                        @else
                                            <div><p>-</p></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection