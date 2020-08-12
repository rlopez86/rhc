@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Idiomas
            </h2>
            <div class="d-inline-flex px-1">
                <a class="btn btn-lg btn-outline-success" href="{{route('languages-new')}}"><i class="mdi mdi-plus-circle-outline"></i></a>
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
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped" id="languages">
                    <thead>
                    <tr>
                        <th>Idioma</th>
                        <th>Abreviatura</th>
                        <th>Default</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($languages as $l)
                        <tr>
                            <td>{{$l->idioma}}</td>
                            <td>{{$l->abrev}}</td>
                            <td>
                                @if($l->default)
                                    <i class="mdi mdi-checkbox-marked-outline"></i>
                                @else
                                    <a href="{{route('languages-default', $l->ididioma)}}"><i class="mdi mdi-checkbox-blank-outline"></i></a>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{route('languages-enable', $l->ididioma)}}"><i class="mdi @if($l->habilitado) mdi-flag @else mdi-flag-outline @endif"></i></a>
                                <a href="{{route('languages-edit', $l->ididioma)}}"><i class="mdi mdi-grease-pencil"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <p>Al crear un nuevo idioma, usted debe proveer la traducción de los textos del portal. En caso de faltar
                la traducción para algun texto el idioma inglés será utilizado en su lugar, aqui debajo se provee el fichero
                    que debe completar con los textos en español. <strong>Solo debe cambiar los textos a la derecha del simbolo "=>"
                        y respetar las comillas en todos los casos</strong>. Una vez completada la traduccion debe subir el fichero
                modificado pero con el mismo nombre en la interfaz de edición del nuevo idioma.</p>

                <a href="{{route('languages-file-download')}}" class="pr-4">Descargar Textos Generales</a>
                <a href="{{route('languages-file-download')}}">Descargar Textos de Error</a>
            </div>
        </div>
    </div>
@endsection