$(function () {
    $('div.subchild').hide();
    $('a.plus').click(function(e){
        e.stopPropagation();
        e.preventDefault();
        $(this).parent('div').next('div.subchild').toggle();
        $(this).text() === '+' ? $(this).text('-') : $(this).text('+');
    });
    var header  = $('#header');
    var navbar = $('#alter');
    var body = $('.body');
    var up = $('#up-page');

    $( window ).scroll(function() {
        if(header.position().top > header.height()+140){
            $('nav#alter').show();
        }
        else{
            $('nav#alter').hide();
        }

        if(header.position().top > 500 && window.innerWidth > 1024){
            up.removeClass('d-none');
        }
        else
            up.addClass('d-none');
    });
});