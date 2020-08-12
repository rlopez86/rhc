@php $parts = explode('/', $url); @endphp
<iframe
        width="560"
        height="400"
        src="https://www.youtube.com/embed/{{$parts[count($parts)-1]}}"
        frameborder="0"
        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen>

</iframe>