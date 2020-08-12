<div class="row no-gutters my-3 multimedia-block">
    <div class="d-none loader" id="loader">
        <span class="square" role="status"></span>
    </div>
    <div class="col-12 col-md-5 media" id="screen">
        @if($multimedia->first() instanceof \App\Article)
            @include('portal.youtube', ['url'=>$multimedia->first()->video])
        @else
            @include('portal.gallery-preview', ['gallery'=>$multimedia->first()])
        @endif
    </div>
    <div class="col-12 col-md-7 row no-gutters mt-3">
        <div class="col-12">
            <ul class=" list-unstyled list-articles d-flex flex-wrap">
                @foreach($multimedia as $mul)
                    @if($loop->index < 6)
                    <li class="col-12 col-md-6 px-0 @if($loop->index == 0) selected @endif">
                        <div class="c" data-type="@if($mul instanceof \App\Article) video @else pictures @endif" data-id="{{$mul->id}}">
                            <div class="art px-3 py-2">
                                <a>{{$mul->nombre}}</a>
                                <div class="date">
                                    {{\Carbon\Carbon::createFromFormat(\Carbon\Carbon::DEFAULT_TO_STRING_FORMAT, $mul->fecha)->diffForHumans()}}
                                </div>
                            </div>
                            <div class="options d-inline-flex @if($mul instanceof \App\Gallery)galeria @endif">
                                @if($mul instanceof \App\Article)
                                    <a href="{{$mul->getFirstUrl()}}">{{trans('messages.ver-articulo')}}</a>
                                @else
                                    <a href="{{$mul->getFirstUrl()}}">{{trans('messages.ver-gallery')}}</a>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>