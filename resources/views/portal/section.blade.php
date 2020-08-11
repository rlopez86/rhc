@extends('layouts.master')
@section('side1')
    @include('portal.live')
    
@endsection
@section('side2')
    @include('portal.most_visited',['most_visited'=>$most_visited])
@endsection
@section('side3')
    @include('portal.propaganda')
@endsection
@section('side4')
    @include('portal.counter', ['counters'=>$counters])
@endsection
@if($main_section->banner)
@section('banner', 'background: linear-gradient(rgba(90, 112, 159, 0.5), rgba(6, 28, 58, 0.7)), url("../'.$main_section->banner.'") no-repeat center;background-size:cover;')
@endif
@section('section-title')
    <h1>{{trans('messages.'.$main_section->label)}}</h1>
@endsection
@section('title', trans('messages.rhc').' | '.trans('messages.'.$main_section->label))
@section('metas')
<meta name="keywords" content="{{trans('messages.'.$main_section->label.'-keywords')}}"/>
<meta name="description" content="{{trans('messages.'.$main_section->label.'-description')}}"/>
<meta property="og:image" content="{{asset('images/'.($main_section->banner ? $main_section->banner : 'section-default.png'))}}"/>
<meta property="og:description" content="{{trans('messages.'.$main_section->label.'-description')}}"/>
<meta property="og:locale" content="{{app()->getLocale()}}">
<meta property="og:title" content="{{'messages.'.$main_section->label}}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{URL::current()}}">
@stop
@section('content')
    <div class="row">
    
        <div class="col-12">
            @foreach($articles as $art)
            
                <div class="row">
                    <h1> </h1>
                    @include('portal.long_article', ['video'=>$art->video, 'audio'=>$art->audios, 'art'=>$art, 'picture'=>$art->imagen, 'right'=>$loop->index%2, 'section'=>$main_section])
                    <hr>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col">
            {{$articles->links()}}
        </div>
    </div>
    <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModal">RHC MULTIMEDIA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="data-title"></h6>
                    <div class="videoplayer">
                        <video id="videoPlayer" width="640" height="360" preload="none" style="max-width: 100%" controls poster="images/big_buck_bunny.jpg" playsinline webkit-playsinline>
                            <source src="http://clips.vorwaerts-gmbh.de/big_buck_bunny.mp4" type="video/mp4">
                            <track src="dist/mediaelement.vtt" srclang="en" label="English" kind="subtitles" type="text/vtt">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{asset('js/multimedia.js')}}"></script>
    <script type="text/javascript">
        $(function(){
            $('.article .header img, .article-long .image img').one('load', function(){
                if(this.naturalHeight >= this.naturalWidth){
                    $(this).addClass('vertical');
                }
            }).each(function () { //cached images don't fire load events, so we trigger it manually
                if(this.complete){
                    $(this).trigger('load');
                }
            });
        })

    </script>
@endsection
@section('schema')
<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "ItemList",
        "numberOfItems": "{{count($articles)}}",
        "itemListElement": [
            @foreach($articles as $arti)
            {
                "@type": "NewsArticle",
                "mainEntityOfPage": {
                    "@type": "WebPage",
                    "@id": "{{$arti->getFirstUrl()}}"
                  },
                "image": [
                    @if($arti->imagenFull())
                    "{{asset($arti->imagenFull()->location)}}",
                    @endif
                ],
                "datePublished": "{{$arti->created_at->format('c')}}",
                @if($arti->updated_at) "dateModified": "{{$arti->updated_at->format('c')}}",@endif
                "headline": "$arti->nombre",
                "author": {
                    "@type": "Person",
                    "name": "{{$arti->autornombre}}"
                  },
                   "publisher": {
                    "@type": "Organization",
                    "name": "Radio Habana Cuba",
                    "logo": {
                      "@type": "ImageObject",
                      "url": "{{asset('images/logo.png')}}"
                    }
                  }
            },
            @endforeach
        ]
    }
    </script>
@endsection
