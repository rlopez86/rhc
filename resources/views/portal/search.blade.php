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
@if($main_section->banner)
    @section('banner', 'background: linear-gradient(rgba(90, 112, 159, 0.5), rgba(6, 28, 58, 0.7)), url("../'.$main_section->banner.'") no-repeat center;background-size:cover;')
@endif
@section('section-title')
    <h1>{{trans('messages.'.$main_section->label)}}</h1>
@endsection
@section('styles')
    <link href="{{asset('vendor/jquery/jquery-ui.min.css')}}" rel="stylesheet">
@endsection
@section('metas')
    <meta name="keywords" content="{{trans('messages.search-keywords')}}"/>
    <meta name="description" content="{{trans('messages.search-description')}}"/>
@endsection
@section('title', trans('messages.rhc').' | '.trans('messages.search'))
@section('content')
    <div class="row">
        <div class="col-12">
            <form id="main-search" class="p-2 border mb-5" action="@if(app()->getLocale() == 'es') {{url('/search')}} @else {{route('get-search', app()->getLocale())}} @endif">
                <div class="form-row">
                    <div class="col-12 col-md-6">
                        <label for="query" class="sr-only">{{trans('messages.search')}}</label>
                        <input type="text" class="form-control" name="query" id="query" placeholder="{{trans('messages.search')}}" value="{{$query}}">
                    </div>
                    <div class="col-6 col-md-2 mt-2 mt-md-0">
                        <label for="from" class="sr-only">{{trans('messages.since')}}</label>
                        <input type="text" class="form-control" name="from" id="from" placeholder="{{trans('messages.since')}}" value="{{$from}}">
                    </div>
                    <div class="col-6 col-md-2 mt-2 mt-md-0">
                        <label for="to" class="sr-only">{{trans('messages.upto')}}</label>
                        <input type="text" class="form-control" name="to" id="to" placeholder="{{trans('messages.until')}}" value="{{$to}}">
                    </div>
                    <div class="col-12 col-md-2 mt-2 mt-md-0">
                        <button type="submit" class="btn btn-primary mb-2 w-100">{{trans('messages.search')}}</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12">
            @foreach($articles as $art)
                <div class="row">
                    @include('portal.long_article', ['art'=>$art, 'picture'=>$art->imagen,'audio'=>$art->audio,'video'=>$art->video, 'right'=>$loop->index%2, 'section'=>$art->sections[0]])
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
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('vendor/jquery/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/jquery/datepicker-'.app()->getLocale().'.js')}}"></script>
    <script type="text/javascript">
        var options = {
            changeMonth:true,
            changeYear:true
        };
        $('#from').datepicker(options);
        $('#to').datepicker(options);
    </script>
@endsection
@section('schema')
<script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "SearchResultsPage",
      "mainEntity": [{
        "@type": "ItemList",
        "name": "{{trans('messages.search').' '.$query}}",
        "itemListOrder": "http://schema.org/ItemListOrderAscending",
        "itemListElement":[
            @foreach($articles as $art)
            {
            "@type": "ListItem",
            "position": 1,
            "item": {
                "@type": "NewsArticle",
                "url": "{{$art->getFirstUrl()}}"
            }
            },
            @endforeach
        ]
      }]
    }
    </script>
    @endsection
