@php
    $class = isset($class) ? $class : '';
@endphp
<div class="article special">
    <div class="header">
        <div class="image align-self-center">
            <img src="{{$article->getPortadaLocation()}}" alt="{{ $article->getPortadaAlt() }}">
        </div>
        <div class="breadcrumbs">
            <a href="{{$article->sections[0]->getUrl()}}">{{trans('messages.'.$article->sections[0]->label)}}</a>
        </div>
        <div class="title"style="color:white">
            <h3 @if(strlen($article->nombre) > 60) class="long" @endif><a href="{{$article->getFirstUrl()}}" >{{$article->nombre}}</a></h3>
        </div>
    </div>
    <div class="content">
        
        <p>{{$article->intro}}</p>
        <span class="readmore portal"><a href="{{$article->getFirstUrl()}}">{{trans('messages.more')}}</a></span>
    </div>
</div>
