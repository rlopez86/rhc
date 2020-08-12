<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('metas')
    <link rel='shortcut icon' type='image/x-icon' href="{{asset('images/logo-color.png')}}" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{asset('vendor/jquery/jquery.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}" type="text/javascript"></script>

    <!-- Styles -->
    <link href="{{asset('vendor/material-design-webfont/css/materialdesignicons.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('vendor/datatables/css/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css">
    @yield('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel border-bottom">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @auth
                            @can('read', App\Article::class)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('articles-index')}}">Artículos</a>
                                </li>
                            @endcan
                            @can('read', App\Podcast::class)
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        Podcasts <span class="caret"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('programs-index', 'es') }}">Programas</a>
                                        <a class="dropdown-item" href="{{ route('podcasts-index') }}">Audios</a>
                                    </div>
                                </li>
                            @endcan
                            @can('manage', App\Gallery::class)
                                 <li class="nav-item">
                                     <a class="nav-link" href="{{route('galleries-index')}}">Galerías</a>
                                 </li>
                            @endcan
                            @can('read', App\Comment::class)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('comments-index')}}">Comentarios</a>
                                </li>
                            @endcan
                            @can('read', App\Comment::class)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('mails-index')}}">Correos</a>
                                </li>
                            @endcan
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Administrar <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                    @can('view', App\User::class)
                                        <a class="dropdown-item" href="{{route('users-index')}}">Usuarios</a>
                                    @endcan
                                    @can('edit', App\Language::class)
                                        <a class="dropdown-item" href="{{route('languages-index')}}">Idiomas</a>
                                    @endcan
                                    @can('edit', App\Section::class)
                                        <a class="dropdown-item" href="{{route('sections-index')}}">Secciones</a>
                                    @endcan
                                    @can('manage', App\Propaganda::class)
                                        <a class="dropdown-item" href="{{route('propaganda-index', 'es')}}">Propaganda</a>
                                    @endcan
                                    @can('manage', App\ProgramSchedule::class)
                                        <a class="dropdown-item" href="{{route('schedule-index', 1)}}">Programación</a>
                                    @endcan
                                    @can('manage', App\Registro::class)
                                        <a class="dropdown-item" href="{{route('bulletin-index')}}">Boletin</a>
                                    @endcan
                                    @can('manage', App\Ribbon::class)
                                        <a class="dropdown-item" href="{{route('ribbon-index')}}">Cintas</a>
                                    @endcan
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
@yield('scripts')
@yield('last_styles')
</html>
