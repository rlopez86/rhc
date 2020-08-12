var IMAGES_CONTROLS = (function(){
    this.trigger = $('#show_images_controls');
    this.main_container = $('#images_container');
    this.csrf_token = $('input#csrf').val();

    this.dropzone = $('#dropzone');
    this.dropzone_preview = $('#dropzone_preview');

    this.db_panel = $('#db_panel');
    this.db_results = db_panel.find('.results');
    this.db_input = $('#db_input');
    this.db_search_button = $('#db_search_button');
    this.loaded_images = 0;

    this.main_panel = $('#main_panel');
    this.expose_uri = $('#expose_uri');

    this.confirmModal = $('#confirmModal');
    this.cropperModal = $('#cropperModal');
    this.history_panel = $('#history');
    this.history_panel_original = history_panel.html();
    this.cropper = undefined;

    this.translate = {
        dictRemoveFile:'Quitar Imagen',
        dictCancelUpload:'Cancelar subida',
        dictCancelUploadConfirmation:'Seguro que desea cancelar?',
        dictResponseError:'Server Error',
        dictFileTooBig:'La imagen debe pesar solo 3MB como máximo',
        dictInvalidFileType:'La imagen no es válida',
        dictFallbackMessage:'Click Aqui para subir imágenes'
    };

    this.activate_dropzone = function(){

        dropzone.dropzone({
            autoDiscover : false,
            maxFilesize : 3,
            previewsContainer : dropzone_preview[0],
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
                _token: csrf_token
            },

            init: function(){
                this.on("processing", function(){
                    //nothing to do for now
                });

                this.on("success",function(file, response){
                    send_to_main($('<img class="img-thumbnail" src="/'+response.location+'" id="'+response.idimagenes+'">'), 'cloud');
                    $(file.previewElement).remove();
                });

                this.on("removedfile", function(file){
                    //nothing to do for now
                });

                this.on("error", function(file, response){
                    $(file.previewElement).find('.dz-error-message span').html(translate.dictResponseError)
                });

                this.on("complete", function(){
                    //nothing to do for now
                });
            }
        });

    };

    this.activate_search = function(){
        db_search_button.click(function(e){
            e.preventDefault();
            if(db_input.val()){
                loaded_images = 0;
                $.get(db_panel.data('action'), {'query':db_input.val()}, function(data){
                    db_results.empty();
                    $.each(data, function(index, value){
                        db_results.append('<span><img src="/articles/'+value.imagen+'" class="img-thumbnail" id="'+value.idimagenes+'"><i class="up mdi mdi-arrow-up-bold"></i></span>');
                    });
                    loaded_images += data.length;
                    db_results.append('<span><i class="more mdi mdi-plus"></i></span>');
                });
            }
        });
        db_results.on('click', 'span i.up', function () {
            var span = $(this).parents('span');
            var img = span.find('img');
            span.remove();
            send_to_main(img, 'archive');
        });

        db_results.on('click', 'span i.more', function (){
            var span = $(this).parents('span').detach();
            $.get(db_panel.data('action'), {'query':db_input.val(), 'offset':loaded_images}, function(data){
                $.each(data, function(index, value){
                    db_results.append('<span><img src="/articles/'+value.location+'" class="img-thumbnail" id="'+value.idimagenes+'"><i class="up mdi mdi-arrow-up-bold"></i></span>');
                });
                loaded_images += data.length;
                db_results.append(span);
            });
        })
    };

    this.send_to_main = function(img, origin){
        if(origin === 'archive'){
            signal = '<i class="mdi mdi-archive"></i>'
        }
        else{
            signal = '<i class="mdi mdi-amazon-clouddrive"></i>'
        }
        var elem = $('<div class="image">' +
            '<div class="controls up">' +
            '<span class="left">'+signal+'</span>' +
            '<span class="right"><i class="mdi mdi-content-cut action"></i><i class="mdi mdi-delete action"></i></span>' +
            '</div>' +
            '</div>');
        elem.append(img);
        elem.append('<div class="controls down">' +
            '<i class="action front">A PORTADA</i>' +
            '</div>');
        main_panel.append(elem);
    };

    this.highlight_image = function (img_id) {
        var img_elem = $('#'+img_id);
        main_panel.find('.img-thumbnail').removeClass('front');
        main_panel.find('div.controls.down i').text('A PORTADA');
        img_elem.addClass('front');
        img_elem.parents('div.image').find('div.controls.down i').text('NO PORTADA');
        img_elem.parents('div.image').find('#expose_uri').addClass('d-none').remove();
    };

    this.activate_controls = function(){
        main_panel.on('click', 'i.action.mdi-content-cut', function(e){
            var img_id = $(this).parents('div.image').find('img').attr('id');
            start_cropper(img_id);
        });
        main_panel.on('click', 'i.action.mdi-delete', function () {
            $(this).parents('div.image').remove();
        });
        main_panel.on('click', 'i.action.front', function(){
            var parent = $(this).parents('div.image');
            var img = parent.find('img');
            var img_id = img.attr('id');
            if(img.hasClass('front')){
                img.removeClass('front');
                parent.find('div.controls.down i').text('A PORTADA')
            }
            else{
                if(parent.hasClass('cropped')){
                    highlight_image(img_id)
                }
                else{
                    confirmModal.data('img', img_id);
                    confirmModal.modal();
                }
            }
        });
        confirmModal.find('button.action').click(function(e){
            e.preventDefault();
            var choice = $(e.target).data('choice');
            confirmModal.modal('hide');
            if(!choice){
                highlight_image(confirmModal.data('img'));
            }
            else{
                start_cropper(confirmModal.data('img'));
            }
        })
    };

    this.start_cropper = function(img_id){
        var img = $('#'+img_id).clone().removeClass('img-thumbnail');
        cropperModal.find('.modal-title').text('Recortando Imagen '+img_id);
        cropperModal.find('div.cropper div').html(img);
        cropper = new Cropper(img[0],{
            aspectRatio: 0.5,
            viewMode: 1
        });
        cropperModal.modal({
            backdrop:'static'
        });
        cropperModal.find('div#shapes li').click(function(e){
            var ratio = $(this).data('ratio');
            cropper.setAspectRatio(ratio);
            $(this).siblings('li').removeClass('selected');
            $(this).addClass('selected');
        });
        $.get(history_panel.data('url'), {id:img_id}, function (data) {
            history_panel.html('<ul class="croppeds d-flex justify-content-start list-unstyled"></ul>');
            $.each(data, function(i, v){
                history_panel.find('ul.croppeds').append('<li class="option"><img src="/'+v.location+'" id="'+v.idimagenes+'"></li>')
            });
        });
        history_panel.on('click', 'ul.croppeds li', function () {
            $(this).siblings('li').removeClass('selected');
            $(this).toggleClass('selected');
        })
    };

    cropperModal.on('hidden.bs.modal', function (e) {
        $(this).find('div#shapes li').off('click').removeClass('selected');
        $(this).find('div#shapes li:first').addClass('selected');
        history_panel.off('click').html(history_panel_original);
        cropper.destroy();
    });

    cropperModal.find('#save').click(function(){

        function accept(id, location){
            var oldid = $('div.cropper img.cropper-hidden').attr('id');
            var img = $('img#'+oldid);
            img.attr('src', location);
            img.attr('id', id);
            var parent = img.parents('div.image');
            parent.addClass('cropped');
            parent.find('i.action.mdi-content-cut').remove();
            highlight_image(id);
            cropperModal.modal('hide');
        }
        var history = $('ul.croppeds');
        if(history.find('li.selected').length === 1){
            var id = history.find('li.selected img').attr('id');
            var location = history.find('li.selected img').attr('src');
            accept(id, location);
            return;
        }
        id = $('div.cropper img.cropper-hidden').attr('id');
        $.post(
            $(this).data('url'),
            {_token:csrf_token, id:id, data:cropper.getCroppedCanvas().toDataURL()},
            function(data){
                accept(data.idimagenes, '/'+data.location)
            }
        );
    });

    this.trigger.click(function(e){
        e.preventDefault();
        main_container.toggleClass('d-none');
    });

    main_container.on('click', '.img-thumbnail', function(e){
        var parent = $(this).parents('div.image');
        if(parent.find('#expose_uri').length === 1){
            expose_uri.addClass('d-none').remove();
        }
        else{
            expose_uri.val($(this).attr('src'));
            $(this).parents('div.image').append(expose_uri);
            expose_uri.removeClass('d-none');
            expose_uri.focus();
            expose_uri.select()
        }
    });

    activate_dropzone();
    activate_search();
    activate_controls();
})();