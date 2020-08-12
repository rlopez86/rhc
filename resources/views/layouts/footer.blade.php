<div id="up-page" class="text-center d-none">
    <a href="#"><img src="{{asset('images/icons/subir.png')}}" height="32" alt="up"></a>
</div>
<div id="footer">
    <div class="container">
        <div class="row pt-4">
            <div class="col-md-6 d-flex align-items-start">
                <img src="{{asset('images/icons/boletin.png')}}" width="48" class="mr-2 flex-shrink-0 flex-grow-0" alt="bulletin logo">
                <p class="mb-md-0 boletin">{{trans('messages.subscription_text')}}</p>
            </div>
            <div class="col-md-6">
                @if(session()->get('registered'))
                    <div class="alert alert-success my-3" role="alert">
                        {{trans('messages.subscription_no_text')}}
                    </div>
                @else
                <form method="post" action="{{route('bulletin-register')}}">
                    {{csrf_field()}}
                    <div class="input-group">
                        <input name="mail" type="text" class="form-control" title="bulletin-mail">
                        <div class="input-group-append">
                            <button class="input-group-sm btn">{{trans('messages.subscribirse')}}</button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>
        <div class="row subscription">
            <div class="col">
                <hr>
            </div>
        </div>
        <div class="row sections">
            @php
                    foreach ($sections as $sect){
                        if(key_exists('children', $sect)){
                            foreach ($sect['children'] as $c){
                                if($c['data']->label == 'noticias'){
                                    $news_children = $c['children'];
                                }
                                elseif($c['data']->label == 'de-interes'){
                                    $concerns_children = $c['children'];
                                }
                                elseif($c['data']->label == 'especiales'){
                                    $special_children = $c['children'];
                                }
                            }
                        }
                    }
            @endphp
            <div class="col-md-3">
                <h1>{{trans('messages.noticias')}}</h1>
                <ul class="list-unstyled">
                    @foreach($news_children as $child)
                        <li><a href="{{$child['data']->getUrl()}}">{{trans('messages.'.$child['data']->label)}}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-3">
                <h1>{{trans('messages.especiales')}}</h1>
                <ul class="list-unstyled">
                    @foreach($special_children as $child)
                        <li><a href="{{$child['data']->getUrl()}}">{{trans('messages.'.$child['data']->label)}}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-3">
                <h1>{{trans('messages.de-interes')}}</h1>
                <ul class="list-unstyled">
                    @foreach($concerns_children as $child)
                        <li><a href="{{$child['data']->getUrl()}}">{{trans('messages.'.$child['data']->label)}}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-3">
                <div class="social">
                    <ul class="list-unstyled d-flex justify-content-end">
                        <li class="mx-3">
                            <a target="_blank" href="{{trans('messages.facebook')}}"><img src="{{asset('images/icons/facebook.png')}}"></a>
                        </li>
                        <li class="mx-2">
                            <a target="_blank" href="{{trans('messages.twitter')}}"><img src="{{asset('images/icons/twitter.png')}}"></a>
                        </li>
                        <li class="ml-3">
                            <a target="_blank" href="{{trans('messages.instagram')}}"><img src="{{asset('images/icons/instagram.png')}}"></a>
                        </li>
                        <li class="ml-3">
                            <a target="_blank" href="{{trans('messages.youtube')}}"><img src="{{asset('images/icons/youtube.png')}}"></a>
                        </li>
                        <li class="ml-3" style="margin-top: -3px">
                            <a target="_blank" href="{{url('/feed')}}"><img src="{{asset('images/icons/rss.ico')}}" alt="rss" width="32"></a>
                        </li>
                    </ul>
                </div>
                <div class="authors">
                    <ul class="list-unstyled">
                        <li><span>{{trans('messages.director')}}:</span> Tania Hernández Castellanos</li>
                        <li><span>{{trans('messages.editor_jefe')}}:</span> Pedro Otero Cabañas</li>
                        <li><span>{{trans('messages.jefe_web')}}:</span> Maité González Martínez</li>
                        <li><span>{{trans('messages.webmaster')}}:</span> Reinier Clemente López, Adrian Valdés Serrano</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row copy">
            <div style="position:unset" class="col">
                <p class="mb-0 p-1"><span><img src="{{asset('/images/logo-color.png')}}" height="20" class="mr-2"></span>Radio Habana Cuba © 2019</p>
            </div>
        </div>
    </div>
</div>
