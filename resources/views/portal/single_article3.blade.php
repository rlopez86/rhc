@php
    $class = isset($class) ? $class : '';
@endphp
<div class="article single3"data-title="{{$article->nombre}}">
    <div class="header">
        @if($breadcrumbs)
        <div class="breadcrumbs position-absolute p-1 text-uppercase">
            <a href="{{$article->sections[0]->getUrl()}}" class="section {{$article->sections[0]->label}}">{{trans('messages.'.$article->sections[0]->label)}}</a>
        </div>
        @endif
        @if($article->imagen)
            <div class="image">
                <img src="{{$article->getPortadaLocation()}}" alt="{{ $article->getPortadaAlt() }}" title="{{ $article->getPortadaAlt() }}">
            </div>
        @endif
        <div class="title">
            <h3 @if(strlen($article->nombre) > 60) class="long" @endif><a href="{{$article->getFirstUrl()}}">{{$article->nombre}}</a></h3>
        </div>
    </div>
    @if($intro)
    <div class="content">
        <p>{{$article->intro}}</p>
    <span class="readmore portal"><a href="{{$article->getFirstUrl()}}">{{trans('messages.more')}}</a></span>
    </div>
    @endif
</div>
