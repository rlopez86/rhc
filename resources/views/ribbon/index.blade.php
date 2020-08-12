@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Cintas
            </h2>
            <div class="d-inline-flex px-1">
                <a class="btn btn-lg btn-outline-success" href="{{route('ribbon-new')}}"><i class="mdi mdi-plus-circle-outline"></i></a>
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
            <div class="col">
                <table class="table" id="ribbon">
                    <thead>
                    <tr>
                        <th width="10%">Label</th>
                        <th width="70%">Preview</th>
                        <th>Position</th>
                        <th>Idioma</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ribbons as $ribbon)
                        <tr>
                            <td>{{$ribbon->label}}</td>
                            <td style="background-color:#A02733">{!! $ribbon->html !!}</td>
                            <td>{{$ribbon->position}}</td>
                            <td>{{$ribbon->idioma}}</td>
                            <td>
                                <a href="{{route('ribbon-publish', $ribbon->id)}}"><i class="mdi @if($ribbon->publicado) mdi-flag @else mdi-flag-outline @endif"></i></a>
                                <a href="{{route('ribbon-edit', $ribbon->id)}}"><i class="mdi mdi-grease-pencil"></i></a>
                                <a href="{{route('ribbon-delete', $ribbon->id)}}"><i class="mdi mdi-delete"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection