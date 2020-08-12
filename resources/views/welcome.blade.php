@extends('layouts.master')
@section('metas')
    <meta name="keywords" content="{{trans('messages.rhc-keywords')}}"/>
    <meta name="description" content="{{trans('messages.rhc-description')}}"/>
    <link rel="alternate" type="application/atom+xml" title="Noticias: Radio Habana Cuba" href="{{(url(app()->getLocale() == 'es' ? '' : app()->getLocale()).'/feed')}}">
    <meta property="og:locale" content="{{app()->getLocale()}}">
    <meta property="og:title" content="{{trans('messages.rhcfullname')}}">
    <meta property="og:description" content="{{trans('messages.rhc-description')}}">
    <meta property="og:image" content="{{asset('images/logo-color.png')}}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{URL::current()}}">
    @endsection

@section('content')
    @foreach($ribbons as $ribbon)
    @if($ribbon->position == 1)
    <div class="row" id="exalted">
        <div class="container">{!!$ribbon->html!!}</div>
    </div>
    @endif
    @endforeach
    <div class="top news upper">
        <div class="row no-gutters">
            <div class="col-12 pb-2">
                <div class="main article picture"data-title="{{$portada['section1']->nombre}}">
                    <div class="header d-flex ">
                        @if($portada['section1']->imagen)
                        <div class="image">
                            <img src="{{$portada['section1']->getPortadaLocation()}}" alt="{{ $portada['section1']->imagenalt }}">
                            @if($portada['section1']->audios)
                            <div class="control audio position-absolute d-none">
                                <div class="media-wrapper article-player player-audio">
                                    <audio preload="none" controls style="max-width:100%;">
                                        <source src="{{asset($portada['section1']->audios)}}" type="audio/mp3">
                                    </audio>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                        @if($portada['section1']->audios || $portada['section1']->video)
                            <div class="controls position-absolute d-flex">
                                @if($portada['section1']->video)
                                    <div class="trigger-video mr-2">
                                        <a data-location="{{$portada['section1']->video}}"href="#" data-toggle="modal" data-target="#videoModal"><img src="{{asset('/images/icons/video.png')}}" width="30"></a>
                                    </div>
                                @endif
                            @if($portada['section1']->audios && $portada['section1']->imagen)
                                <div class="trigger-audio mr-2">
                                    <a href="#"><img src="{{asset('/images/icons/audio.png')}}" width="30"></a>
                                </div>
                            @endif

                            </div>
                        @endif
                        
                    </div>
                    <div class="content p-4">
                        <h2><a href="{{$portada['section1']->getFirstUrl()}}" class="no-style">{{$portada['section1']->nombre}}</a></h2>
                        <p>
                            {{$portada['section1']->intro}}...  <span class="readmore portal"><a href="{{$portada['section1']->getFirstUrl()}}">{{trans('messages.more')}}</a></span>
                        </p>
                        
                    </div>
                </div>
            </div>
            <div class="col-12 pb-3 col-md-4 pr-md-1">
                @include('portal.single_article1', ['article'=>$portada['section2']])
            </div>
            <div class="col-12 pb-3 col-md-4 px-md-2">
                @include('portal.single_article1', ['article'=>$portada['section3']])
            </div>
            <div class="col-12 pb-3 col-md-4 pl-md-1">
                @include('portal.single_article1', ['article'=>$portada['section4']])
            </div>
        </div>
    </div>
    <div class="row pb-3">
        <div id="nationals" class="big-section-block col-12 col-md-6">
            <div class="row no-gutters">
                <h2 class="mb-3">
                    {{trans('messages.nacionales')}}
                </h2>
            </div>
            <div class="row no-gutters mb-4">
                <div class="col-12 pb-3 ">
                    @include('portal.single_article2', ['article'=>$nationals[0]])
                </div>
                <div class="col-12 pb-3">
                    @include('portal.single_article2', ['article'=>$nationals[1]])
                </div>
            </div>
            @include('portal.article-list', ['articles'=>$nationals->except([0,1]), 'section'=>\App\Section::where('label', 'nacionales')->first(), 'max'=>5])
        </div>
        <div id="internationals" class="big-section-block col-12 col-md-6 border-left">
            <div class="row no-gutters">
                <h2 class="mb-3">
                    {{trans('messages.internacionales')}}
                </h2>
            </div>
            <div class="row no-gutters mb-3">
                <div class="col-12 pb-3">
                    @include('portal.single_article2', ['article'=>$internationals[0]])
                </div>
                <div class="col-12 pb-3">
                    @include('portal.single_article2', ['article'=>$internationals[1]])
                </div>
            </div>
            @include('portal.article-list', ['articles'=>$internationals->except([0,1]), 'section'=>\App\Section::where('label', 'internacionales')->first(), 'max'=>5])
        </div>
    </div>

    <div class="sections-block-comments">
        <div class="sections-arts">
            <div class="row no-gutters">
                <div class="col-6 col-md-3 section-title">
                    <h2 class="mb-1">
                        <a href="{{\App\Section::where('label', 'comentarios')->first()->getUrl()}}">{{trans('messages.comentarios')}}</a>
                    </h2>
                </div>
            </div>
            <div id="comments-block">
                <div class="row">
                    <div class="col-12 mb-2 mb-md-0 col-md-4">
                        @include('portal.special_article', ['article'=>$comments[0]])
                    </div>
                    <div class="col-12 mb-2 mb-md-0 col-md-4">
                        @include('portal.special_article', ['article'=>$comments[1]])
                    </div>
                    <div class="col-12 mb-2 mb-md-0 col-md-4">
                        @include('portal.special_article', ['article'=>$comments[2]])
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="mt-4 sections-block">
        <div class="section-arts">
            <div class="row no-gutters">
                <div class="col-6 col-md-3 section-title">
                    <h2 class="mb-1">
                        <a href="{{\App\Section::where('label', 'deportes')->first()->getUrl()}}">{{trans('messages.deportes')}}</a>
                    </h2>
                </div>
            </div>
            <div class="row no-gutters pt-2 articles">
                @php $intro = true @endphp
                <div class="col-12 col-md-4 pr-md-2">
                    @include('portal.single_article3', ['article'=>$sports[0], 'breadcrumbs'=>false, $intro])
                </div>
                <div class="col-12 col-md-4 pr-md-1">
                    @include('portal.single_article3', ['article'=>$sports[1], 'breadcrumbs'=>false, $intro])
                </div>
                <div class="col-12 col-md-4 pl-md-1">
                    @include('portal.single_article3', ['article'=>$sports[2], 'breadcrumbs'=>false, $intro])
                </div>
                <div class="col-12 py-4 text-right ">
                    @include('portal.article-list', ['articles'=>$sports->except([0,1,2]), 'max'=>5])
                    <span class="readmore "><a href="{{$sports[0]->sections[0]->getUrl()}}">{{trans('messages.more')}}</a></span>
                </div>
           
            </div>
        </div>
    </div>
    <div class="sections-block-exclusives">
        <div class="sections-arts">
            <div class="row no-gutters">
                <div class="col-6 col-md-3 section-title">
                    <h2 class="mb-1">
                        <a href="{{\App\Section::where('label', 'exclusivas')->first()->getUrl()}}">{{trans('messages.exclusivas')}}</a>
                    </h2>
                </div>
            </div>
            <div id="exclusives-block">
                <div class="row">
                    <div class="col-12 mb-2 mb-md-0 col-md-4">
                        @include('portal.special_article', ['article'=>$exclusives[0]])
                    </div>
                    <div class="col-12 mb-2 mb-md-0 col-md-4">
                        @include('portal.special_article', ['article'=>$exclusives[1]])
                    </div>
                    <div class="col-12 mb-2 mb-md-0 col-md-4">
                        @include('portal.special_article', ['article'=>$exclusives[2]])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 sections-block">
        <div class="section-arts">
            <div class="row no-gutters">
                <div class="col-6 col-md-3 section-title">
                    <h2 class="mb-1">
                        <a href="{{\App\Section::where('label', 'cultura')->first()->getUrl()}}">{{trans('messages.cultura')}}</a>
                    </h2>
                </div>
            </div>
            <div class="row no-gutters pt-2 articles">
                @php $intro = true @endphp
                <div class="col-12 col-md-4 pr-md-2">
                    @include('portal.single_article3', ['article'=>$culture[0], 'breadcrumbs'=>false, $intro])
                </div>
                <div class="col-12 col-md-4 pr-md-1">
                    @include('portal.single_article3', ['article'=>$culture[1], 'breadcrumbs'=>false, $intro])
                </div>
                <div class="col-12 col-md-4 pl-md-1">
                    @include('portal.single_article3', ['article'=>$culture[2], 'breadcrumbs'=>false, $intro])
                </div>
                <div class="col-12 py-4 text-right">
                    @include('portal.article-list', ['articles'=>$culture->except([0,1,2]), 'max'=>5])
                    <span class="readmore"><a href="{{$culture[0]->sections[0]->getUrl()}}">{{trans('messages.more')}}</a></span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModal">RHC MULTIMEDIA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="data-title"></h6>
                    <div class="videoplayer">
                        <video id="videoPlayer" width="640" height="360" preload="none" style="max-width: 100%" controls playsinline webkit-playsinline>
                            <source src="" type="video/mp4">
                            <track src="dist/mediaelement.vtt" srclang="en" label="English" kind="subtitles" type="text/vtt">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('full-container')
    @foreach($ribbons as $ribbon)
    @if($ribbon->position == 2)
    <div class="row" id="exalted">
        <div class="container">{!!$ribbon->html!!}</div>
    </div>
    @endif
    @endforeach
    <div class="container">
        @include('portal.latest_news', ['latest'=>$latest])
        @include('portal.multimedia_block', ['multimedia'=>$multimedia])
    </div>
