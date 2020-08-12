<!doctype html>
@php $locale = app()->getLocale() @endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <link rel='canonical' href="{{URL::current()}}">
    @yield('metas')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='shortcut icon' type='image/x-icon' href="{{asset('images/logo-color.png')}}" />
    @if(app()->getLocale() == 'ar')
        <link href="{{asset('vendor/bootstrap-rtl/bootstrap-rtl.css')}}" rel="stylesheet">
    @else
        <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    @endif
    <link href="{{asset('vendor/mediaelement/mediaelementplayer.css')}}" rel="stylesheet">
    <link href="{{asset('css/portal.css')}}" rel="stylesheet">
    <link href="{{asset('css/medias.css')}}" rel="stylesheet">
    <link href="{{asset('css/audio.css')}}" rel="stylesheet">

    @yield('styles')
    @yield('schema')
    <title>@yield('title', trans('messages.rhcfullname'))</title>
</head>
<body>
<div id="upper-header">
    <div class="container">
        <div class="row">
            <div class="col">
                <nav class="navbar navbar-dark navbar-expand-lg justify-content-end" id="social-languages">
                    <div class="nav-item dropdown mr-4">
                        <a class="dropdown-toggle" href="#" id="lang_dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{asset('images/icons/languages.png')}}" alt="World Icon">
                            <sub><img src="{{asset('images/icons/minidown.png')}}" class="trigger" alt="down"></sub>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="lang_dropdown">
                            @foreach($languages as $language)
                                @if($language->abrev == 'es')
                                    <a class="dropdown-item" href="{{url('/')}}">{{trans('lang.'.$language->abrev)}}</a>
                                @else
                                    <a class="dropdown-item" href="{{route('home', $language->abrev)}}">{{trans('lang.'.$language->abrev)}}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="my-2 my-lg-0">
                        <ul class="navbar-nav ml-auto social flex-row">
                            <li class="nav-item mr-1">
                                <a target="_blank" href="{{trans('messages.facebook')}}"><img src="{{asset('images/icons/facebook.png')}}" alt="facebook logo"></a>
                            </li>
                            
                            <li class="nav-item mx-2">
                                <a target="_blank" href="{{trans('messages.twitter')}}"><img src="{{asset('images/icons/twitter.png')}}" alt="twitter logo"></a>
                            </li>
                            <li class="nav-item mx-2">
                                <a target="_blank" href="{{trans('messages.instagram')}}"><img src="{{asset('images/icons/instagram.png')}}" alt="instagram logo"></a>
                            </li>
                            <li class="nav-item mr-1">
                                <a target="_blank" href="{{trans('messages.youtube')}}"><img src="{{asset('images/icons/youtube.png')}}" width="28" alt="rss logo"></a>
                            </li>
                            <li  class="nav-item ml-1">
                                <a target="_blank" href="{{url('/feed')}}"><img src="{{asset('images/icons/rss.ico')}}" alt="rss" width="31"></a>
                            </li>

                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>


<div id="header" style="@yield('banner')">
    <div class="container">
        
        <div class="row">
            <div class="col">
                
                <nav class="navbar navbar-dark navbar-expand-lg" id="upper">
                    @isset($main_section)
                        <a href="@if($locale == 'es'){{url('/')}} @else {{route('home', $locale)}}@endif"><img src="{{asset('images/logo.png')}}" height="40px" alt="RHC logo"></a>
                    @endisset
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto sections">
                            @foreach($sections as $sect)
                                @if(key_exists('children', $sect) && count($sect['children']) > 0)
                                    @include('portal.sections_menu', ['sections'=>$sect['children']])
                                @endif
                            @endforeach
                            
                        </ul>
                        
                        <form class="form-inline my-lg-0 d-flex" action="@if($locale == 'es') {{url('/search')}} @else {{route('get-search', $locale)}} @endif">
                            <input class="form-control mr-sm-2" type="search" name="query" placeholder="{{trans('messages.search')}}" aria-label="Search" id="upper-search">
                        </form>
                    </div>
                </nav>
                <div class="text-center mt-3 mb-5 @isset($main_section) d-none @endisset" id="title" >
                    <a href="@if($locale == 'es'){{url('/')}} @else {{route('home', $locale)}}@endif"><img src="{{asset('images/logo.png')}}" alt="logo"></a>
                    <h1>{{trans('messages.rhc')}}</h1>
                    <h6>{{trans('messages.slogan')}}</h6>
                </div>
                <div class="section-title">
                    @yield('section-title')
                </div>
            </div>
            
        </div>
        
    </div>
