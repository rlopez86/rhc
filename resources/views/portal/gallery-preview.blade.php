<div class="preview-gallery">
    <a href="{{route('gallery',['id'=>$gallery->idgalerias])}}">
    <ul class="list-unstyled d-flex flex-wrap">
        @foreach($gallery->images as $img)
            @if($loop->index < 6)
    <li><img src="{{asset('galleries/'.$gallery->location.'/thumbnails/'.$img->location)}}" alt="{{$img->description ? $img->description : trans('messages.image').$loop->index}}"></li>
            @endif
        @endforeach
    </ul>
    </a>
</div>