@stop
@section('side1')
    @include('portal.live')
    @include('portal.ivoox')
    <div class="twitter-container my-3">
        @include('portal.twitter')
    </div>
@endsection
@section('side2')
    @include('portal.most_visited',['most_visited'=>$most_visited])
@endsection
@section('side3')
    @include('portal.propaganda')
@endsection
@section('side4')
    @include('portal.counter')
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('js/multimedia.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('.article.single2').each(function(index,element){
                var image = $(this).find('.image img');
                if(image.length === 0)
                    return
                var source = image.attr('src');
                
                if(image[0].naturalHeight > image[0].naturalWidth ){
                    $(this).addClass('vertical')
                    $(this).find('.header').addClass('col-md-12');
                    $(this).find('.header').addClass('col-xl-6');
                    $(this).find('.content').addClass('col-md-12');
                    $(this).find('.content').addClass('col-xl-6');
                }
            });
            var maximo = 180;
                $('.article.single3 p').each(function(){
                    if($(this).html().length < maximo)
                        maximo = $(this).html().length;
                   
                });
            $('.article.single3').each(function(index,element){
                var intro = $(this).find('.content p');
                var parent = intro.parent()
                var p_intro = intro.html();
                var array_split_intro = p_intro.split(' ')
                var new_intro = '';
                var i = 0;
                var j = 0;
                
                while(j <= maximo-8)
                {  
                    if(j + array_split_intro[i].length >= maximo -8)                      
                        break
                    if(array_split_intro[i] != ' ')
                    new_intro += array_split_intro[i] + ' ';
                    i++;
                    j= new_intro.length;
                }
                
                var readmore = parent.find('.readmore')
                new_intro += '... ' + '<span class="readmore portal">' + readmore.html() + '</span>'
                readmore.remove();
                
                intro.html(new_intro)
             });
             var maximo = 180;
                $('.article.special p').each(function(){
                    if($(this).html().length < maximo)
                        maximo = $(this).html().length;
                   
                });
            $('.article.special').each(function(index,element){
                var intro = $(this).find('.content p');
                var parent = intro.parent()
                var p_intro = intro.html();
                var array_split_intro = p_intro.split(' ')
                var new_intro = '';
                var i = 0;
                var j = 0;
                
                while(j <= maximo-8)
                {  
                    if(j + array_split_intro[i].length >= maximo -8)                      
                        break
                    if(array_split_intro[i] != ' ')
                    new_intro += array_split_intro[i] + ' ';
                    i++;
                    j= new_intro.length;
                }

                var readmore = parent.find('.readmore')
                new_intro += '... ' + '<span class="readmore portal">' + readmore.html() + '</span>'
                readmore.remove();
                
                intro.html(new_intro)
             });
            var maximo = 180;
                $('.article.single2 p').each(function(){
                    if($(this).html().length < maximo)
                        maximo = $(this).html().length;
                   
                });
            $('.article.single2').each(function(index,element){
                var intro = $(this).find('.content p');
                var parent = intro.parent()
                var p_intro = intro.html();
                var array_split_intro = p_intro.split(' ')
                var new_intro = '';
                //intro.html(new_intro);
                var i = 0;
                var j = 0;
                while(j <= maximo-8)
                {       
                    if(j + array_split_intro[i].length >= maximo -8)                      
                        break
                    if(array_split_intro[i] != ' ')
                    new_intro += array_split_intro[i] + ' ';
                    i++;
                    j= new_intro.length;
                   
                }
                
                var readmore = parent.find('.readmore')
                new_intro += '... ' + '<span class="readmore portal">' + readmore.html() + '</span>'
                readmore.remove();
                
                intro.html(new_intro)
            });
            var mainImg = $('.main.article').find('.image img');
            if(mainImg.length === 0)
                return;
            mainImg.one("load", function() {
                if(mainImg[0].naturalHeight>mainImg[0].naturalWidth){
                    mainImg.attr('style', 'height:385px;width:auto;');
                    $('.main.article').addClass('d-flex');
                }
            });
            if(mainImg[0].complete) {
                mainImg.load();
            }
        })
    </script>
@endsection
@section('schema')
<script type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","name":"Radio Habana Cuba","url":"http://www.radiohc.cu","address":"Infanta 105 entre 25 y San Francisco, La Habana, Cuba","sameAs":["https://www.facebook.com/RadioHabanaCuba","https://www.instagram.com/RadioHabanaCuba/","https://twitter.com/RadioHabanaCuba"]}</script>
@endsection
