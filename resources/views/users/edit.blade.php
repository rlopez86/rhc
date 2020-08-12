@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h2 class="col-md-12">
                @if(isset($user->id))
                    Editando Usuario <span style="color: #a8a8a8">[{{$user->name}}]</span>
                @else
                    Nuevo Usuario
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
        <form method="post" action="{{route('users-save')}}">
            {{csrf_field()}}
            <input type="hidden" value="{{$user->id}}" name="id">
            <div class="form-row">
                <div class="form-group col-md-6 mb-0">
                    <label for="name" class="col-form-label">Nombre</label>
                    <input class="form-control @if($errors->first('name')) border-danger @endif" name="name" type="text" id="name" value="{{old('name', $user->name)}}" required>
                    @if($errors->first('name'))
                    <p class="text-danger mb-0">{{$errors->first('name')}}</p>
                    @endif
                </div>
                <div class="form-group col-md-6 mb-0">
                    <label for="nick" class="col-form-label">Nick</label>
                    <input class="form-control @if($errors->first('nick')) border-danger @endif" name="nick" type="text" id="nick" value="{{old('nick', $user->nick)}}" required>
                    @if($errors->first('nick'))
                        <p class="text-danger mb-0">{{$errors->first('nick')}}</p>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12 mb-0">
                    <label for="email" class="col-form-label">Correo</label>
                    <input class="form-control @if($errors->first('email')) border-danger @endif" name="email" type="email" id="email" value="{{old('email', $user->email)}}">
                    @if($errors->first('email'))
                        <p class="text-danger mb-0">{{$errors->first('email')}}</p>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6 mb-0">
                    <label for="password" class="col-form-label">Contraseña</label>
                    <input class="form-control @if($errors->first('r_password')) border-danger @endif" name="password" type="password" id="password" value="">
                    @if($errors->first('r_password'))
                        <p class="text-danger mb-0">{{$errors->first('r_password')}}</p>
                    @endif
                </div>
                <div class="form-group col-md-6 mb-0">
                    <label for="r_password" class="col-form-label">Repetir Contraseña</label>
                    <input class="form-control @if($errors->first('r_password')) border-danger @endif" name="r_password" type="password" id="r_password" value="">
                    @if($errors->first('r_password'))
                        <p class="text-danger mb-0">{{$errors->first('r_password')}}</p>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="languages" class="col-form-label">Idiomas</label>
                    <select class="form-control" name="languages[]" id="languages" multiple>
                        @foreach($languages as $lang)
                            <option value="{{$lang->abrev}}" @if($user->languages()->contains($lang->abrev)) selected @endif>{{$lang->idioma}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn-primary btn">Guardar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('styles')
    <link href="{{asset('vendor/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('scripts')
    <script src="{{asset('vendor/select2/js/select2.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $('#languages').select2();
    </script>
@endsection