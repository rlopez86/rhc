@extends('layouts.app')
@section('styles')
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/jquery-timepicker/jquery.timepicker.min.css')}}" rel="stylesheet">
@endsection()
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="col-md-12">
                @if(isset($program->id))
                    Editando Programa <span style="color: #a8a8a8">[{{$program->name}}]</span>
                @else
                    Nuevo Programa
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
        <form method="post" action="{{route('schedule-save')}}">
            <div class="form-row">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$program->id}}">
                <div class="form-group col-md-3">
                    <label for="day">Día de la semana</label>
                    <select id="day" name="day" class="form-control" required>
                        <option value="0" @if(old('day', $program->day)== 0) selected @endif>Domingo</option>
                        <option value="1" @if(old('day', $program->day)== 1) selected @endif>Lunes</option>
                        <option value="2" @if(old('day', $program->day)== 2) selected @endif>Martes</option>
                        <option value="3" @if(old('day', $program->day)== 3) selected @endif>Miercoles</option>
                        <option value="4" @if(old('day', $program->day)== 4) selected @endif>Jueves</option>
                        <option value="5" @if(old('day', $program->day)== 5) selected @endif>Viernes</option>
                        <option value="6" @if(old('day', $program->day)== 6) selected @endif>Sábado</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="hour">Hora</label>
                    <input type="text" name="hour" id="hour" class="form-control" value="{{old('hour') ? old('hour') : $program->hour ? \Carbon\Carbon::createFromFormat('H:i:s', $program->hour)->format('h:i A') : ''}}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="name">Nombre del programa</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{old('name', $program->name)}}" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label for="description">Descripción</label>
                    <textarea id="description" name="description" class="form-control" required>{{old('description', $program->description)}}</textarea>
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
<script type="text/javascript" src="{{asset('vendor/jquery-timepicker/jquery.timepicker.min.js')}}"></script>
<script type="text/javascript">
    $(function(){
        $('#hour').timepicker({});
    });
</script>
@endsection