
var current_index = 0;
var random = false;
var interval = 10000;
var urls = ['/images/1.jpg','/images/2.jpg','/images/3.jpg'];
var gradient = 'linear-gradient(rgba(90, 112, 159, 0.5), rgba(6, 28, 58, 0.7))'
//TODO usar rutas absolutas en vez de rutas relativas
var length = urls.length ;
function slide(){
    $('#header').css('background', gradient + ', url(' + urls[current_index] + ')' + 'no-repeat center');
    $('#header').css('background-size','cover')
    current_index = (current_index < length-1) ? current_index +1 : 0;
}

function banner_random(){
    var numero = Math.random()*length;
    var portada = Math.floor(numero)
    $('#header').css('background', gradient + ', url(' + urls[portada] + ')' + 'no-repeat center');
    $('#header').css('background-size','cover')
    
}
var language = ($('html').attr('lang') == 'es')? '' :$('html').attr('lang');
if(window.location.pathname == '/'+language)
    {if(!random)
        {
            slide();
            window.setInterval(slide,interval);
        }
        else{
            banner_random();
        }
    }

