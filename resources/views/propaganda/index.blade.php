@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Propaganda
            </h2>
            <div class="d-inline-flex px-1">
                <a class="btn btn-lg btn-outline-success" href="{{route('propaganda-new')}}"><i class="mdi mdi-plus-circle-outline"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="row pb-4">
            <div>
                @foreach($languages as $language)
                <a href="{{route('propaganda-index', $language->abrev)}}" class="btn @if($language->abrev == $lang) btn-success @else btn-outline-success @endif mx-2">{{$language->idioma}}</a>
                @endforeach
            </div>
        </div>
        <div class="row pb-4">
            <div class="col">
                <table class="table" id="propaganda">
                    <thead>
                    <tr>
                        <th>Orden</th>
                        <th>Nombre</th>
                        <th>link</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ads as $ad)
                        <tr>
                            <td>
                                <a href="{{route('propaganda-up', $ad->idpropaganda)}}"><i class="mdi mdi-arrow-up"></i></a>
                                <a href="{{route('propaganda-down', $ad->idpropaganda)}}"><i class="mdi mdi-arrow-down"></i></a>
                                {{$ad->orden}}
                            </td>
                            <td>{{$ad->nombres}}</td>
                            <td><a href="{{$ad->link}}">{{substr($ad->link, 0, 50)}}</a></td>
                            <td><img src="{{asset($ad->recurso)}}"></td>
                            <td>
                                <a href="{{route('propaganda-toggle', $ad->idpropaganda)}}"><i class="mdi @if($ad->publicado) mdi-flag @else mdi-flag-outline @endif"></i></a>
                                <a href="{{route('propaganda-edit', $ad->idpropaganda)}}"><i class="mdi mdi-grease-pencil"></i></a>
                                <a href="{{route('propaganda-delete', $ad->idpropaganda)}}"><i class="mdi mdi-delete"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection