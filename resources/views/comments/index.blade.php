@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Comentarios
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
            <table class="table table-bordered table-striped" id="comments">
                <thead>
                <tr>
                    <th width="25%">Artículo</th>
                    <th>Texto</th>
                    <th>Autor</th>
                    <th width="65">Fecha</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($comments as $c)
                    <tr>
                        <td>{{$c->articleData->nombre}}</td>
                        <td>{{$c->texto}}</td>
                        <td>{{$c->autor}}</td>
                        <td>{{$c->fecha}}</td>
                        <td>
                            @include('comments.row', ['comment'=>$c])
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
        $('#comments').dataTable({
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
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('comments-data')}}",
            "deferLoading": {{$count}},
            "order":[
                [3, 'desc']
            ],
            "columnDefs":[
                {
                    targets:[1,4],
                    sortable:false,
                    searchable:false
                }
            ]
        });
    });
</script>
@endsection