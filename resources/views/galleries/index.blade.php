@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">Galerías</h2>
            <div class="d-inline-flex px-1">
                @can('manage', \App\Gallery::class)
                    <a class="btn btn-lg btn-outline-success" href="{{route('galleries-new')}}"><i class="mdi mdi-plus-circle-outline"></i></a>
                @endcan
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
            <table class="table table-bordered table-striped" id="galleries">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th width="50%">Descripción</th>
                    <th>Autor</th>
                    <th>Portada</th>
                    <th>Imágenes</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($galleries as $gallery)
                    <tr>
                        <td>{{$gallery->nombre}}</td>
                        <td>{{$gallery->descripcion}}</td>
                        <td>{{$gallery->author ? $gallery->author->name : 'Autor Desconocido'}}</td>
                        <td><img src="{{asset('galleries/'.$gallery->location.'/thumbnails/'.$gallery->getPortada())}}"></td>
                        <td>{{count($gallery->imagesJSON())}}</td>
                        <td>{{\Carbon\Carbon::createFromFormat(\Carbon\Carbon::DEFAULT_TO_STRING_FORMAT, $gallery->fechacreacion)->format('d/m/Y h:i a')}}</td>
                        <td>
                            <span><a href="{{route("galleries-publish", $gallery->idgalerias)}}" class="toggle"><i class="mdi @if($gallery->publicado) mdi-flag @else mdi-flag-outline @endif" ></i></a></span>
                            <span><a href="{{route('galleries-edit', $gallery->idgalerias)}}"><i class="mdi mdi-grease-pencil"></i></a></span>
                            <span><a href="{{route("galleries-delete", $gallery->idgalerias)}}"><i class="mdi mdi-delete"></i></a></span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(function(){
        var table = $('#galleries').dataTable({
            "language": {
                "sEmptyTable":     "No hay datos disponibles en la tabla",
                "sInfo":           "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                "sInfoEmpty":      "No hay entradas que mostrar",
                "sInfoFiltered":   "(Filtrado de _MAX_ entradas totales)",
                "sInfoPostFix":    "",
                "sInfoThousands":  ",",
                "sLengthMenu":     "Mostrar _MENU_ Entradas",
                "sLoadingRecords": "Cargando...",
                "sProcessing":     "Procesando...",
                "sSearch":         "Buscar:",
                "sZeroRecords":    "No se encontraron resultados",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Ultimo",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar ascendiente",
                    "sSortDescending": ": Activar para ordenar descendiente"
                }
            },
            "columnDefs":[
                {
                    targets:[1,4],
                    sortable:false,
                    searchable:false
                }
            ]
        });
        table.on('click', 'a.toggle', function(e){
            e.preventDefault();
            var ele = $(this);
            var icon = ele.find('i');
            $.get(ele.attr('href'), {}, function(data){
                icon.removeClass('mdi-flag').removeClass('mdi-flag-outline');
                if(data==="1"){
                    icon.addClass('mdi-flag')
                }
                if(data==="0"){
                    icon.addClass('mdi-flag-outline')
                }
            }).fail(function (xhr) {
                alert(xhr.statusText)
            });

        });
    });
</script>
@endsection
