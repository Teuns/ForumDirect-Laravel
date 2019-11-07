<?php use \App\Http\Controllers\IndexController; ?>

<script type="application/javascript">
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
        'host' => 'https://server.teunstrik.com:2053',
    ]) !!};
</script>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title><?= config('app.name'); ?> - @yield('pageTitle')</title>

    <!-- Scripts -->
    <script type='text/javascript' src='//code.jquery.com/jquery-1.11.0.js'></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/to-markdown/3.0.4/to-markdown.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/markdown-it/8.3.1/markdown-it.min.js"></script>
    <script src="{{ asset('js/index.js?md5='.md5_file('js/index.js')) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplemde/1.11.2/simplemde.min.js" integrity="sha256-6sZs7OGP0Uzcl7UDsLaNsy1K0KTZx1+6yEVrRJMn2IM=" crossorigin="anonymous"></script>
    {!! NoCaptcha::renderJs() !!}

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css"/>

    <link rel="stylesheet" href="https://unpkg.com/@bybas/latte-ui@1.7.0/dist/latte-ui.css"/>
    
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">

    <link href="{{ asset('css/app.css?md5='.md5_file('css/app.css')) }}" rel="stylesheet">

    <link href="{{ asset('css/prism.css') }}" rel="stylesheet">
    
    <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body>
<main id="app">
  <div class="app-bar" role="toolbar" v-cloak/>
    <div class="container app-bar-row p-2">
      <button class="btn btn-icon btn-text ml-lg-2 mr-lg-1 d-block d-lg-none" aria-label="Toggle drawer" @click="$refs.drawer.open()"><i class="mdi mdi-menu"></i></button>

      <a href="/"><span class="app-bar-title">Forums</span></a>

      <nav class="nav nav-tabs px-lg-5 d-none d-lg-flex">
        <latte-ripple as="a" class="nav-link" href="/">Home</latte-ripple>
        <latte-ripple class="nav-link">Calender</latte-ripple>
        <latte-ripple class="nav-link">Search</latte-ripple>
      </nav>

      <div class="nav nav-tabs d-lg-flex d-none align-items-center ml-auto">
        <div class="divider divider-vertical"></div>
        @if (!Auth::check())
          <latte-ripple as="a" class="nav-link" href="/login">Account</latte-ripple>
        @else
          <latte-ripple as="a" class="nav-link" href="/users">Account</latte-ripple>
          @if(Auth::user() && strtolower(IndexController::getUserRoleName(Auth::id())) == 'administrator' || Auth::user() && strtolower(IndexController::getUserRoleName(Auth::id())) == 'moderator' || Auth::user() && strtolower(IndexController::getUserRoleName(Auth::id())) == 'developer')
            <latte-ripple as="a" class="nav-link" href="/modcp">ModCP</latte-ripple>
          @endif
          @if(Auth::user() && strtolower(IndexController::GetUserRoleName(Auth::id())) == 'administrator' || Auth::user() && strtolower(IndexController::getUserRoleName(Auth::id())) == 'developer')
            <latte-ripple as="a" class="nav-link" href="/admincp">AdminCP</latte-ripple>
          @endif
          <latte-ripple as="a" class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</latte-ripple>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
        @endif
        <latte-button-dropdown button-class="btn-icon btn-text" icon="dots-vertical">
          <nav class="nav nav-list">
            <latte-ripple as="a" class="nav-link" data-action="latte:switch-theme" data-close data-theme-id="default" onclick="setCookie('theme', 'default', 365);"><i class="mdi mdi-lightbulb-on"></i><span>Light mode</span></latte-ripple>
            <latte-ripple as="a" class="nav-link" data-action="latte:switch-theme" data-close data-theme-id="dark" onclick="setCookie('theme', 'dark', 365);"><i class="mdi mdi-invert-colors"></i><span>Dark mode</span></latte-ripple>
          </nav>
        </latte-button-dropdown>
      </div>
    </div>
    <div class="app-bar-row d-flex d-lg-none">
      <nav class="nav nav-tabs tabs-fill flex-grow-1">
         <latte-button-dropdown button-class="btn-icon btn-text" icon="dots-vertical">
          <nav class="nav nav-list">
            <latte-ripple as="a" class="nav-link" data-action="latte:switch-theme" data-close data-theme-id="default" onclick="setCookie('theme', 'default', 365);"><i class="mdi mdi-lightbulb-on"></i><span>Light mode</span></latte-ripple>
            <latte-ripple as="a" class="nav-link" data-action="latte:switch-theme" data-close data-theme-id="dark" onclick="setCookie('theme', 'dark', 365);"><i class="mdi mdi-invert-colors"></i><span>Dark mode</span></latte-ripple>
          </nav>
        </latte-button-dropdown>
      </nav>
    </div>
  </div>

