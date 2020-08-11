@extends('layouts.master')
@section('styles')
    <link href="{{asset('vendor/lightbox/css/lightbox.css')}}" rel="stylesheet"/>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('vendor/lightbox/js/lightbox.js')}}"></script>
@endsection
@section('page_title')
    <h1 class="page_title mt-1">{{$gallery->nombre}}</h1>
@stop
@section('metas')
    <meta name="keywords" content="{{$gallery->tags}}"/>
    <meta name="description" content="{{$gallery->descripcion}}"/>
    <meta property="og:image" content="{{asset($gallery->images[0]->location)}}"/>
    <meta property="og:description" content="{!! $gallery->descripcion !!}"/>
    <meta property="og:locale" content="{{app()->getLocale()}}">
    <meta property="og:title" content="{{$gallery->nombre}}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{URL::current()}}">
@stop
@section('title', trans('messages.rhc').' | '.trans('messages.galerias').' | '.$gallery->nombre))
@section('full-container')
    <div class="row">
        <div class="col-12 text-center">
            <h1>{!! $gallery->nombre !!}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center text-muted" id="gallery-description">
            <p>{!! $gallery->descripcion !!}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 d-flex flex-wrap align-items-baseline justify-content-center">
            @foreach($gallery->images as $image)
                <a href="{{asset('galleries/'.$gallery->location.'/'.$image->location)}}" class="gallery-item" data-lightbox="place">
                    <figure class="figure m-3">
                        <img src="{{asset('galleries/'.$gallery->location.'/thumbnails/'.$image->location)}}" class="figure-img img-fluid rounded" alt="{{$image->descripcion ? $image->descripcion : trans('messages.image').' '.$loop->index}}">
                    </figure>
                </a>
            @endforeach
        </div>
    </div>
@stop
@section('schema')
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ImageGallery",
      "name": "{{$gallery->nombre}}",
      "photo": [
        @foreach($gallery->images as $image)
        {
          "@type": "ImageObject",
          "url": "{{asset('galleries/'.$gallery->location.'/'.$image->location)}}",
          "name": "{{$image->descripcion ? $image->descripcion : trans('messages.image').' '.$loop->index}}"
        },
        @endforeach
        ]
    }
    </script>
@endsection
