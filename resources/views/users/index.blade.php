@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Usuarios
            </h2>
            <div class="d-inline-flex px-1">
                <a class="btn btn-lg btn-outline-success" href="{{route('users-new')}}"><i class="mdi mdi-account-plus"></i></a>
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
            <table class="table table-bordered table-striped" id="users">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Nick</th>
                    <th>Idiomas</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $u)
                    <tr>
                        <td>{{$u->name}}</td>
                        <td>{{$u->nick}}</td>
                        <td>@if($u->redactor){{ $u->redactor->idiomas }}@endif</td>
                        <td class="text-right">
                            @if($u->superadmin && !\Illuminate\Support\Facades\Auth::user()->superadmin)
                                <i class="mdi mdi-lock"></i>
                            @else
                                @can('edit', App\User::class)
                                    <a href="{{route('users-enable', $u->id)}}"><i class="mdi @if($u->habilitado) mdi-flag @else mdi-flag-outline @endif"></i></a>
                                    <a href="{{route('users-edit', $u->id)}}"><i class="mdi mdi-grease-pencil "></i></a>
                                @endcan
                                @can('permissions', App\User::class)
                                    <a href="{{route('users-permissions', $u->id)}}"><i class="mdi mdi-key"></i></a>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot></tfoot>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(function(){
        $('#users').dataTable({
            "language": {
                "lengthMenu": "Mostrando _MENU_ entradas por página",
                "zeroRecords": "No se han encontrado resultados",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay entradas",
                "infoFiltered": "(filtrado de _MAX_ total entradas)"
            }
        });
    });
</script>
@endsection