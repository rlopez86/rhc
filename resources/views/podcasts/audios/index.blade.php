@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Podcasts (Audios)
            </h2>
            @can('manage_podcasts', 'App\Podcast')
                <div class="d-inline-flex px-1">
                    <a class="btn btn-lg btn-outline-success" href="{{route('podcasts-new')}}"><i class="mdi mdi-plus-circle-outline"></i></a>
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
        <form method="post" action="{{route('podcasts-index')}}">
            {{csrf_field()}}
            <div class="form-row pb-4">
                <div class="form-group col-md-5">
                    <label for="categoria">Programa</label>
                    <select class="form-control" id="categoria" name="categoria">
                        <option value="0">Todos</option>
                        @foreach($programs as $program)
                            <option value="{{$program->idprograma}}" @if($program->idprograma == $program_id) selected @endif>{{$program->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-5">
                    <label for="idioma">Idioma</label>
                    <select class="form-control" id="idioma" name="idioma">
                        <option value="0">Todos</option>
                        @foreach($languages as $lang)
                            <option value="{{$lang->abrev}}" @if($lang->abrev === $l_abrev) selected @endif>{{$lang->idioma}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 pt-2">
                    <label></label>
                    <button class="btn btn-success form-control">Filtrar</button>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col">
                <table class="table" id="podcasts">
                    <thead>
                    <tr>
                        <th width="10%">Fecha</th>
                        <th>Nombre</th>
                        <th>Location</th>
                        <th>Programa</th>
                        <th>Idioma</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($podcasts as $podcast)
                        <tr>
                            <td>{{$podcast->fecha}}</td>
                            <td>{{$podcast->nombre}}</td>
                            <td>{{$podcast->location}}</td>
                            <td>{{$podcast->program->nombre}}</td>
                            <td>{{$podcast->idioma}}</td>
                            <td>
                                <a href="{{route('podcasts-toggle', $podcast->idaudio)}}"><i class="mdi @if($podcast->publicado) mdi-flag @else mdi-flag-outline @endif"></i></a>
                                <a href="{{route('podcasts-edit', $podcast->idaudio)}}"><i class="mdi mdi-grease-pencil"></i></a>
                                <a href="{{route('podcasts-delete', $podcast->idaudio)}}"><i class="mdi mdi-delete"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection