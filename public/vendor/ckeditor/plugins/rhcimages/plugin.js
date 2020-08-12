CKEDITOR.plugins.add( 'rhcimages', {
    requires: 'widget',
    icons: 'rhcimages',
    init: function( editor ) {

        editor.addContentsCss('.innerImage{\n' +
            '    background-color: white;\n' +
            '    padding: 5px;\n' +
            '    margin: 5px;\n' +
            '    border: 1px solid #cacaca;\n' +
            '    text-align:center;\n' +
            '}\n' +
            '\n' +
            '.innerImage.center{\n' +
            '    width: 100%;\n' +
            '    text-align: center;\n' +
            '    border: none;\n' +
            '}\n' +
            '\n' +
            '.innerImage.right{\n' +
            '    display: inline;\n' +
            '    float: right;\n' +
            '}\n' +
            '\n' +
            '.innerImage.left{\n' +
            '    display: inline;\n' +
            '    float: left;\n' +
            '}\n' +
            '.innerImage.front{\n' +
            '    background-color: #007bff30;\n' +
            '}\n' +
            '\n' +
            '.innerImage .subtitle{\n' +
            '    text-align: center;\n' +
            '    color: #495057;\n' +
            '    font-style: italic;\n' +
            '    font-weight: 600;\n' +
            '    min-height: 16px;\n' +
            '    margin: 0;\n' +
            '}\n' +
            '\n' +
            '.innerImage img{\n' +
            '    max-width: 100%!important;\n' +
            '}');

        CKEDITOR.dialog.add('rhcImagesDialog', this.path + 'dialogs/rhcImagesWidget.js');
        editor.widgets.add('rhcimages',{
            button: 'Insertar Imagen RHC',
            template:
            '<div class="innerImage" id="">' +
            '<img src="">' +
            '<p class="subtitle"></p>' +
            '</div>',
            editables:{
                subtitle:{
                    selector: '.subtitle'
                }
            },
            dialog: 'rhcImagesDialog',
            upcast: function (element) {
                //alert('hello world')
                return element.name === 'div' && element.hasClass('innerImage');
            },
            init: function () {
                var img = this.element.find('img').src;
                var subtitle = this.element.find('.subtitle').innerText;
                this.setData('img', img);
                this.setData('subtitle', subtitle);
                editor.currWidget = this;
            },
            
            data: function () {
                if(this.data.img){
                    this.element.find('img').$[0].src = this.data.img;
                    this.element.find('img').$[0].alt = '';
                }
                if(this.data.subtitle !== undefined){
                    this.element.find('.subtitle').$[0].innerText = this.data.subtitle;
                }
                if(this.data.clazz){
                    
                    $(editor.document.$).find('div.innerImage').removeClass('front');
                    this.element.removeClass('innerImage');
                    for (let i = 0; i < this.data.clazz.length; i++) {
                        this.element.addClass(this.data.clazz[i]);
                        
                    };
                    
                }
            }
        });
        editor.addCommand('adjustRHCImage', new CKEDITOR.dialogCommand('rhcImagesAdjustDialog'));
        CKEDITOR.dialog.add('rhcImagesAdjustDialog', this.path + 'dialogs/adjustrhcimages.js');
        if(editor.contextMenu){
            editor.addMenuGroup('rhcimagesGroup');
            editor.addMenuItem('rhcimagesItem', {
                label: 'Propiedades de Imagen RHC',
                icon: this.path + 'icons/rhcimages.png',
                command: 'adjustRHCImage',
                group: 'rhcimagesGroup'
            });

            editor.contextMenu.addListener(function (element) {
                var asc = element.getAscendant('div', true);
                if(asc && asc.$.className.indexOf('innerImage')!== -1){
                    return {rhcimagesItem:CKEDITOR.TRISTATE_OFF}
                }
            });
        }
    }
});

