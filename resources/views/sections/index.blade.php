@extends('layouts.app')
@section('styles')
    <link href="{{asset('css/admin.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Secciones
            </h2>
            <div class="d-inline-flex px-1">
                <a class="btn btn-lg btn-outline-success" href="{{route('sections-new')}}"><i class="mdi mdi-plus-circle-outline"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="sections-tree">
                <ul>
                @include('sections.recursive-list', ['sections'=>$sections['children'], 'padding'=>0])
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(function(){
            $('.confirm-delete').click(function(){
                return confirm('Al Eliminar la sección "'+$(this).data('nombre')+ '", También se ELIMINARÁN TODOS los artículos de esta sección. ESTÁ SEGURO???');
            });
        });
    </script>
@endsection