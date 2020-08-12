@extends('layouts.app')
@section('styles')
    <link href="{{asset('vendor/bootstrap-datepicker/bootstrap-datepicker.standalone.css')}}" rel="stylesheet">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">
@endsection()
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Programación
            </h2>
            <div class="d-inline-flex px-1">
                <a class="btn btn-lg btn-outline-success" href="{{route('schedule-new')}}"><i class="mdi mdi-plus-circle-outline"></i></a>
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
        <div class="row pb-4">
            <div>
                <a href="{{route('schedule-index',0)}}" class="btn @if($day == 0) btn-success @else btn-outline-success @endif mx-2">Domingo</a>
                <a href="{{route('schedule-index',1)}}" class="btn @if($day == 1) btn-success @else btn-outline-success @endif mx-2">Lunes</a>
                <a href="{{route('schedule-index',2)}}" class="btn @if($day == 2) btn-success @else btn-outline-success @endif mx-2">Martes</a>
                <a href="{{route('schedule-index',3)}}" class="btn @if($day == 3) btn-success @else btn-outline-success @endif mx-2">Miercoles</a>
                <a href="{{route('schedule-index',4)}}" class="btn @if($day == 4) btn-success @else btn-outline-success @endif mx-2">Jueves</a>
                <a href="{{route('schedule-index',5)}}" class="btn @if($day == 5) btn-success @else btn-outline-success @endif mx-2">Viernes</a>
                <a href="{{route('schedule-index',6)}}" class="btn @if($day == 6) btn-success @else btn-outline-success @endif mx-2">Sábado</a>
            </div>
        </div>
        <div class="row pb-4">
            <div class="col">
                <table class="table" id="schedule">
                    <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($programs as $p)
                        <tr>
                            <td>{{\Carbon\Carbon::createFromFormat('H:i:s',$p->hour)->format('h:i A')}}</td>
                            <td>{{$p->name}}</td>
                            <td>{{$p->description}}</td>
                            <td>
                                <a href="{{route('schedule-toggle', $p->id)}}"><i class="mdi @if($p->habilitado) mdi-flag @else mdi-flag-outline @endif"></i></a>
                                <a href="{{route('schedule-edit', $p->id)}}"><i class="mdi mdi-grease-pencil"></i></a>
                                <a href="{{route('schedule-delete', $p->id)}}"><i class="mdi mdi-delete"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection