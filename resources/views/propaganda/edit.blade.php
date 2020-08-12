@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="col-md-12">
                @if(isset($ad->idpropaganda))
                    Editando Anuncio <span style="color: #a8a8a8">[{{$ad->nombres}}]</span>
                @else
                    Nuevo Anuncio
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
        <form method="post" action="{{route('propaganda-save')}}" enctype="multipart/form-data">
            <div class="form-row">
                {{csrf_field()}}
                <input type="hidden" name="idpropaganda" value="{{$ad->idpropaganda}}">
                <div class="form-group col-md-3">
                    <label for="idioma">Idioma</label>
                    <select id="idioma" name="idioma" class="form-control">
                        @foreach($languages as $lang)
                        <option value="{{$lang->abrev}}" @if(old('idioma', $ad->idioma)== $lang->abrev) selected @endif>{{$lang->idioma}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-9">
                    <label for="nombres">Nombre</label>
                    <input type="text" name="nombres" id="nombres" class="form-control" value="{{old('name', $ad->nombres)}}" required="required">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label for="link">Enlace</label>
                    <input type="text" name="link" id="link" class="form-control" value="{{old('link', $ad->link)}}" required="required">
                </div>
            </div>
            <div class="form-row">
                <div class="col d-inline-flex">
                    <div class="form-group">
                        <img src="{{asset($ad->recurso)}}" id="preview">
                    </div>
                    <div class="form-group ml-3">
                        <label for="recurso">Cambiar Imagen</label>
                        <input type="file" class="form-control-file" id="recurso" name="recurso">
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
    <script type="text/javascript">
        $(function(){
            var original = $('#preview').attr('src');
            $('#recurso').change(function(){
                var fr = new FileReader();
                var that = $(this);
                fr.onload = function(e){
                    var str = e.target.result;
                    //if(get_width(str) === 300) //TODO Discuss with toki size of ads
                        $('#preview').attr('src', str);
                    //else{
                    //    that.val(null);
                    //    $('#preview').attr('src', original);
                    //    alert('Tamaño Incorrecto')
                        //todo use toast plugin to validation errors
                    //}
                };
                fr.readAsDataURL(this.files[0]);
            });
        });
        
        var get_width = function (str) {
            var img = document.createElement('img');
            img.style.display = 'none'; // If you don't want it showing
            img.src = str;
            document.body.appendChild(img);
            var width = img.width;
            img.parentNode.removeChild(img);
            img = undefined;
            return width;
        }
    </script>
@endsection