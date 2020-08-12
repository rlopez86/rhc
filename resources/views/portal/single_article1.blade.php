@php
    $class = isset($class) ? $class : '';
@endphp
<div class="article single position-relative h-100 {{$class}} @if($article->imagen) picture @else no-picture @endif @if($article->audio || $article->video) multimedia @endif" data-title="{{$article->nombre}}">
    <div class="header position-relative">
        <div class="breadcrumbs position-absolute p-1 text-uppercase">
            <a href="{{$article->sections[0]->getUrl()}}" class="section {{$article->sections[0]->label}}">{{trans('messages.'.$article->sections[0]->label)}}</a>
        </div>
        @if($article->audios || $article->video)
            <div class="controls position-absolute d-flex">
                @if($article->video)
                    <div class="trigger-video mr-1">
                        <a data-location="{{$article->video}}" href="#" data-toggle="modal" data-target="#videoModal"><img src="{{asset('/images/icons/video.png')}}" width="30"></a>
                    </div>
                @endif
            @if($article->audios && $article->imagen)
                <div class="trigger-audio mr-2">
                    <a href="#"><img src="{{asset('/images/icons/audio.png')}}" width="30"></a>
                </div>
            @endif

            </div>
        @endif
        @if($article->imagen)
            <div class="image">
                <img src="{{$article->getPortadaLocation()}}" alt="{{ $article->getPortadaAlt() }}" title="{{ $article->getPortadaAlt() }}">
            </div>
        @endif
        @if($article->audios && $article->imagen)
            <div class="control audio position-absolute d-none">
                <div class="media-wrapper article-player player-audio">
                    <audio preload="none" controls style="max-width:100%;">
                        <source src="{{asset($article->audios)}}" type="audio/mp3">
                    </audio>
                </div>
            </div>
        @endif

    </div>
    <div class="content @if($article->imagen) position-absolute @endif">
        <div class="title">
            <h2 @if(strlen($article->nombre) > 60) class="long" @endif><a href="{{$article->getFirstUrl()}}" class="no-style">{{$article->nombre}}</a></h2>
        </div>
        @if(!$article->imagen)
        <p>{{$article->intro}}</p>
        
        @endif
    </div>
</div>
