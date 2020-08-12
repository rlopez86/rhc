CKEDITOR.dialog.add('rhcImagesAdjustDialog', function(editor){
    const val = new Map();
    return {
        title: 'Propiedades de Imagen RHC',
        minWidth: 250,
        minHeight: 100,
        onShow: function(){
            var selection = editor.getSelection();
            var element = selection.getStartElement();
            this.element = element.getAscendant('div', true);
            this.setupContent(this.element);

            let {naturalWidth, naturalHeight} = $(element.$).find('img')[0];
            let auto = $('#'+val.auto).find('input');
            let w = $('#'+val.w).find('input');
            let h = $('#'+val.h).find('input');
            let restart = $('#'+val.restart);

            val.wvalue = w.val();
            val.hvalue = h.val();

            restart.click(function(){
                h.val(naturalHeight);
                w.val(naturalWidth);
            });
            w.keyup(function(){
                if(auto.prop('checked'))
                    h.val(w.val()*val.hvalue/val.wvalue);
            });
            h.keyup(function(){
                if(auto.prop('checked'))
                    w.val(h.val()*val.wvalue/val.hvalue);
            });
        },
        onOk: function () {
            this.commitContent(this.element);
        },
        contents:[
            {
                id: 'adjust',
                label: 'Propiedades de Imagen RHC',
                elements:[
                    {
                        type:'checkbox',
                        id:'front',
                        label:'Portada',
                        setup: function (element) {
                            if($(element.$).find('div.innerImage').hasClass('front')){
                                $('#'+this.domId).find('input').prop('checked', true);
                            }
                        },
                        commit: function (element) {
                            if($('#'+this.domId).find('input').prop('checked')){
                                $(editor.document.$).find('div.innerImage').removeClass('front');
                                $(element.$).find('div.innerImage').addClass('front');
                            }
                            else{
                                $(element.$).find('div.innerImage').removeClass('front');
                            }
                        }
                    },
                    {
                        type: 'textarea',
                        id: 'alt',
                        label: 'Texto Alternativo',
                        setup: function (element) {
                            this.setValue($(element.$).find('img').attr('alt'));
                        },
                        commit: function (element) {
                            $(element.$).find('img').attr('alt', this.getValue());
                        }
                    },
                    {
                        type:'checkbox',
                        id:'auto',
                        label:'Automatico',
                        setup: function (element) {
                            val.auto = this.domId;
                            $('#'+this.domId).find('input').prop('checked', true);
                        },
                        commit: function (element) {}
                    },
                    {
                        type: 'hbox',
                        id: 'sizes',
                        children:[
                            {
                                type: 'text',
                                id: 'width',
                                label: 'Anchura',
                                setup: function (element) {
                                    var w = $(element.$).find('img').width();
                                    val.w = this.domId;
                                    this.setValue(w)
                                },
                                commit: function(element){
                                    if(this.getValue() !== $(element.$).find('img').width()){
                                        $(element.$).find('img').attr('width', parseInt(this.getValue()));
                                    }
                                }
                            },
                            {
                                type: 'text',
                                id: 'height',
                                label: 'Altura',
                                setup: function (element) {
                                    var w = $(element.$).find('img').height();
                                    val.h = this.domId;
                                    this.setValue(w)
                                },
                                commit: function(element){
                                    if(this.getValue() !== $(element.$).find('img').height()){
                                        $(element.$).find('img').attr('height', parseInt(this.getValue()));
                                    }
                                }
                            }
                        ]
                    },
                    {
                        type:'button',
                        id:'restart',
                        label:'Reestablecer',
                        setup: function(element){
                            $('#'+this.domId).css('position','relative');
                            val.restart = this.domId;
                        },
                        commit: function(element){}
                    },
                    {
                        type: 'hbox',
                        id: 'align',
                        children:[
                            {
                                type: 'radio',
                                id: 'aligns',
                                label: 'Alineación',
                                items: [
                                    [ 'Izquierda', 'left' ],
                                    [ 'Centrado', 'center' ],
                                    [ 'Derecha', 'right' ]
                                ],
                                setup: function (element) {
                                    if($(element.$).find('div.innerImage').hasClass('left')){
                                        this.setValue('left');
                                    }
                                    if($(element.$).find('div.innerImage').hasClass('right')){
                                        this.setValue('right');
                                    }
                                    if($(element.$).find('div.innerImage').hasClass('center')){
                                        this.setValue('center');
                                    }
                                },
                                commit: function (element) {
                                    $(element.$).find('div.innerImage').removeClass('left').removeClass('center')
                                        .removeClass('right').addClass(this.getValue());

                                }
                            }
                        ]
                    }
                ]
            }
        ]
    }
});