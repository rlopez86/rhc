@extends('layouts.app')
@section('styles')
    <link href="{{asset('vendor/dropzone/css/basic.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/lightbox/css/lightbox.css')}}" rel="stylesheet">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="col-md-12">
                @if(isset($gallery->idgalerias))
                    Editando Galería <span style="color: #a8a8a8">[{{$gallery->nombre}}]</span>
                @else
                    Nueva Galería
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
        <form method="post" action="{{route('galleries-save')}}" enctype="multipart/form-data">
            <div class="form-row">
                {{csrf_field()}}
                <input type="hidden" name="idgalerias" value="{{$gallery->idgalerias}}">
                <div class="form-group col-md-12">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre', $gallery->nombre)}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="descripcion">Descripción</label>
                    <textarea type="descripcion" name="descripcion" id="descripcion" class="form-control" rows="5">{{old('descripcion', $gallery->descripcion)}}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="tags">Tags</label>
                    <textarea type="tags" name="tags" id="tags" class="form-control" rows="3">{{old('tags', $gallery->tags)}}</textarea>
                </div>
            </div>
            @if($gallery->idgalerias)
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="imagenes">Imágenes</label>
                    <div id="dropzone" action="{{route('images-upload-gallery', $gallery->idgalerias)}}" class="p-5 border text-center">
                        <input type="hidden" id="csrf" value="{{csrf_token()}}">
                        <h6>Arrastre imágenes aquí o de click para subir al servidor</h6>
                        <div class="fallback">
                            <input name="file" type="file" multiple />
                        </div>
                    </div>
                    <div id="imagenes" class="border mt-3 dz">
                        <ul class="list-unstyled d-flex flex-wrap sortable">
                            @foreach($gallery->images->sortBy('posicion') as $image)
                                <li class="m-1 p-1 border position-relative image" id="{{$image->idimagenes}}" data-description="{{$image->descripcion}}">
                                    <ul class="list-unstyled d-flex position-absolute floating-menu">
                                        <li><a href="#" class="description"><i class="mdi mdi-tooltip-edit"></i></a></li>
                                        <li><a href="#" class="delete"><i class="mdi mdi-delete"></i></a></li>
                                    </ul>
                                    <a href="{{asset('galleries/'.$gallery->location.'/'.$image->location)}}" data-lightbox="cover" class="big-img"><img src="{{asset('galleries/'.$gallery->location.'/thumbnails/'.$image->location)}}" alt="{{$image->descripcion}}" ></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
            <div class="form-row pt-3">
                <div class="col text-center">
                    <button type="submit" class="btn-primary btn">@if($gallery->idgalerias)Guardar @else Guardar y volver para agregar imágenes @endif</button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="imageDescriptionModal" tabindex="-1" role="dialog" aria-labelledby="imageDescriptionModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Descripción</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" name="description" id="description" rows="6"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="save">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
    <li class="m-1 p-1 border position-relative image d-none" id="template-img" data-description="">
        <ul class="list-unstyled d-flex position-absolute floating-menu">
            <li><a href="#" class="description"><i class="mdi mdi-tooltip-edit"></i></a></li>
            <li><a href="#" class="delete"><i class="mdi mdi-delete"></i></a></li>
        </ul>
        <a href="" data-lightbox="cover" class="big-img"><img src="" alt="" ></a>
    </li>
@endsection

@section('scripts')
    @if($gallery->idgalerias)
    <script type="text/javascript" src="{{asset('vendor/jquery/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/dropzone/js/dropzone.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/lightbox/js/lightbox.min.js')}}"></script>
    <script>
        var imagenes_container = $('#imagenes');
        var descriptionModal = $('#imageDescriptionModal');
        var translate = {
            dictRemoveFile:'Quitar Imagen',
            dictCancelUpload:'Cancelar subida',
            dictCancelUploadConfirmation:'Seguro que desea cancelar?',
            dictResponseError:'Server Error',
            dictFileTooBig:'La imagen debe pesar solo 3MB como máximo',
            dictInvalidFileType:'La imagen no es válida',
            dictFallbackMessage:'Click Aqui para subir imágenes'
        };
        $('#dropzone').dropzone({
            autoDiscover : false,
            maxFilesize : 3,
            previewsContainer : imagenes_container[0],
            acceptedFiles: 'image/*',
            addRemoveLinks:true,
            dictRemoveFile:translate.dictRemoveFile,
            dictCancelUpload:translate.dictCancelUpload,
            dictCancelUploadConfirmation:translate.dictCancelUploadConfirmation,
            dictResponseError:translate.dictResponseError,
            dictFileTooBig:translate.dictFileTooBig,
            dictInvalidFileType:translate.dictInvalidFileType,
            dictFallbackMessage:translate.dictFallbackMessage,
            params: {
                _token: $('input#csrf').val()
            },

            init: function(){
                this.on("processing", function(){
                    //nothing to do for now
                });

                this.on("success",function(file, response){
                    var current = $('#template-img').clone();
                    current.attr('id', response.idimagenes);
                    current.data('description', response.descripcion);
                    current.data('order', response.posicion);
                    current.find('a.big-img').attr('href', "{{asset('galleries/'.$gallery->location)}}/"+response.location);
                    current.find('img').attr('src', "{{asset('galleries/'.$gallery->location.'/thumbnails')}}/"+response.location);
                    current.find('img').attr('alt', response.descripcion);
                    imagenes_container.find('ul.sortable').append(current.removeClass('d-none'));
                    $(file.previewElement).remove();
                });

                this.on("removedfile", function(file){
                    //nothing to do for now
                });

                this.on("error", function(file, response){
                    $(file.previewElement).find('.dz-error-message span').html(translate.dictResponseError)
                });

                this.on("complete", function(){

                });
            }
        });
        var sortable = $( ".sortable" ).sortable({
            update: function( event, ui ) {
                var current = $(ui['item']);
                var prev = current.prev();
                var next = current.next();
                $.post("{{route('images-reorder', $gallery->idgalerias)}}",
                    {_token:$('#csrf').val(), prev: prev.attr('id'), current:current.attr('id'),next:next.attr('id')}, function(data){
                    //todo toast
                    }
                ).fail(function(){
                    alert('Ha ocurrido un error');
                    sortable.sortable('cancel');
                });
            }
        });

        imagenes_container.on('click', '.floating-menu a', function(e){
            e.preventDefault();
            var image = $(this).parents('li.image');
            if($(this).hasClass('description')){
                descriptionModal.data('imageid', image.attr('id'));
                descriptionModal.find('#description').val(image.data('description'));
                descriptionModal.modal('show');
            }
            else{
                if(confirm('Esta seguro de eliminar esta imagen? Esta acción no es reversible')){
                    var id = image.attr('id');
                    $.post("{{route('images-delete')}}", {_token:$('#csrf').val(), id:id}, function(){
                        image.remove();
                    }).fail(function(xhr){
                        alert('Ha ocurrido un error');
                    });
                }
            }
        });
        descriptionModal.find('#save').click(function(e){
            var id = descriptionModal.data('imageid');
            var description = descriptionModal.find('#description').val();
            $.post("{{route('images-description')}}", {_token:$('#csrf').val(), id:id, description:description}, function(data){
                $('#'+id).data('description', data.descripcion);
            }).fail(function(xhr){
                alert('Ha ocurrido un error');
            });
            descriptionModal.modal('hide');
        });
    </script>
    @endif
@endsection
