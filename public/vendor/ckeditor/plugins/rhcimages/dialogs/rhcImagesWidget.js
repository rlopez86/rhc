CKEDITOR.dialog.add('rhcImagesDialog', function(editor){
    //search Area----------------------------------------
    var offset = 0;
    var inQuery = false;
    var cropper;
    var query = function(e){
        offset = 0;
        search(e);
        inQuery = true;
    };
    var previous = function (e) {
        if(offset < 12)
            return;
        offset-=12;
        search(e);
    };
    var next = function (e) {
        if(inQuery)
            offset+=12;
        search(e)
    };
    var search = function(e) {
        var dialog = e.data.dialog;
        var browser = $('#'+dialog.getContentElement('tab-basic', 'imageBrowser').domId);
        var details = $('#'+dialog.getContentElement('tab-basic', 'imageDetails').domId);
        browser.html('<div class="loader" id="loader">\n' +
            '        <span class="square" role="status"></span>\n' +
            '    </div>');
        var name = dialog.getValueOf('tab-basic', 'name');
        var origin = dialog.getValueOf('tab-basic', 'origin');
        var date = dialog.getValueOf('tab-basic', 'date');
        $.get('/admin/images/search', {query: name, origin: origin, date: date, offset: offset}, function (data) {
            browser.off('click','li');
            browser.html('<ul class="list-unstyled d-flex flex-wrap"></ul>');
            var l = browser.find('ul');
            $.each(data, function (index, elem) {
                if(elem.location.indexOf('articles') === 0)
                    elem.location = '/'+elem.location;
                else
                    elem.location = '/articles/'+elem.location;
                var li = $('<li><img src="'+elem.location+'" class="admin-thumbnail"></li>');
                li.data('id', elem.idimagenes).data('description', elem.descripcion).data('name', elem.imagen)
                    .data('origin', elem.origen).data('date', elem.fecha).data('parent', elem.parent);

                l.append(li);
            });
            browser.on('click', 'li', function(e){
                $(this).addClass('selected').siblings('li').removeClass('selected');
                var template = '<h3>Detalles de la Imagen</h3><ul class="list-unstyled">' +
                    '<li><span>Identificador: </span>'+$(this).data('id')+'</li>' +
                    '<li><span>Nombre: </span><p>'+$(this).data('name')+'</p></li>' +
                    '<li><span>Descripción: </span><p>'+$(this).data('description')+'</p></li>' +
                    '<li><span>Origen: </span><p>'+$(this).data('origin')+'</p></li>' +
                    '<li><span>Fecha: </span><p>'+$(this).data('date')+'</p></li>' +
                    '<li><span>Es Recorte: </span><p>'+($(this).data('parent') ? 'SI' : 'NO')+'</p></li>' +
                    '</ul>';
                details.html(template);
            });

        }).fail(function(xhr){
            alert(xhr.statusText);
            browser.html('');
        });
    };
    //---------------------------------------------------
    //end search Area, Upload Area-----------------------
    //---------------------------------------------------
    var start = function () {
        var cropperContainer = $('#'+this.getContentElement('tab-adv', 'cropper').domId);
        var csrf_token = $('input[name="_token"]').val();
        $('#dropbox').dropzone({
            url:'/admin/images/upload',
            autoDiscover : false,
            maxFilesize : 3,
            acceptedFiles: 'image/*',
            addRemoveLinks:true,
            dictCancelUpload:'Cancelar Subida',
            dictCancelUploadConfirmation:'Esta seguro/a de cancelar la subida?',
            dictResponseError:'Ha ocurrido un error',
            dictFileTooBig:'La imagen es demasiado grande',
            dictInvalidFileType:'Fichero Invalido',
            dictFallbackMessage:'Click Para subir imagen',
            dictRemoveFile:'Quitar Imagen',
            params: {
                prev:localStorage.prevImageLocation || 'none',
                _token: csrf_token
            },
            init: function(){
                this.on("processing", function(){
                    //nothing to do for now
                });

                this.on("success",function(file, response){
                    var img = $('<img src="/'+response.location+'" id="'+response.idimagenes+'" style="max-height: 380px">');
                    cropperContainer.html(img);
                    localStorage.prevImageLocation = response.location;
                    this.options.params.prev = localStorage.prevImageLocation;
                    /* TODO find a better solution to the problem of ".cke_reset_all * " in the skin moono-lisa that
                    destroy the cropper by overwriting styles, current solution was modify editor.css on moono-lisa skin
                    and remove the selector */
                    cropper = new Cropper(img[0],{
                        aspectRatio: 1.75,
                        viewMode: 1
                    });
                    $(file.previewElement).remove();
                });

                this.on("removedfile", function(file){
                    //nothing to do for now
                });

                this.on("complete", function(){
                    //nothing to do for now
                });
            }
        });
        $('#ratios').on('click', 'span', function(e){
            if(!cropper)
                return;
            var ratio = $(this).data('ratio');
            cropper.setAspectRatio(ratio);
        });
    };


    return {
        title: 'Insertar Imagen en Artículo',
        minWidth: 1000,
        minHeight: 450,
        onLoad: start,

        contents:[
            {
                id: 'tab-basic',
                label: 'Banco de Imágenes',
                elements:[
                    {
                        type: 'hbox',
                        widths: ['', '', '', '70px'],
                        children: [
                            {
                                type: 'text',
                                id: 'name',
                                label: 'Nombre'
                            },
                            {
                                type: 'text',
                                id: 'origin',
                                label: 'Origen'
                            },
                            {
                                type: 'text',
                                id: 'date',
                                label: 'Fecha (d/m/yyyy ó m/yyyy ó yyyy)'
                            },
                            {
                                type: 'button',
                                label: 'Buscar',
                                id: 'search',
                                style:'margin-top:20px;',
                                onClick: query
                            }

                        ]
                    },
                    {
                        type: 'hbox',
                        widths: ['', '270px'],
                        children: [
                            {
                                type: 'html',
                                id: 'imageBrowser',
                                html: '<div class="images"></div>'
                            },
                            {
                                type: 'html',
                                id: 'imageDetails',
                                style:'border:1px solid grey;min-height:350px',
                                html: '<div class="image-details"><h3>Detalles de la Imagen</h3></div>'
                            }
                        ]
                    },
                    {
                        type: 'hbox',
                        widths: ['70px', '70px' ,'',''],
                        children: [
                            {
                                type: 'button',
                                label: 'Anterior',
                                id: 'previous',
                                onClick: previous
                            },
                            {
                                type: 'button',
                                label: 'Siguiente',
                                id: 'next',
                                onClick: next
                            },
                            {
                                type: 'checkbox',
                                id: 'front',
                                style: 'height:28px; width:40px;',
                                label: 'Usar como Portada'
                            },
                            {
                                type: 'html',
                                html: '<div></div>'
                            }
                        ]
                    }
                ]
            },
            {
                id: 'tab-adv',
                label: 'Subir Imagen',
                elements: [
                    {
                        type: 'hbox',
                        widths: ['200px', ''],
                        children: [
                            {
                                type: 'vbox',
                                heights: ['175px', ''],
                                children: [
                                    {
                                        type: 'html',
                                        id: 'dropbox',
                                        style: 'height:175px;background-color:#e9ecef;border:1px solid #139ff7;',
                                        html: '<div id="dropbox" class="dropzone"></div>'
                                    },
                                    {
                                        type: 'textarea',
                                        id:'imagen',
                                        rows:3,
                                        label: 'Nombre',
                                        
                                    },
                                    {
                                        type: 'textarea',
                                        id:'origin',
                                        rows:3,
                                        label: 'Origen'
                                    },
                                    {
                                        type: 'textarea',
                                        id:'description',
                                        label: 'Descripción'
                                    }
                                ]
                            },

                            {
                                type: 'vbox',
                                heights: ['380px', ''],
                                children: [
                                    {
                                        type: 'html',
                                        id: 'cropper',
                                        html: '<div></div>'
                                    },
                                    {
                                        type: 'hbox',
                                        widths: [''],
                                        children: [
                                            {
                                                type: 'html',
                                                id: 'ratios',
                                                html: '<div id="ratios">' +
                                                '<span id="horizontal" data-ratio="1.75"><img src="/images/shapes/h-long.jpg" height="50px"></span>' +
                                                '<span id="vertical" data-ratio="0.57"><img src="/images/shapes/v-long.jpg" height="50px"></span>' +
                                                '<span id="free" data-ratio="0"><img src="/images/shapes/square.jpg" height="50px"></span>' +
                                                '</div>'
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]


                    }
                ]
            }
        ],
        onOk: function(){
            var activeTab = this.definition.dialog._.currentTabId;
            if(activeTab === 'tab-basic'){
                var selected = $('.images').find('li.selected');
                if(selected.length === 0)
                    return;
                var imgId = selected.data('id');
                var front = $('#'+this.getContentElement('tab-basic', 'front').domId).find('input[type="checkbox"]').prop('checked');
                var location = selected.find('img').attr('src');
                var subtitle = selected.data('description');
                var clazz = ['innerImage','left'];
                if(front){
                    
                    //$('.innerImage').removeClass('front');
                    clazz.push('front');
                }

                editor.currWidget.setData('img', location);
                editor.currWidget.setData('subtitle', subtitle);
                editor.currWidget.setData('clazz', clazz);
            }
            if(activeTab === 'tab-adv'){
                localStorage.prevImageLocation = 'none';
                var token = $('input[name="_token"]').val();
                var id = $('.cropper-hidden').attr('id');
                var data = cropper.getCroppedCanvas().toDataURL();
                var origin = $('#'+this.getContentElement('tab-adv', 'origin').domId).find('textarea').val();
                var description = $('#'+this.getContentElement('tab-adv', 'description').domId).find('textarea').val();
                var name = $('#'+this.getContentElement('tab-adv', 'imagen').domId).find('textarea').val();
                var willUpload = true;
                if(!name || !description || !origin || origin.trim()=='' || name.trim()=='' || description.trim()==''){
                    alert("Debe rellenar los campos 'descripcion, origen y nombre'");
                    willUpload = false;
                }
                if(willUpload){
                    $.post(
                        '/admin/images/upload-cropped',
                        {
                            _token:token,
                            id:id,
                            data:data,
                            origin: origin,
                            description: description,
                            name: name
                        },
                        function(data){
                            cropper.destroy();
                            $('#'+data.parent).remove();
                            editor.currWidget.setData('img', '/'+data.location);
                            editor.currWidget.setData('subtitle', data.descripcion);
                        }
                    ).fail(function(xhr){
                        alert(xhr.statusText);
                    });
                }
                
            }
        }
    };
});