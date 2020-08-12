@extends('layouts.app')
@section('styles')
    <link href="{{asset('vendor/bootstrap-datepicker/bootstrap-datepicker.standalone.css')}}" rel="stylesheet">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="col-md-12">
                @if(isset($podcast->idaudio))
                    Editando Audio <span style="color: #a8a8a8">[{{$podcast->nombre}}]</span>
                @else
                    Nuevo Audio
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
        <form method="post" action="{{route('podcasts-save')}}">
            {{csrf_field()}}
            <input type="hidden" name="idaudio" value="{{$podcast->idaudio}}">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="idioma">Idioma</label>
                    <select class="form-control" id="idioma" name="idioma">
                        <option  value="-1" selected ></option>
                        @foreach($languages as $lang)
                            <option value="{{$lang->abrev}}" @if(old('idioma', $podcast->idioma) == $lang->abrev) selected @endif>{{$lang->idioma}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="categoria">Programa</label>
                    <select class="form-control" id="categoria" name="categoria">
                        <option idioma ="" value="-1" selected ></option>
                        @foreach($programs as $program)
                        <option idioma ="{{$program->idioma}}" value="{{$program->idprograma}}" @if(old('categoria', $podcast->categoria) == $program->idprograma) selected @endif>{{$program->nombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="fecha">Fecha</label>
                    <input type="text" name="fecha" id="fecha" class="form-control hasDatepicker" value="{{old('fecha', ($podcast->fecha ? \Carbon\Carbon::createFromFormat(\Carbon\Carbon::DEFAULT_TO_STRING_FORMAT, $podcast->fecha)->format('d/m/Y') : ''))}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{old('nombre', $podcast->nombre)}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion">{{old('descripcion', $podcast->descripcion)}}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label for="player1">Audio</label>
                    <div class="media-wrapper" id="player1">
                        <audio preload="none" controls style="max-width:100%;">
                            @if($podcast->externo)
                                <source src="{{$podcast->location}}" type="audio/mp3">
                            @else
                                <source src="{{asset($podcast->location)}}" type="audio/mp3">
                            @endif
                        </audio>
                    </div>
                </div>
                <div class="form-group col">
                    <label for="location_ext">Externo</label>
                    <input type="text" name="location_ext" id="location_ext" class="form-control" value="{{old('location_ext', ($podcast->externo ? $podcast->location : ''))}}">
                </div>
                <div class="form-group col">
                    <label for="location_local">Local</label>
                    <div class="server">
                        @if($podcast->externo)<a class="btn btn-outline-primary form-control discover">Descubrir en el servidor</a> @endif
                        <select class="form-control @if($podcast->externo )d-none @else trigger @endif" id="location_local" name="location_local">
                            @if(!$podcast->externo)<option value="{{$podcast->location}}" selected>{{$podcast->location}}</option>@endif
                        </select>
                        <p class="d-none" id="none">No se encontraron audios sin referencia en el servidor</p>
                    </div>
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
@section('scripts')
    <script type="text/javascript" src="{{asset('vendor/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript">
        $(function(){
            $('#idioma').change(function(){
                var idiom_to_show = $(this).val()
                $('#categoria option').each(function(){
                    if(idiom_to_show === '-1')
                        $(this).show()
                    else
                    ($(this).attr('idioma') != idiom_to_show) ? $(this).hide() : $(this).show()
                    //alert($(this).selected())
                })
                $('#categoria')[0].value = -1
            })
            $('.hasDatepicker').datepicker({
                format:'dd/mm/yyyy',
                orientation: 'left bottom'
            });
            var locals_loaded = false;
            $('.server').find('a, .trigger').click(function(e){
                if(locals_loaded)
                    return;
                $.get('{{route('podcasts-orphaned')}}', {}, function (data) {
                    $('a.discover').addClass('d-none');
                    if(data.length === 0){
                        $('#none').removeClass('d-none')
                    }
                    else{
                        $.each(data, function(index, value){
                            $('#location_local').append('<option value="'+value+'">'+value+'</option>')
                        });
                        $('#location_local').removeClass('d-none');
                    }
                    locals_loaded = true;
                })
            });
            $('#location_local').change(function(e){
                $('#location_ext').val('');
            });
        })
    </script>
@endsection