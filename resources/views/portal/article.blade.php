@extends('layouts.master')
@section('side1')
    @include('portal.live')
@endsection
@section('side2')
    @include('portal.most_visited',['most_visited'=>$most_visited])
@endsection
@if($main_section->banner)
    @section('banner', 'background: linear-gradient(rgba(90, 112, 159, 0.5), rgba(6, 28, 58, 0.7)), url("'.asset($main_section->banner).'") no-repeat center;background-size:cover;')
@endif
@section('styles')
    <link rel="stylesheet" href="{{asset('vendor/social_likes/social-likes.css')}}">
@endsection
@section('section-title')
    <h1>{{trans('messages.'.$main_section->label)}}</h1>
@endsection
@section('title', trans('messages.rhc').' | '.$article->nombre)
@section('metas')
    <meta name="keywords" content="{{$article->tags}}"/>
    <meta name="description" content="{{$article->metadesc}}"/>
    <meta property="og:title" content="{{$article->nombre}}"/>
    @if($article->imagen)
        <meta property="og:image" content="{{$article->getPortadaLocation()}}"/>
    @else
        <meta property="og:image" content="{{asset('images/logo-color.png')}}"/>
    @endif
    <meta property="og:description" content="{{$article->metadesc}}"/>
    <meta property="og:locale" content="{{app()->getLocale()}}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{URL::current()}}">
@endsection
@section('content')
    <div class="row">
        <div class="col-12 col-lg-11 article-full">
            <h1><a href="{{$article->getUrl($main_section)}}">{{$article->nombre}}</a></h1>
            <div class="social social-likes">
                <ul class="list-inline">
                    <li class="facebook" title="facebook">Facebook</li>
                    <li class="twitter" title="Twitter">Twitter</li>
                    <li><a href="{{route('download-pdf', $article->idarticulos)}}"><img src="{{asset('images/icons/pdf.png')}}"></a></li>
                </ul>
            </div>
            @if($article->imagenFull())
            <div class="main container">
                @if(str_contains($article->imagenFull()->location, 'articles'))
                    <img src="{{asset($article->imagenFull()->location)}}" alt="{{ $article->getPortadaAlt() }}">
                @else
                    <img src="{{asset('articles/'.$article->imagenFull()->location)}}" alt="{{ $article->getPortadaAlt() }}">
                @endif
            </div>
            @endif
            <div class="text">
                {!! $article->texto !!}
            </div>
            <div class="author mt-5">
                <p class="text-right text-muted">{{trans('messages.by')}} {{$article->autornombre}}</p>
            </div>
        </div>
    </div>
    <hr>
    <div class="row my-4">
        <div class="col-12 col-lg-11">
            <h4>{{trans('messages.related_articles')}}</h4>
            <ul class="list-unstyled related">
            @foreach($related as $r)
                @if($r->idarticulos != $article->idarticulos)
                    <li><a href="{{$r->getFirstUrl()}}">{{$r->nombre}}</a></li>
                @endif
            @endforeach
            </ul>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-12 col-lg-11">
            <h3>{{trans('messages.comentarios')}}</h3>
            <ul class="list-unstyled comments">
                @foreach($article->comments as $comment)
                    @if(!$comment->publicado) @continue @endif
                <li class="d-flex">
                    <div class="avatar mr-2">
                        <img src="http://www.gravatar.com/avatar/{{md5(trim(strtolower($comment->correo)))}}?s=80" alt="{{$comment->autor}}'s gravatar" onerror="this.src='http://radiohc.local/images/icons/noavatar.png'" width="80" height="80">
                    </div>
                    <div class="opinion w-100">
                        <div class="header d-md-flex justify-content-between">
                            <h6 class="text-dark font-weight-bold">{{$comment->autor}}</h6>
                            <span class="text-muted font-italic">{{\Carbon\Carbon::createFromFormat(\Carbon\Carbon::DEFAULT_TO_STRING_FORMAT, $comment->fecha)->format('d/m/Y h:i a')}}</span>
                        </div>
                        <p class="w-100">{{$comment->texto}}</p>
                    </div>

                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <hr/>
    <div class="row">
        @if(session()->get('sended'))
            <div class="col-12">
                <div class="alert alert-success my-3" role="alert">
                    {{trans('messages.comment_saved')}}
                </div>
            </div>
        @endif
        <div class="col-12 col-lg-11">
            <h5>{{trans('messages.leave_comment')}}</h5>
            <span class="text-muted">{{trans('messages.comment_all_fields_required')}}</span>
            <form name="comment" method="post" action="{{route('comment-send')}}">
                {{csrf_field()}}
                <input type="hidden" value="{{$article->idarticulos}}" name="articulo">
                <div class="form-row mt-3">
                <div class="col-md-4 mt-1">
                    <label for="nombre">{{trans('messages.your_name')}}</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="col-md-8 mt-1">
                    <label for="correo">{{trans('messages.your_mail')}}</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                    <small id="emailHelp" class="form-text text-muted">{{trans('messages.not_publish')}}</small>
                </div>
                <div class="col-md-12 mt-1">
                    <label for="texto">{{trans('messages.your_comment')}}</label>
                    <textarea class="form-control" id="texto" rows="3" name="texto" required></textarea>
                </div>
                <div class="col-md-6 mt-1">
                    <label for="captcha">{{trans('messages.captcha_label')}}</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <img src="{{route('render-captcha')}}" alt="captcha challenge">
                            </div>
                        </div>
                        <input type="text" id="captcha" name="captcha" class="form-control" required="">
                    </div>
                </div>
                <div class="col-md-6 text-right pt-md-5">
                    <button type="submit" class="btn btn-primary">{{trans('messages.submit_comment')}}</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('vendor/social_likes/social-likes.min.js')}}"></script>
@endsection
@section('schema')
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsArticle",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{URL::current()}}"
      },
      "headline": "{{$article->nombre}}",
      "image": [
        @if($article->imagenFull())
        "{{asset($article->imagenFull()->location)}}"
        @endif
       ],
      "datePublished": "{{$article->created_at->format('c')}}",
      @if($article->updated_at)"dateModified": "{{$article->updated_at->format('c')}}",@endif
      "author": {
        "@type": "Person",
        "name": "{{$article->autornombre}}"
      },
       "publisher": {
        "@type": "Organization",
        "name": "Radio Habana Cuba",
        "logo": {
          "@type": "ImageObject",
          "url": "{{asset('images/logo.png')}}"
        }
      }
    }
    </script>
@endsection
