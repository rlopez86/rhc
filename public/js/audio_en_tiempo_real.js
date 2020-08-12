$(window).load(function showdata() {
    var active_button =  document.getElementsByClassName("btn  btn-success  mx-2")[0];
    var day = active_button.id;
    $('tr').hide();
    $('tr.program-day-' + day).show();
    $('.header_table').show();
});

var botones = document.getElementsByClassName("btn");
for(var i =0; i< botones.length; i++)
    {
    botones[i].addEventListener("click", function changeDays(){
        //descactivar el boton
        var button_to_desactivate = document.getElementsByClassName("btn  btn-success  mx-2");
        button_to_desactivate[0].className='btn  btn-outline-success  mx-2';
        //ocultar los programas que no son de ese dia
        $('tr').hide();
        //activa el boton actual
        this.className= 'btn  btn-success  mx-2';
        //mostrar los programas de ese dia
        $('.program-day-'+ this.id).show();
        $('.header_table').show();
    


        });
    };
$(window).resize(function no_resize(){
    window.resizeTo (600, 520);
    
})

   