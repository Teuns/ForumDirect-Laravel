@extends('layouts.admin')

@section('pageTitle', 'Home')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<div class="row">

    <!-- Total users Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count_users; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat messages (Total) Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Chat messages</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count_messages; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Threads (Total) Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Threads</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count_topics; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts (Total) Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Posts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $count_posts; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-12">

        <!-- Reports Card -->
        <div class="card mb-4">
            <div class="card-header">
                Reports
            </div>
            <div class="card-body">
                @if($count_reports)
                <div class="alert alert-danger" role="alert">
                    Note: some reports are waiting to being reviewed.&nbsp;<a href="/modcp">Please take a look and perform an action.</a>
                </div>
                @else
                    There are no reports to handle for you.
                @endif
            </div>
        </div>

        <!-- Forum information Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Forum information</h6>
            </div>
            <div class="card-body">
                The forum is running on PHP <?= PHP_VERSION; ?> and Laravel version <?= app()->version(); ?>. The forum version itself is 1.0.0.
            </div>
        </div>

    </div>

</div>
@endsection
