@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Programas
            </h2>
            @can('manage_programs', 'App\Podcast')
            <div class="d-inline-flex px-1">
                <a class="btn btn-lg btn-outline-success" href="{{route('programs-new')}}"><i class="mdi mdi-plus-circle-outline"></i></a>
            </div>
            @endcan
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
        <form>
            <div class="form-row pb-4">
                <div class="col">
                    @foreach($languages as $language)
                        <a href="{{route('programs-index', $language->abrev)}}" class="btn @if($language->abrev == $lang) btn-success @else btn-outline-success @endif mx-2">{{$language->idioma}}</a>
                    @endforeach
                </div>
            </div>
        </form>
        <div class="row pb-4">
            <div class="col">
                <table class="table" id="programs">
                    <thead>
                    <tr>
                        <th>Orden</th>
                        <th>Nombre</th>
                        <th>Idioma</th>
                        <th>Extra</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($programs as $program)
                        <tr>
                            <td>
                                <a href="{{route('programs-up', $program->idprograma)}}"><i class="mdi mdi-arrow-up"></i></a>
                                <a href="{{route('programs-down', $program->idprograma)}}"><i class="mdi mdi-arrow-down"></i></a>
                                {{$program->orden}}
                            </td>
                            <td>{{$program->nombre}}</td>
                            <td>{{$program->idioma}}</td>
                            <td>{{$program->extra}}</td>
                            <td>
                                <a href="{{route('programs-toggle', $program->idprograma)}}"><i class="mdi @if($program->habilitado) mdi-flag @else mdi-flag-outline @endif"></i></a>
                                <a href="{{route('programs-edit', $program->idprograma)}}"><i class="mdi mdi-grease-pencil"></i></a>
                                <a href="{{route('programs-delete', $program->idprograma)}}"><i class="mdi mdi-delete"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection