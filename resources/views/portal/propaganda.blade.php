<div id="propaganda">
    <ul class="d-flex flex-wrap justify-content-around">
        @foreach($propaganda as $prop)
            <li><a href="{{$prop->link}}"><img src="{{asset($prop->recurso)}}" alt="{{$prop->nombres}}" onerror="this.src='{{asset('images/relleno_square.png')}}'"></a></li>
        @endforeach
    </ul>
</div>