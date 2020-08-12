@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-between pb-4">
            <h2 class="d-inline-flex px-1">
                Portada
            </h2>
        </div>
        <form class="border p-3 mb-5" method="post" action="{{route('articles-organize')}}">
            {{csrf_field()}}
            <h5>Filtros</h5>
            <hr>
            <div class="form-row">
                <div class="col-md-5">
                    <label for="section">Sección</label>
                    <select name="section" id="section" class="form-control">
                        @foreach($sections as $s)
                            @if($s->idseccion != 1)
                                <option value="{{$s->idseccion}}" @if($s->idseccion == $section)selected @endif>{{$s->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="language">Idioma</label>
                    <select name="language" id="language" class="form-control">
                        @foreach($languages as $lang)
                            <option value="{{$lang->abrev}}" @if($lang->abrev == $language)selected @endif>{{$lang->idioma}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button class="btn btn-success w-100">Buscar</button>
                </div>
            </div>
        </form>
        
        <div class="row">
            <div class="col">
                <table class="table" id="articles">
                    <thead>
                    <tr>
                        <th>Controles</th>
                        
                        <th>Orden</th>
                        <th>Portada</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Idioma</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($articles as $article)
                    <tr id="{{$article->idarticulos}}">
                        <td>
                            <a href="{{route('articles-up', $article->idarticulos)}}" class="up"><i class="mdi mdi-arrow-up"></i></a>
                            <a href="{{route('articles-down', $article->idarticulos)}}" class="down"><i class="mdi mdi-arrow-down"></i></a>
                            <a href="{{route('articles-publish', $article->idarticulos)}}" class="toggle"><i class="mdi @if($article->publicado) mdi-flag @else mdi-flag-outline @endif"></i></a>
                        </td>
                        
                        <td> 
                            @if($article->fecha > \Carbon\Carbon::now()->subDays(3))
                            <select title="orden" id="orden" data-uri="{{route('articles-orden', $article->idarticulos)}}">
                                
                                @for($i=1; $i <= $articulos_vigentes; $i++)<!--TODO Cambiar x asrticulos vigentes-->
                                    <option value={{$i}} @if($article->orden == $i) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                            @endif
                        </td>
                        <td>
                            <select title="portada" id="portada" data-uri="{{route('articles-front', $article->idarticulos)}}">
                                <option value="0" @if($article->portada == 0) selected @endif>No</option>
                                <option value="1" @if($article->portada == 1) selected @endif>1</option>
                                <option value="2" @if($article->portada == 2) selected @endif>2</option>
                                <option value="3" @if($article->portada == 3) selected @endif>3</option>
                                <option value="4" @if($article->portada == 4) selected @endif>4</option>
                            </select>
                        </td>
                        <td>{{$article->nombre}}</td>
                        <td>{{$article->fecha}}</td>
                        <td>{{$article->idioma}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(function () {
            var table = $('table#articles');
            table.find('a').click(function(e){
                e.preventDefault();
                var that = $(this);
                if(that.hasClass('toggle')){
                    ajaxify(that, function(data){
                        if(data === '1')
                            that.find('i').removeClass('mdi-flag-outline').removeClass('mdi-flag').addClass('mdi-flag');
                        else
                            that.find('i').removeClass('mdi-flag-outline').removeClass('mdi-flag').addClass('mdi-flag-outline');
                    });
                }
                if(that.hasClass('up') || that.hasClass('down')){
                    ajaxify(that, function(data){
                        var jdata = $.parseJSON(data);
                        if(jdata[0] == 'ERROR')
                        {
                            alert('Articulo no vigente');
                            return;
                        };
                        var near = $('#'+jdata[0].idarticulos);
                        
                        near.find('#orden').val(jdata[0].orden);
                        var art = $('#'+jdata[1].idarticulos);
                        art.find('#orden').val(jdata[1].orden);
                        if(near.index() < art.index()){
                            near.detach().insertAfter(art);
                        }
                        else{
                            art.detach().insertAfter(near)
                        }
                        
                    });
                }
            });
            table.find('select#portada').change(function(){
                var v = $(this).val();
                var uri = $(this).data('uri');
                $.get(uri, {'position': v}, function(data){
                    
                });
            });
            //Orden de las secciones
            table.find('select#orden').change(function(){
                var v = $(this).val();
                var uri = $(this).data('uri');
                $.get(uri, {'position': v, 'section':$('#section').val(), 'language':$('#language').val()}, function(data){
                    var jdata = $.parseJSON(data);
                    if(jdata[0] == 'ERROR')
                        {
                            var article_to_change = $('#'+jdata[2].idarticulos);
                            article_to_change.find('#orden').val(jdata[1]);
                            alert('Articulo no vigente');
                            return;
                        };
                    
                    var articles = jdata[0];
                    var oldpos = jdata[1];
                    var article_to_change = $('#'+jdata[2].idarticulos);
                    
                    for (let index = 0; index < articles.length -1; index++) {
                        var current = $('#'+articles[index].idarticulos);
                        var next = $('#'+articles[index + 1].idarticulos);
                        current.find('#orden').val(articles[index].orden);
                        next.find('#orden').val(articles[index +1].orden);
                        
                    }
                    article_prev = (v > oldpos) ? article_to_change.detach().insertAfter($('#'+articles[1].idarticulos)) : article_to_change.detach().insertBefore($('#'+articles[articles.length-2].idarticulos));
                   
                    
                   
                   
                });
            });
        });

        var ajaxify = function(link, callback){
            var url = link.attr('href');
            $.get(url, {'section':$('#section').val(), 'language':$('#language').val()}, function (data) {
                callback(data)
            }).fail(function(xhr){
                alert(xhr.statusText);
            });
        }
    </script>
@endsection