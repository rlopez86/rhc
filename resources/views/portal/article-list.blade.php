<ul class="list-unstyled articles-list">
    @foreach($articles as $article)
        @if($loop->index < $max)
        <li class="d-flex">
            <div class="mr-2"><img src="{{asset('images/logo-p.png')}}" alt="RHC logo petite"></div>
            <div class="art">
                <a href="{{$article->getFirstUrl()}}">{{$article->nombre}}</a>
            </div>
        </li>
        @endif
    @endforeach
</ul>
@if(isset($section))
    <div class="text-right readmore">
        <a href="{{$section->getUrl()}}">{{trans('messages.more')}}</a>
    </div>
@endif
