<div id="index" class="index d-flex row">
    <div class="col-12 col-md-4">
        <ul class="latest list-unstyled">
            @for($i = 0; $i<5; $i++)
                @php $art = $latest[$i] @endphp
                <li>
                    <h6><a href="{{$art->sections[0]->getUrl()}}">{{$art->sections[0]->nombre}}</a></h6>
                    <a href="{{$art->getFirstUrl()}}">{{$art->nombre}}</a>
                </li>
            @endfor
        </ul>
    </div>
    <div class="col-12 col-md-4">
        <ul class="latest list-unstyled">
            @for($i = 5; $i<10; $i++)
                @php $art = $latest[$i] @endphp
                <li>
                    <h6><a href="{{$art->sections[0]->getUrl()}}">{{$art->sections[0]->nombre}}</a></h6>
                    <a href="{{$art->getFirstUrl()}}">{{$art->nombre}}</a>
                </li>
            @endfor
        </ul>
    </div>
    <div class="col-12 col-md-4">
        <ul class="latest list-unstyled">
            @for($i = 10; $i<15; $i++)
                @php $art = $latest[$i] @endphp
                <li>
                    <h6><a href="{{$art->sections[0]->getUrl()}}">{{$art->sections[0]->nombre}}</a></h6>
                    <a href="{{$art->getFirstUrl()}}">{{$art->nombre}}</a>
                </li>
            @endfor
        </ul>
    </div>
</div>