</div>
@if($url_path && count($url_path) > 1)
<div id = 'navigation_bar'class="container">
    <div class="row">
        <div class="col">
            <nav class="navigation_bar">
                @for($i = 0; $i < count($url_path)-1; $i++)
                    @if($url_path[$i][2])
                        <a href={{url($url_path[$i][0])}}>  {{trans('messages.'.$url_path[$i][1]) }} </a>
                    @else
                    <a href={{url($url_path[$i][0])}}>  {{$url_path[$i][1]}}   </a>
                    @endif
                    <a class = 'active'>/</a>
                    
                @endfor
                @if($url_path[count($url_path) -1][2])
                    <a class = 'active' >  {{trans('messages.'.$url_path[count($url_path) -1][1]) }}</a>
                @else
                    <a class = 'active' >  {{$url_path[count($url_path) -1][1]}}</a>
                @endif
            </nav>
        </div>
    </div>
</div>
@endif

<div id="content">
    <nav class="navbar navbar-expand-lg navbar-light sticky-top" id="alter" style="display: none">
        
        <div class="container">
            <a href="@if($locale == 'es'){{url('/')}} @else {{route('home', $locale)}}@endif"><img src="{{asset('images/logo-color.png')}}" height="40" class="pr-4" alt="RHC logo color"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto sections">
                    @foreach($sections as $sect)
                        @if(key_exists('children', $sect) && count($sect['children']) > 0)
                            @include('portal.sections_menu', ['sections'=>$sect['children']])
                        @endif
                    @endforeach
                </ul>
                <form class="form-inline my-lg-0 d-flex" action="@if($locale == 'es') {{url('/search')}} @else {{route('get-search', $locale)}} @endif">
                    <input class="form-control mr-sm-2" type="search" name="query" placeholder="{{trans('messages.search')}}" aria-label="Search" id="search">
                </form>
            </div>
        </div>
    </nav>
    @if(isset($notification))
    <div class="row" id="exalted">
        <div class="container">
            @yield('page_title')
            <!-- Implement here notifications -->
        </div>
    </div>
    @endif
    <div id="contenido_pagina" class="container body @isset($main_section)section @endif @isset($article)article @endif @isset($no_mic) no-mic @endif">
        <div class="row">
            @yield('columns')
        </div>
    </div>
    @yield('full-container')
</div>
@include('layouts.footer')
</body>
<script src="{{asset('vendor/jquery/jquery.js')}}" type="text/javascript"></script>
<script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendor/mediaelement/mediaelement-and-player.min.js')}}"></script>
<script src="{{asset('js/menu.js')}}" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        $('audio').mediaelementplayer({
            audioWidth: '100%',
            success: function (mediaElement, domObject) {
                mediaElement.addEventListener('ended', function (e) {
                    mejsPlayNext(e.target);
                }, false);
            },
            keyActions: []
        });
        var s_title = $('.section-title h1');
        var c_width = $('#content').width();
        if(s_title.width() > c_width){
            var current_size = parseInt(s_title.css('font-size'));
            while(s_title.width() > c_width){
                current_size-=3;
                s_title.css('font-size',current_size+'px');
                if(s_title.width() < 0)
                    break;
            }
        }
        $('#up-page').click(function (e) {
            e.preventDefault();
            $('html, body').animate({scrollTop : 0},800);
            return false;
        });
    });

</script>

<script src="{{asset('js/micro-image.js')}}" type="text/javascript"></script>
<script src="{{asset('js/banner-rotativo.js')}}" type="text/javascript"></script>
@yield('scripts')
</html>
