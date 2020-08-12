<!doctype html>
@php $locale = app()->getLocale() @endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    @yield('metas')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='shortcut icon' type='image/x-icon' href="{{asset('images/logo-color.png')}}" />
    @if(app()->getLocale() == 'ar')
        <link href="{{asset('vendor/bootstrap-rtl/bootstrap-rtl.css')}}" rel="stylesheet">
    @else
        <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    @endif
    <link href="{{asset('vendor/mediaelement/mediaelementplayer.css')}}" rel="stylesheet">
    <link href="{{asset('css/portal.css')}}" rel="stylesheet">
    <link href="{{asset('css/medias.css')}}" rel="stylesheet">
    <link href="{{asset('css/audio.css')}}" rel="stylesheet">

    @yield('styles')
    <title>@yield('title', trans('messages.rhc'))</title>
    <style>
        .body{
            background-image: 
        }
        .btn{
            color: white;
            background-color: #A12634;
            border-color:#A12634;
            margin-right: 5px;
            margin-top:5px; 
            width: 50px;
            font-size: 14px;
            text-align: center;
        }
        
        #table-audio{
            overflow: scroll;
            height: 250px;
            width: 95%;
        }
        #table-audio table{
           width: 95%;
           
        }
        .btn:hover{
            background-color: #2578B1;
            border-color: #2578B1;
            color: white;
        }
        .btn-success{
            background-color: #2578B1;
            border-color: #2578B1;
            color: white;   
        }
    </style>

</head>
<body>
    <!--Carousel-->
    
    <!--Repdroductor de audio-->
    <div class="container" style = 'background-image: linear-gradient(rgba(99, 100, 104, 0.5), rgba(97, 99, 102, 0.7)); margin-bottom:5px'>
        <div class="row-pb-12">
            <div id="podcasts">
            @if($current)
            <li class="current-play">
                <a>Ahora Reproduciendo : {{$current->name}} </a>
            </li>
            
            <li>
                <a>Inicio: {{\Carbon\Carbon::createFromFormat('H:i:s',$current->hour)->format('h:i A')}} </a>
            </li>
            
            <li >
                <a>Descripcion: {{$current->description}}</a>
            </li>
            @endif
        </div>
    </div>
</div>
                
            <div class="media-wrapper player-audio" id="player2">
                <audio class = "mejs__container" preload="true" controls style="max-width:100%;">
                    <source src="{{asset('audios/test.mp3')}}" type="audio/mp3">
                </audio>
            </div>
           

    <!--Controlador de la tabla-->
    
    <div class="container" >
        <div style="text-align : center ">
           
            <button id = '0' class="btn @if($day == 0) btn-success @else btn-outline-success @endif mx-2">Dom</nutton>
            <button id = '1' class="btn @if($day == 1) btn-success @else btn-outline-success @endif mx-2">Lun</button>
            <button id = '2' class="btn @if($day == 2) btn-success @else btn-outline-success @endif mx-2">Mar</button>
            <button id = '3' class="btn @if($day == 3) btn-success @else btn-outline-success @endif mx-2">Mier</button>
            <button id = '4' class="btn @if($day == 4) btn-success @else btn-outline-success @endif mx-2">Jue</button>
            <button id = '5' class="btn @if($day == 5) btn-success @else btn-outline-success @endif mx-2">Vie</button>
            <button id = '6' class="btn @if($day == 6) btn-success @else btn-outline-success @endif mx-2">Sáb</button>
        </div>
    </div>
    <!--Schedule en tabla-->
    <div class="col-sm-8 col-md-7 text-right podcasts-list">
        <div class="podcasts" style="clear: both">
            
                <div class="audios" style="display: none">
                    <ul class="list-unstyled">
                        
                    </ul>
                </div>
            
        </div>
    </div>
    <div class="row pb-4">
        <div id='table-audio' class="col">
            <table border="1" class="table" id="schedule" style="margin : 1rem ; padding : 1rem ; text-align : left">
                <h5 style ="color: #A12634;margin : 5px ; padding : 5px ; text-align : center ">Programacion</h5>
                <thead>
                <tr class="header_table" style ="color: #2578B1">
                    <th style="width: 25%">Hora</th>
                    <th style="width: 35%">Nombre</th>
                    <th style="width: 40%">Descripcion</th>
                   
                    
                </tr>
                </thead>
                <tbody>
                @foreach($programs as $p)
                
                    <tr class="program-day-{{$p->day}}" style="color :@if($p->day != $day) #A12634
                     @else
                        @if($p->hour > $now)
                            #2578B1
                        @else
                           
                            @if($p == $current)
                                green
                            @else
                                #A12634
                            @endif
                                                 
                        @endif
                     @endif">
                        <td class="date">{{\Carbon\Carbon::createFromFormat('H:i:s',$p->hour)->format('h:i A')}}</td>
                        <td class="name">{{$p->name}}</td>
                        <td class="description">{{$p->description}}</td>
                        
                        
                        
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

<script src="{{asset('vendor/jquery/jquery.js')}}" type="text/javascript"></script>
<script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendor/mediaelement/mediaelement-and-player.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/multimedia.js')}}"></script>
<script src="{{asset('js/audio_en_tiempo_real.js')}}" type="text/javascript"></script>
</html>