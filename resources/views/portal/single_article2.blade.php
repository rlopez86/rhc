@php
    $class = isset($class) ? $class : '';
@endphp
<div class="row no-gutters article single2 border border-md-0 {{$class}} @if($article->imagen) picture @else no-picture @endif @if(isset($article->audio) || isset($article->video)) multimedia @endif " data-title="{{$article->nombre}}">
    <div class="header">
        @if($article->audios || $article->video)
            <div class="controls position-absolute d-flex">
                @if(isset($article->video))
                    <div class="trigger-video mr-1">
                        <a data-location="{{$article->video}}" href="#" data-toggle="modal" data-target="#videoModal"><img src="{{asset('/images/icons/video.png')}}" width="30"></a>
                    </div>
                @endif
                @if($article->audios && $article->imagen)
                    <div class="trigger-audio ">
                        <a href="#"><img src="{{asset('/images/icons/audio.png')}}" width="30"></a>
                    </div>
                @endif

            </div>
        @endif
        @if($article->imagen)
            <div class="breadcrumbs position-absolute p-1 text-uppercase">
                <a href="{{$article->sections[0]->getUrl()}}" alt="{{ $article->imagenalt }}" title="{{ $article->imagenalt }}" class="section {{$article->sections[0]->label}}">{{trans('messages.'.$article->sections[0]->label)}}</a>
            </div>
            <div class="image">
                <img src="{{$article->getPortadaLocation()}}">
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
        @endif
        
    </div>
    <div class="content ">
        <div class="insider p-1 pl-3 mt-2 border">
            <div class="title">
                <h3 @if(strlen($article->nombre) > 60) class="long" @endif><a href="{{$article->getFirstUrl()}}" class="no-style">{{$article->nombre}}</a></h3>
            </div>
            <p>{{$article->intro}}</p>
            <span class="readmore portal"><a href="{{$article->getFirstUrl()}}">{{trans('messages.more')}}</a></span>
            
        </div>
    </div>
</div>