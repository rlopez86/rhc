$(window).load(function micro() {
    if(navigator.userAgent.match(/Android/i)|| navigator.userAgent.match(/webOS/i)||navigator.userAgent.match(/Iphone/i)||
    navigator.userAgent.match(/Ipad/i)||navigator.userAgent.match(/Ipod/i)||navigator.userAgent.match(/BlackBerry/i)||
    navigator.userAgent.match(/Windows Phone/i))
        return;
    var language = ($('html').attr('lang') == 'es')? '' :$('html').attr('lang');
    if(window.location.pathname == '/'+language) return;
    if(window.location.pathname.includes('/galerias/')) return;
    //If we reach this state, we are going to display micro images as background
    var container = $('#contenido_pagina')[0];
    var altura_container = $('#contenido_pagina').height();
    if(screen.height < 576)
        {
            var image = `background:none;`
            container.style.cssText = image;
            return;   

        } 
    var image = `background: url("../../images/mic-thin_01.png") no-repeat 81.5% 0, `;
    var altura_actual = 525
    for(altura_actual ; altura_actual + 8 + 81.5 + 25 <= altura_container ; altura_actual+=8){
        image+=`url("../../images/mic-thin_02.png") no-repeat 81.5% ${altura_actual}px,`;
    }
  
    image+=`url("../../images/mic-thin_03.png") no-repeat 81.5% ${altura_actual}px,`;
    altura_actual +=81.5
    image+=`url("../../images/mic-thin_04.png") no-repeat 81.5% ${altura_actual}px,`;
    image+=`url("../../images/mic-thin_05.png") no-repeat 76% ${altura_actual}px,`;
    image+=`url("../images/mic-thin_05.png") no-repeat 73% ${altura_actual}px,`;
    var current_width = 75;
    for (current_width ; current_width  > 25; current_width -=2) {
        image+=`url("../images/mic-thin_05.png") no-repeat ${current_width}% ${altura_actual}px,`;
        
    }
    
    
    image+=`url("../images/mic-thin_05.png") no-repeat ${current_width}% ${altura_actual}px;`;
    container.style.cssText = image;
    
    
  });
  $(window).resize(function micro() 
       {
       
        if(navigator.userAgent.match(/Android/i)|| navigator.userAgent.match(/webOS/i)||navigator.userAgent.match(/Iphone/i)||
        navigator.userAgent.match(/Ipad/i)||navigator.userAgent.match(/Ipod/i)||navigator.userAgent.match(/BlackBerry/i)||
        navigator.userAgent.match(/Windows Phone/i))
            return;
        var language = ($('html').attr('lang') == 'es')? '' :$('html').attr('lang');
        if(window.location.pathname == '/'+language) return;
        if(window.location.pathname.includes('/galerias/')) return;
        //If we reach this state, we are going to display micro images as background
        var container = $('#contenido_pagina')[0];
        var altura_container = $('#contenido_pagina').height();
        if(screen.height < 576)
        {
            var image = `background:none;`
            container.style.cssText = image;
            return;   

        } 
        var image = `background: url("../../images/mic-thin_01.png") no-repeat 81.5% 0, `;
        var altura_actual = 525
        for(altura_actual ; altura_actual + 8 + 81.5 + 25 <= altura_container ; altura_actual+=8){
            image+=`url("../../images/mic-thin_02.png") no-repeat 81.5% ${altura_actual}px,`;
        }
      
        image+=`url("../../images/mic-thin_03.png") no-repeat 81.5% ${altura_actual}px,`;
        altura_actual +=81.5
        image+=`url("../../images/mic-thin_04.png") no-repeat 81.5% ${altura_actual}px,`;
        image+=`url("../../images/mic-thin_05.png") no-repeat 76% ${altura_actual}px,`;
        image+=`url("../images/mic-thin_05.png") no-repeat 73% ${altura_actual}px,`;
        var current_width = 75;
        for (current_width ; current_width  > 25; current_width -=2) {
            image+=`url("../images/mic-thin_05.png") no-repeat ${current_width}% ${altura_actual}px,`;
            
        }
        
        
        image+=`url("../images/mic-thin_05.png") no-repeat ${current_width}% ${altura_actual}px;`;
        container.style.cssText = image;
        
        
       });