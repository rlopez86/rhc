<div class="article @if($picture) picture @else no-picture @endif @if(isset($audio) || isset($video)) multimedia @endif">
    <div class="header">
        @if($picture)
        <div class="image">
        <img src="{{$article->getPortadaLocation()}}" alt="{{$article->getPortadaAlt()}}">
        </div>
        @endif
        <div class="breadcrumbs @if($picture) position-absolute @endif">
            @php $tree = $article->getSectionsTree()@endphp
            <ul class="list-inline">
            @foreach($tree as $section)
                <li><a href="{{$section->getUrl()}}">{{trans('messages.'.$section->label)}}</a></li>
            @endforeach
            </ul>
        </div>
        <div class="title @if($picture) position-absolute @endif">
            <h2 @if(strlen($article->nombre) > 60) class="long" @endif><a href="{{$article->getFirstUrl()}}" class="no-style">{{$article->nombre}}</a></h2>
        </div>
            @if($picture)
        <div class="sub">
            {{$article->imagenalt}}
        </div>
            @endif
        @if(isset($audio))
            <div class="control audio position-absolute d-none">
                <div class="media-wrapper article-player">
                    <audio preload="none" controls style="max-width:100%;">
                        <source src="{{asset('audios/test.mp3')}}" type="audio/mp3">
                    </audio>
                </div>
            </div>
        @endif
    </div>
    <div class="content">
        <p>
            {{$article->intro}}
        </p>
        <div class="more">
            <a href="{{$article->getFirstUrl()}}">{{trans('messages.more')}}</a>
            <hr>
            <hr>
        </div>
    </div>
</div>
