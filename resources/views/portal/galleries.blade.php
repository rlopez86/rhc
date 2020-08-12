@extends('layouts.master')
@section('side1')
    @include('portal.live')
@endsection
@section('side2')
    @include('portal.propaganda', ['propaganda'=>$propaganda])
@endsection
@section('side3')
    @include('portal.counter', ['counters'=>$counters])
@endsection
@section('styles')
    
@endsection
@section('scripts')
    
@endsection
@section('page_title')
    <h1 class="page_title mt-1"></h1>
@stop
@section('metas')
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta property="og:image" content=""/>
    <meta property="og:description" content=""/>
    <meta property="og:locale" content="{{app()->getLocale()}}">
    <meta property="og:title" content="">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{URL::current()}}">
@stop
@section('title', trans('messages.rhc'.' | '.trans('messages.galeria-p')))
@section('section-title')
    <h1>{{trans('messages.'.$main_section->label)}}</h1>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 text-center text-muted" id="gallery-description">
            <p></p>
        </div>
    </div>
    @foreach($galleries as $gallery)
    <div class="row mb-3 gallery">
        <div class="col-sm-12 col-md-4">
            <a href="{{route('gallery',['id'=>$gallery->idgalerias])}}" class="gallery-item">
                <img src="{{asset('galleries/'.$gallery->location.'/thumbnails/'.$gallery->getPortada())}}" class="figure-img img-fluid rounded" alt="{{$gallery->nombre ? $gallery->nombre : $gallery->descripcion}}" width="100%">
            </a>
        </div>
        <div class="col-sm-12 col-md-8">
            <a href="{{route('gallery',['id'=>$gallery->idgalerias])}}" class="d-block mb-2 title">{{$gallery->nombre}}</a>
            <p class="description">{{substr($gallery->descripcion, 0, 400).(strlen($gallery->descripcion) > 400 ? '...' : '')}}</p>
        </div>
    </div>
    @endforeach
    <div class="row">
        <div class="col">
            {{$galleries->links()}}
        </div>
    </div>
@stop
@section('schema')
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "itemListObject",
      "name": "",
      "photo": [
        @foreach($galleries as $gallery)
        {
          "@type": "ImageGallery",
          "url": "{{route('gallery',['id'=>$gallery->idgalerias])}}",
          "name": "{{$gallery->nombre}}"
        },
        @endforeach
        ]
    }
    </script>
@endsection