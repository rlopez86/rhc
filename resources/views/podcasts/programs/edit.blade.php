@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="col-md-12">
                @if(isset($program->idprograma))
                    Editando Programa <span style="color: #a8a8a8">[{{$program->nombre}}]</span>
                @else
                    Nuevo Programa
                @endif
            </h2>
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
        <form method="post" action="{{route('programs-save')}}">
            <div class="form-row">
                {{csrf_field()}}
                <input type="hidden" name="idprograma" value="{{$program->idprograma}}">
                <div class="form-group col-md-3">
                    <label for="idioma">Idioma</label>
                    <select id="idioma" name="idioma" class="form-control">
                        @foreach($languages as $lang)
                        <option value="{{$lang->abrev}}" @if(old('idioma', $program->idioma)== $lang->abrev) selected @endif>{{$lang->idioma}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-9">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre', $program->nombre)}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label for="extra">Extra</label>
                    <textarea name="extra" id="extra" class="form-control">{{old('extra', $program->extra)}}</textarea>
                </div>
            </div>
            <div class="form-row pt-3">
                <div class="col text-center">
                    <button type="submit" class="btn-primary btn">Guardar</button>
                </div>
            </div>
        </form>
    </div>
@endsection