<latte-sheet ref="drawer" class="drawer-container has-secondary">
      <div id="drawer" role="menu" @click="$refs.drawer.close()">
        <nav class="nav nav-list py-3" id="drawer-secondary">
          <div class="d-flex d-lg-none flex-column">
            <latte-ripple as="a" class="nav-link" href="/">Home</latte-ripple>
            <latte-ripple class="nav-link">Calender</latte-ripple>
            <latte-ripple class="nav-link">Search</latte-ripple>
            @if (Auth::check())
              <latte-ripple as="a" class="nav-link" href="/users">Account</latte-ripple>
              @if(Auth::user() && strtolower(IndexController::getUserRoleName(Auth::id())) == 'administrator' || Auth::user() && strtolower(IndexController::getUserRoleName(Auth::id())) == 'moderator' || Auth::user() && strtolower(IndexController::getUserRoleName(Auth::id())) == 'developer')
                <latte-ripple as="a" class="nav-link" href="/modcp">ModCP</latte-ripple>
              @endif
              @if(Auth::user() && strtolower(IndexController::getUserRoleName(Auth::id())) == 'administrator' || Auth::user() && strtolower(IndexController::getUserRoleName(Auth::id())) == 'developer')
                <latte-ripple as="a" class="nav-link" href="/admincp">AdminCP</latte-ripple>
              @endif
              <latte-ripple as="a" class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</latte-ripple>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            @else
              <latte-ripple as="a" class="nav-link" href="/login">Account</latte-ripple>
            @endif
          </div>
        </nav>
      </div>
    </latte-sheet>

  <!-- Body -->

  @if(Auth::user() && strtolower(IndexController::getUserRoleName(Auth::id())) == 'guest')

  <section>
    <div class="container my-3">
      <div class="notice notice-info" role="alert">
        Please activate your account.
      </div>
    </div>
  </section>

  @endif

  @yield('content')

  <footer class="footer py-5 bg-light">
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-4 text-center text-lg-left">
                    <p><strong>By Teun</strong></p>
                    <p>
                        Designed with Latte-UI.
                    </p>
                </div>
                <div class="col-12 col-lg-2 d-none d-lg-block"></div>
                <div class="col-12 col-lg mt-5 mt-lg-0 footer-nav text-center text-lg-left"><strong>Sitemap</strong>
                    <nav class="nav nav-links mt-3"><a href="/" class="nav-link is-not-really-active">Home</a><a href="#" target="_blank" class="nav-link">Search</a><a href="#" target="_blank" class="nav-link">Account</a></nav>
                </div>
                <div class="col-12 col-lg mt-5 mt-lg-0 footer-nav text-center text-lg-left"><strong>Account</strong>
                    <nav class="nav nav-links mt-3"><a href="/login" class="nav-link">Log in</a><a href="/register" class="nav-link">Register</a></nav>
                </div>
            </div>
        </div>
    </div>
  </footer>
</main>

<script src="{{ asset('js/prism.js') }}"></script>
<script src="{{ asset('js/app.js?md5='.md5_file('js/app.js')) }}"></script>

</body>

</html>
