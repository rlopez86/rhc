<div id="more_index" class="index">
    <h1>{{trans('messages.more_views')}}</h1>
    <ul class="most_visited list-unstyled">
        @foreach($most_visited as $art)
        <li><a href="{{$art->getFirstUrl()}}">{{$art->nombre}}</a></li>
        @endforeach
    </ul>
</div>