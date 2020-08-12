@extends('layouts.app')
@section('styles')
    <link href="{{asset('vendor/bootstrap-datepicker/bootstrap-datepicker.standalone.css')}}" rel="stylesheet">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">
@endsection()
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Artículos
            </h2>
            <div class="d-inline-flex px-1">
                @can('create', \App\Article::class)
                    <a class="btn btn-lg btn-outline-success mr-2" href="{{route('articles-organize')}}"><i class="mdi mdi-desktop-mac"></i></a>
                @endcan
                @can('create', \App\Article::class)
                    <a class="btn btn-lg btn-outline-success" href="{{route('articles-new')}}"><i class="mdi mdi-plus-circle-outline"></i></a>
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
        <form id="filtros" class="border p-3 mb-4">
            <h5>Filtros</h5>
            <hr>
            <div class="form-row">
                <div class="col-2">
                    <label> Mostrar artículos desde</label>
                    <input type="text" name="desde" id="desde" class="hasDatepicker form-control">
                    <input type="text" name="hasta" id="hasta" class="hasDatepicker form-control mt-2">
                </div>
                <div class="col-3 pl-4">
                    <label for="pub">Publicación</label>
                    <select name="pub" id="pub" class="form-control">
                        <option value="-1">Todos</option>
                        <option value="1">Solo Publicados</option>
                        <option value="0">Solo Sin Publicar</option>
                    </select>
                </div>
                <div class="col-3">
                    <label for="section">Sección</label>
                    <select name="section" id="section" class="form-control">
                        <option value="0">Cualquiera</option>
                    @foreach($sections as $section)
                        @if($section->idseccion != 1)
                            <option value="{{$section->idseccion}}">{{$section->nombre}}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
                <div class="col-2">
                    <label for="language">Idioma</label>
                    <select name="language" id="language" class="form-control">
                        <option value="0">Cualquiera</option>
                        @foreach($languages as $language)
                            <option value="{{$language->abrev}}">{{$language->idioma}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2 p-3 text-right">
                    <button class="btn btn-primary" id="active">Aplicar</button>
                </div>
            </div>
        </form>
        <div class="row">
            <table class="table table-bordered table-striped" id="articles">
                <thead>
                    <tr>
                        <th width="40">M</th>
                        <th>A</th>
                        <th>Nombre</th>
                        <th width="65">Fecha</th>
                        <th>Autor</th>
                        <th>Secciones</th>
                        <th>Idioma</th>
                        
                    </tr>
                </thead>
                <tbody>
                @foreach($articles as $article)
                    <tr>
                        <td>
                            @include('articles.cell_m', ['article'=>$article])
                        </td>
                        <td>
                            @include('articles.cell_a', ['article'=>$article])
                        </td>
                        <td>@include('articles.cell_nombre', ['article'=>$article])</td>
                        <td>{{$article->fecha}}</td>
                        <td>{{$article->autornombre}}</td>
                        <td>{{$article->sections->implode('nombre', ',')}}</td>
                        <td>{{$article->idioma}}</td>
                        
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
            var table = $('#articles');
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
                    "url": "{{route('articles-data')}}",
                    "data": function(d){
                        if(filters.hasClass('active')){
                            d.from = $('#desde').val();
                            d.to = $('#hasta').val();
                            d.pub = $('#pub').val();
                            d.section = $('#section').val();
                            d.language = $('#language').val();
                        }
                    }
                },
                "deferLoading": {{$count}},
                "order":[
                    [3, 'desc']
                ],
                "columnDefs":[
                    {
                        targets:[0,1,5],
                        sortable:false,
                        searchable:false
                    }
                ]
            });
            $('.hasDatepicker').datepicker({
                format:'dd/mm/yyyy'
            });
            var active = $('#active');
            var filters = $('#filtros');
            active.click(function(e){
                e.preventDefault();
                filters.toggleClass('active');
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