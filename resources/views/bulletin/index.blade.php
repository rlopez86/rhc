@extends('layouts.app')
@section('styles')
    <link href="{{asset('vendor/bootstrap-datepicker/bootstrap-datepicker.standalone.css')}}" rel="stylesheet">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">
@endsection()
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Registrados en el Boletin
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
            <table class="table table-bordered table-striped" id="registers">
                <thead>
                    <tr>
                        <th width="40">Email</th>
                        <th>Idioma</th>
                        <th>fecha</th>
                        <th width="65">Activo</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($registers as $register)
                    <tr>
                        <td>{{$register->email}}</td>
                        <td>{{$register->idioma}}</td>
                        <td>{{\Carbon\Carbon::createFromFormat('Y-m-d', $register->fecha)->format('d/m/Y')}}</td>
                        <td>
                            <a href="{{route('bulletin-toggle', $register->id)}}" class="toggle"><i class="mdi @if($register->activo) mdi-flag @else mdi-flag-outline @endif"></i></a>
                            <a href="{{route('bulletin-delete', $register->id)}}" class="delete"><i class="mdi mdi-delete"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript">
        $(function(){
            var table = $('#registers');
            var dtable = table.dataTable({
                "language":{
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
                "ajax": {
                    "url": "{{route('bulletin-data')}}",
                    "data": function(d){}
                },
                "deferLoading": {{$count}}
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
            table.on('click', 'a.delete', function(e){
                e.preventDefault();
                var ele = $(this);
                if(!confirm('Está seguro de eliminar este artículo? Esta Acción no es reversible.'))
                    return false;
                $.get(ele.attr('href'), {}, function(data){
                    if (data){
                        dtable.fnDeleteRow(ele.parents('tr'));
                    }
                }).fail(function (xhr) {
                    alert(xhr.statusText)
                });
            });
        });
    </script>
@endsection