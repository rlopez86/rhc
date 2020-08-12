<!doctype html>
@php $locale = app()->getLocale() @endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="{{trans('messages.podcasts-keywords')}}"/>
    <meta name="description" content="{{trans('messages.podcasts-description')}}"/>
    <meta property="og:image" content="{{asset('images/podcasts-image.png')}}"/>
    <meta property="og:description" content="{{trans('messages.podcasts-description')}}"/>
    <meta property="og:locale" content="{{app()->getLocale()}}">
    <meta property="og:title" content="{{trans('messages.podcasts')}}">
    <meta property="og:type" content="music">
    <meta property="og:url" content="{{URL::current()}}">
    <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/mediaelement/mediaelementplayer.css')}}" rel="stylesheet">
    <link href="{{asset('css/portal.css')}}" rel="stylesheet">
    <link href="{{asset('css/medias.css')}}" rel="stylesheet">
    <link rel='shortcut icon' type='image/x-icon' href="{{asset('images/logo-color.png')}}" />
    <title>@yield('title', trans('messages.rhc').' | '.trans('messages.podcasts'))</title>
</head>
<body id="podcasts">
    <div>
        <div class="header">
            <div id="content">
                <nav class="navbar navbar-expand-lg navbar-dark" id="upper">
                    <div class="container">
                        <a href="@if($locale == 'es'){{url('/')}} @else {{route('home', $locale)}}@endif"><img src="{{asset('images/logo.png')}}" height="40" alt="RHC logo"></a>
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
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="body container-fluid pt-lg-4">
            <div class="row">
                <div class="col-sm-4 col-md-5 text-lg-center">
                    <div class="title">
                        <h1>{{trans('messages.podcasts_audio')}}</h1>
                        <h2>{{trans('messages.podcasts_demanda')}}</h2>
                    </div>
                </div>
                <div class="col-sm-8 col-md-7 text-right">
                    <div class="wave">
                        <img src="{{asset('images/audio-wave.png')}}" width="100%">
                        <div class="media-wrapper transparent" id="main_player">
                            <audio preload="true" controls style="max-width:100%;">
                                <source src="{{asset('audios/test.mp3')}}" type="audio/mp3">
                            </audio>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-gutters">
                <div class="col-sm-4 col-md-5 pt-4 programs-list">
                    <a href='#' id="trigger-programs-open" class="p-2 text-uppercase d-md-none">{{trans('messages.programs')}}</a>
                    <div class="programs d-none d-md-block">
                        <a href='#' class="float-right mr-1 mb-4 d-md-none text-uppercase" id="trigger-programs-close">{{trans('messages.close')}}</a>
                        <div class="clearfix"></div>
                        <ul class="list-unstyled">
                            @foreach($programs as $prog)
                                @if($prog->audios->count() > 0)
                                    <li class="d-flex"><a href="#" data-id="{{$prog->idprograma}}" class="program">{{$prog->nombre}}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-sm-8 col-md-7 text-right podcasts-list">
                    <div class="podcasts" style="clear: both">
                        @foreach($programs as $prog)
                            <div class="audios" id="p{{$prog->idprograma}}" style="display: none">
                                <ul class="list-unstyled">
                                    @foreach($prog->audios as $audio)
                                        <li><a class='col-10' href="#" data-location="{{$audio->location}}" data-externo="{{$audio->externo}}">{{$audio->nombre}}</a>
                                            <a href="#"><img title="play"src="{{asset('images/icons/play.png')}}" class="pl-4 click"></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('layouts.footer')
</body>
<script src="{{asset('vendor/jquery/jquery.js')}}" type="text/javascript"></script>
<script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendor/mediaelement/mediaelement-and-player.min.js')}}"></script>
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

        $('#trigger-programs-close').click(function(e){
            e.preventDefault();
            $('.programs').addClass('d-none');
            $('#trigger-programs-open').css('opacity', 1);
        });
        $('#trigger-programs-open').click(function(e){
            e.preventDefault();
            $('.programs').removeClass('d-none');
            $(this).css('opacity', 0);
        });

        $('.program').click(function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('.audios').hide();
            $('.audios#p'+id).show();
            $('.program').removeClass('active');
            $(this).addClass('active');
            
        });
        $('.program:first').click();
        $('.audios li').click(function (e) {
            e.preventDefault();
            if($(this).find('a').hasClass('active')){
                $(this).find('img').attr('title','play');
                $(this).find('img').attr('src','/images/icons/play.png');
                $(this).find('a').removeClass('active').addClass('last-active');
                $('#main_player').find('audio').each(function(){
                this.player.pause();
                });
            }
            else{
                $('.audios').find('a').removeClass('active');
                $('.audios').find('img').attr('src','/images/icons/play.png');
                $('.audios').find('img').attr('title','play');
                $(this).find('img').attr('src','/images/icons/pause.png');
                $(this).find('img').attr('title','pause');
                $(this).find('a').addClass('active');
                var audio_src = $(this).find('a').data('location');
                if($(this).find('a').hasClass('last-active')){
                    $('#main_player').find('audio').each(function(){
                    this.player.play();
                    
                });
                }
                else{
                    $('.audios').find('a').removeClass('last-active');
                $('#main_player').find('audio').each(function(){
                    
                    this.player.pause();
                    this.player.setSrc(audio_src);
                    this.player.load();
                    this.player.play();
                    alert(this.player.getDuration())
                    
                });
            }}
            
        });
        $('#up-page').remove();
    });
</script>
