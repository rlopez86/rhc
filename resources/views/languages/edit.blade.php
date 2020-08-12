@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h2 class="col-md-12">
                @if(isset($language->id))
                    Editando Idioma <span style="color: #a8a8a8">[{{$language->name}}]</span>
                @else
                    Nuevo Idioma
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
        <form method="post" action="{{route('languages-save')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" value="{{$language->ididioma}}" name="ididioma">
            <div class="form-row">
                <div class="form-group col-md-6 mb-0">
                    <label for="idioma" class="col-form-label">Nombre</label>
                    <input class="form-control @if($errors->first('name')) border-danger @endif" name="idioma" type="text" id="idioma" value="{{old('idioma', $language->idioma)}}" required>
                    @if($errors->first('idioma'))
                    <p class="text-danger mb-0">{{$errors->first('idioma')}}</p>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="abrev" class="col-form-label">Abreviatura</label>
                    <input class="form-control @if($errors->first('abrev')) border-danger @endif" name="abrev" type="text" id="abrev" value="{{old('abrev', $language->abrev)}}" required>
                    @if($errors->first('abrev'))
                        <p class="text-danger mb-0">{{$errors->first('abrev')}}</p>
                    @endif
                </div>
            </div>
            <!-- TODO DISCUTIR
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="translation">Fichero de Traducción</label>
                    <input type="file" class="form-control-file" id="translation" name="translation">
                </div>
            </div>
            -->
            <div class="form-row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn-primary btn">Guardar</button>
                </div>
            </div>
        </form>
    </div>
@endsection