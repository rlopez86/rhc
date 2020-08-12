
<div id="podcasts">
    <div id="live" >
        <a href="#" class="pop-up-audio-en-tiempo-real" >
            <img style= "border-radius: 15px"src="{{asset('images/live.gif')}}">
        </a>
        
    </div>
    <div>
        <a href="#" class="pop-up-audio-en-tiempo-real" >Ahora Reproduciendo</a>
        <div>
            <span class="current-play"><span>Ahora Reproduciendo</span></span>
        </div>
        
    </div>
    
    <div>
        <a href="#"class="pop-up-audio-en-tiempo-real" >A continuacion</a>
    <ul class="playlist"></ul>
    </div>
    <div id=''></div>
</div>
@section('scripts')
    @parent
<script type="text/javascript">

    function pad_name(str, size){
        if(str.length <= size)
            return str;
        else
            return str.substr(0, size) + '...';
    }

    function format_hour(hour_arr){
        if(hour_arr[1] == 0)
            hour_arr[1] = '12';
        if(parseInt(hour_arr[0]) < 12){
            return hour_arr[0]+':'+hour_arr[1]+'AM';
        }
        if(parseInt(hour_arr[0]) === 12){
            return hour_arr[0]+':'+hour_arr[1]+'M';
        }
        if(parseInt(hour_arr[0]) > 12){
            return (parseInt(hour_arr[0])-12) +':'+hour_arr[1]+'PM';
        }
    }

    function calculate_real_hour(program, scheduleoffset){
        var hour_arr = program.hour.split(':');
        return [parseInt(hour_arr[0])+ parseInt(scheduleoffset/60), parseInt(hour_arr[1]) + scheduleoffset%60];
    }

    $(function(){
        var schedule = $.parseJSON('@json($programacion)');
        var server_tz = 300;
        var date = new Date();
        var client_tz = date.getTimezoneOffset();
        var scheduleoffset = server_tz - client_tz;
        var current = false;
        var next = [];
        $.each(schedule.today, function (index, program) {
            var realHour = calculate_real_hour(program, scheduleoffset);
            if(realHour[1]>59){
                realHour[0]++;
                realHour[1] = realHour[1] - 60
            }
            if(date.getHours() < realHour[0] || (date.getHours() == realHour[0] && date.getMinutes() < realHour[1])){
                if(program !== current && next.length < 3){
                    $('ul.playlist').append('<li><span>'+pad_name(format_hour(realHour)+' '+program.name, 30)+'</span></li>');
                    next.push(program);
                }
            }
            else{
                //this should be the current program
                if(realHour[0] < date.getHours() || (date.getHours() == realHour[0] && realHour[1] < date.getMinutes())){
                    current = program;
                    $('.current-play').find('span').text(pad_name(format_hour(realHour)+' '+program.name, 30));
                }
            }
        });
        if(next.length < 3){
            $.each(schedule.tomorrow, function (index, program) {
                var realHour = calculate_real_hour(program, scheduleoffset);
                if(program !== current && next.length < 3){
                    $('ul.playlist').append('<li><span>'+pad_name(format_hour(realHour)+' '+program.name, 30)+'</span></li>');
                    next.push(program);
                }
            });
        }
    })
    $('.pop-up-audio-en-tiempo-real').click(function(){
        var audio_en_tiempo_real_popup = window.open('/audio_en_tiempo_real','Audio-en-tiempo-real','HEIGHT= 520, WIDTH=600, menubar=no, scrollbars=no, toolbar=no, resizable=no');
        

    })
        
    
</script>
@endsection
