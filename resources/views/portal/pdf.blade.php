@extends('layouts.big_master')
@if($main_section->banner)
    @section('banner', 'background: linear-gradient(rgba(90, 112, 159, 0.5), rgba(6, 28, 58, 0.7)), url("'.asset($main_section->banner).'") no-repeat center;background-size:cover;')
@endif
@section('section-title')
    <h1>{{trans('messages.'.$main_section->label)}}</h1>
@endsection
@php $no_mic = true;@endphp
@section('columns')
    <div class="col" id="us">
        <iframe style="width: 100%; height:500px;" src="{{route('render-pdf', $file)}}"></iframe>
    </div>
@endsection