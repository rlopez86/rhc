@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h2 class="col-md-12">
                Permisos para  <span style="color: #a8a8a8">[{{$user->name}}]</span>
            </h2>
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-striped" id="permissions">
                    <thead>
                    <tr>
                        <th>Identificador</th>
                        <th>Description</th>
                        <th>Activo</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $current_permissions = $user->permissions->pluck('permit') @endphp
                    @foreach($permissions as $perm)
                        <tr>
                            <td>{{$perm->permit}}</td>
                            <td>{{$perm->description}}</td>
                            <td>
                                <a href="{{route('users-change-permissions', ['id'=>$user->id, 'id_perm'=>$perm->id])}}">
                                    <i class="mdi @if($current_permissions->contains($perm->permit)) mdi-checkbox-marked-outline @else mdi-checkbox-blank-outline @endif"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection