@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="col-md-12">
                @if(isset($ribbon->id))
                    Editando Cinta <span style="color: #a8a8a8">[{{$ribbon->label}}]</span>
                @else
                    Nueva Cinta
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
        <form method="post" action="{{route('ribbon-save')}}" enctype="multipart/form-data">
            <div class="form-row">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$ribbon->id}}">
                <div class="form-group col-md-6">
                    <label for="label">Etiqueta</label>
                <input class="form-control" type="text" name="label" id="label" value="{{old('label', $ribbon->label)}}">
                </div>
                <div class="form-group col">
                    <label for="position">Position</label>
                    <select id="position" name="position" class="form-control">
                        <option value="1" @if($ribbon->position == 1)selected="selected" @endif>1 (Arriba) </option>
                        <option value="2" @if($ribbon->position == 2)selected="selected" @endif>2 (Abajo) </option>
                    </select>
                </div>
                <div class="form-group col">
                    <label for="idioma">Idioma</label>
                    <select id="idioma" name="idioma" class="form-control">
                        @foreach($languages as $lang)
                        <option value="{{$lang->abrev}}" @if(old('idioma', $ribbon->idioma)== $lang->abrev) selected @endif>{{$lang->idioma}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="html">HTML</label>
                    <textarea name="html" id="html" class="form-control" required="required" rows="10">{!! old('html', $ribbon->html) !!}</textarea>
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