@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Correos
            </h2>
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
            <div class="col-md-12 mb-5">
                <div class="border p-3" id="filters">
                    <h3>Filtros</h3>
                    @foreach($filters as $filter)
                        <span class="badge badge-light text-uppercase border"><span>{{$filter}}</span><a href="#" class="delete"><i class="mdi mdi-delete"></i></a></span>
                    @endforeach
                    <input type="text" class="filter ml-1 mt-1"><a href="#" id="new-filter"><i class="mdi mdi-plus"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <table class="table table-bordered table-striped" id="correos">
                <thead>
                <tr>
                    <th>Autor</th>
                    <th>Correo</th>
                    <th>Fecha</th>
                    <th width="50%">texto</th>
                    <th>País</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($mails as $mail)
                    <tr>
                        <td>{{$mail->autor}}</td>
                        <td>{{$mail->correo}}</td>
                        <td>{{\Carbon\Carbon::createFromFormat(\Carbon\Carbon::DEFAULT_TO_STRING_FORMAT, $mail->fecha)->format('d/m/Y h:i a')}}</td>
                        <td>{!! $mail->texto !!}</td>
                        <td>{{$mail->pais}}</td>
                        <td>
                            <span><a href="{{route("mails-publish", $mail->idcorreo)}}"><i class="mdi @if($mail->publicado) mdi-flag @else mdi-flag-outline @endif" ></i></a></span>
                            <span><a href="{{route("mails-delete", $mail->idcorreo)}}"><i class="mdi mdi-delete"></i></a></span>
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
        $('#correos').dataTable({
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
        var filter = $('input.filter');
        $('#new-filter').click(function(e){
            e.preventDefault();
            if(filter.val()) {
                $.get("{{route('mails-add_filter')}}", {'filter':filter.val()}, function (data) {
                    if(data){
                        filter.before('<span class="badge badge-light text-uppercase border"><span>'+data+'</span><a href="#" class="delete"><i class="mdi mdi-delete"></i></a></span>');
                        filter.val('');
                    }
                })
            }
        });
        $('#filters').on('click', 'a.delete', function (e) {
            e.preventDefault();
            var badge = $(e.target).parents('span');
            var filter = badge.find('span').text();
            $.get("{{route('mails-delete_filter')}}", {'filter':filter}, function (data) {
                if(data)
                    badge.remove();
            })
        })
    });
</script>
@endsection