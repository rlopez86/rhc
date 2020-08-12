@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="col-md-12">
                @if(isset($section->idseccion))
                    Editando Sección <span style="color: #a8a8a8">[{{$section->nombre}}]</span>
                @else
                    Nueva Sección
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
        <form method="post" action="{{route('sections-save')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" value="{{$section->idseccion}}" name="idseccion">
            <div class="row">
                <div class="col">
                    <label for="nombre" class="col-form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" value="{{old('nombre', $section->nombre)}}">
                </div>
                <div class="col">
                    <label for="label" class="col-form-label">Label</label>
                    <input type="text" class="form-control" name="label" id="label" value="{{old('label', $section->label)}}">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="parent" class="col-form-label">Parent</label>
                    <select name="parent" id="parent" class="form-control">
                        @foreach($sections as $s)
                            @if(!(isset($section->idseccion) && $section->idseccion == $s->idseccion))
                            <option value="{{$s->idseccion}}" @if($section->parent == $s->idseccion)selected @endif>{{$s->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="languages" class="col-form-label">Idiomas</label>
                    <select name="languages[]" id="languages" class="form-control" multiple>
                        @foreach($languages as $l)
                            <option value="{{$l->ididioma}}" @if($section->languages->contains($l)) selected @endif>{{$l->idioma}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row no-gutters">
                <div class="col text-center my-3 py-3 border-bottom border-top">
                    <div class="current mb-3">
                    @if($section->banner)
                        <label for="current" class="col-form-label-md">Banner Actual</label>
                        <img src="{{asset($section->banner)}}" class="img-fluid">
                    @endif
                    </div>
                    <label class="col-form-label-md" for="banner">Actualizar Banner de la sección</label>
                    <input type="file" name="banner" id="banner">
                </div>
            </div>
            <div class="row pt-3">
                <div class="col text-center">
                    <button type="submit" class="btn-primary btn">Guardar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('styles')
    <link href="{{asset('vendor/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('scripts')
    <script src="{{asset('vendor/select2/js/select2.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $('select').select2();
    </script>
@endsection