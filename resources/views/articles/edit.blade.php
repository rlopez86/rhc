@extends('layouts.app')
@section('styles')
    <link href="{{asset('vendor/dropzone5/basic.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('vendor/bootstrap-datepicker/bootstrap-datepicker.standalone.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('last_styles')
    <link href="{{asset('css/images.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="col-md-12">
                @if(isset($article->idarticulos))
                    Editando Artículo <span style="color: #a8a8a8">[{{$article->nombre}}]</span>
                @else
                    Nuevo Artículo
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
        <form method="post" action="{{route('articles-save')}}" id="article_form" novalidate>
            {{csrf_field()}}
            <input type="hidden" value="{{$article->idarticulos}}" name="idarticulos">
            <div class="row">
                <div class="col-6">
                    <label for="nombre" class="col-form-label">Nombre<sup class="required">*</sup></label>
                    <textarea class="form-control" name="nombre" id="nombre" required>{{old('nombre', $article->nombre)}}</textarea>
                    <div class="invalid-feedback">
                        El nombre es requerido.
                    </div>
                </div>
                <div class="col-3">
                    <label for="idioma" class="col-form-label">Idioma<sup class="required">*</sup></label>
                    @php $user_languages = \Illuminate\Support\Facades\Auth::user()->languages() @endphp
                    <select name="idioma" id="idioma" class="form-control" required>
                        @foreach($languages as $lang)
                            @if($user_languages->contains($lang->abrev))
                            <option value="{{$lang->abrev}}" @if(old('idioma') && old('idioma')==$lang->abrev) selected @else @if($article->idioma == $lang->abrev) selected @endif @endif>{{$lang->idioma}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <label for="seccion" class="col-form-label">Sección<sup class="required">*</sup></label>
                    <select name="seccion[]" id="seccion" class="form-control" multiple required>
                        @foreach($article->sections as $section)
                            <option value="{{$section->idseccion}}" id="s{{$section->idseccion}}" selected>{{$section->nombre}}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        Al menos una Sección es requerida.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="intro" class="col-form-label">Intro<sup class="required">*</sup></label>
                    <textarea id="intro" name="intro" class="form-control" required>{{old('intro', $article->intro)}}</textarea>
                    <div class="invalid-feedback">
                        Debe proveer una intro para el artículo.
                    </div>
                </div>
            </div>
            <div id="front">
                <div id="current-front" class="border p-2 mt-3">
                    <div class="row">
                        <div class="col text-center">
                            <h4>Portada Actual</h4>
                        </div>                    
                    </div>
                    <div id= "current-front-section">
                        <div class="row text-center my-3">
                            <div class="col-md-12">
                                <button id="eliminate-front"  class="btn-primary btn" style="margin-bottom:10px " > Eliminar</button>
                            </div>
                            <div id = "current-front-image" class="col-md-12 image">
                                <img src="" class="img-fluid" width="400">
                            </div>
                    </div>
                </div>
                    <div  id="no-front-set">
                        <p style="text-align: center">
                            Para insertar portada, utilice la herramienta rhcimages y al insertar una nueva imagen seleccionela como portada o 
                            click derecho encima de una imagen ya insertada y seleccione la casilla portada.
                        </p>
                    </div>
                </div>
            </div>
            
            
            <div class="row">
                <div class="col">
                    <label for="texto" class="col-form-label">Contenido<sup class="required">*</sup></label>
                    <textarea class="form-control" name="texto" id="texto" rows="50" required>{{old('texto', $article->texto)}}</textarea>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="tags" class="col-form-label">Palabras Clave<sup class="required">*</sup></label>
                    <textarea id="tags" name="tags" class="form-control" required>{{old('tags', $article->tags)}}</textarea>
                    <div class="invalid-feedback">
                        Las palabras claves son requeridas.
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="metadesc" class="col-form-label">Descripción Clave<sup class="required">*</sup></label>
                    <textarea id="metadesc" name="metadesc" class="form-control" required>{{old('metadesc', $article->metadesc)}}</textarea>
                    <div class="invalid-feedback">
                        La descripcion clave es requerida.
                    </div>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col text-center">
                    <button type="submit" class="btn-primary btn" id="save-article">Guardar</button>
                </div>
            </div>
        </form>
        <div class="modal" tabindex="-1" role="dialog" id="multmediaModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Se ha detectado un video en el artículo, desea configurarlo como portada?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="set-video">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('vendor/dropzone5/dropzone.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/cropperjs/cropper.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('vendor/select2/js/select2.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        var editor = CKEDITOR.replace("texto", {
            language:'es',
            toolbar: 'bold,italic,bulletedList,numberedList,blockQuote',
            extraPlugins: 'html5audio,html5video,youtube,rhcimages',
            toolbarGroups: [
                { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'others' },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                { name: 'styles' },
                { name: 'colors' },
                { name: 'about' }
            ], 
            youtube_width: '440',
            youtube_height : '280',
            youtube_responsive : true,
            youtube_related : true,
            youtube_older : false,
            youtube_privacy : false,
            youtube_autoplay : false,
            youtube_controls : true,

            // Remove some buttons provided by the standard plugins, which are
            // not needed in the Standard(s) toolbar.
            removeButtons : 'Underline,Subscript,Superscript',

            // Set the most common block elements.
            format_tags : 'p;h1;h2;h3;pre',

            // Simplify the dialog windows.
            removeDialogTabs : 'image:advanced;link:advanced',

            height : 400,

            contentsLanguage : 'es',

            format_p : { element: 'p' }

        });
        $('#eliminate-front').click(function(e){
            e.preventDefault();
            $(editor.document.$).find('div.innerImage').removeClass('front');
            show_front();

        });
        
        function show_front(){
            var data = '<div>'+editor.getData()+'</div>';
            content = $(data);
            var portada = content.find('div.innerImage.front');
            if(portada.length > 0)
            {
                var new_front = portada.find('img').attr('src');
                $('#current-front-image').find('img').attr('src',new_front);
                $('#no-front-set').hide();
                $('#current-front-section').show();


            }
            else{
                $('#no-front-set').show();
                $('#current-front-section').hide();
            }
            
        }
        show_front();
        editor.on('change',show_front)
        
        
        var idioma = $('#idioma');
        var sections = $('#seccion');
        idioma.change(function(){
            load_sections($(this).val());
        });
        var load_sections = function(lang){
            $.get('{{route('languages-sections')}}', {'lang':lang}, function(data){
                sections.find('option').not('option:checked').remove();
                $.each(data, function(index, value){
                    if(value.freeze)
                        return;
                    if(sections.find('#s'+value.idseccion).length === 0){
                        var o = $('<option></option>');
                        o.val(value.idseccion);
                        o.append(value.nombre);
                        sections.append(o);
                    }
                });
            })
        };
        load_sections(idioma.val());
        $('select').select2();

        var form = $('#article_form');
        var modal = $('#multmediaModal');
        var content;

        form.on('submit', function(event) {
            if (form[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.addClass('was-validated');
        });

        $('#save-article').click(function(e){
            e.preventDefault();

            var data = '<div>'+editor.getData()+'</div>';
            content = $(data);
            content.find('.ckeditor-html5-audio').addClass('d-flex');

            var portada = content.find('div.innerImage.front');
            if(portada.length > 0){
                form.append('<input type=hidden value="'+portada.find('img').attr('src')+'" name="imagen" id="image_front">');
                form.append('<input type=hidden value="'+portada.find('img').attr('alt')+'" name="imagenalt" id="image_front_alt">');
            }

            if(content.find('video').length > 0){
                //modal.data('video', content.find('video').attr('src')).modal();
                form.append('<input type=hidden value="'+content.find('video')[0].src+'" name="video" id="video">');
            }
            else if(content.find('.youtube-embed-wrapper>iframe').length > 0){
                 form.append('<input type=hidden value="'+content.find('.youtube-embed-wrapper>iframe')[0].src+'" name="video" id="video">');
            }
            if(content.find('audio').length > 0){
                form.append('<input type=hidden value="'+content.find('audio')[0].src+'" name="audios" id="audio">');
                //form.submit();
            }
            form.append('<textarea name="processed-text" class="d-none">'+content.html()+'</textarea>');
            // else{
            //     form.submit();
            // }
            form.submit();
        });

        modal.on('hidden.bs.modal', function () {
            form.append('<textarea name="processed-text" class="d-none">'+content.html()+'</textarea>');
            form.submit();
        });

    </script>
@endsection
