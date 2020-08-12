@foreach($sections as $section)
    @php $children = key_exists('children', $section) && count($section['children']) > 0; @endphp
    @if(key_exists('data', $section))
        <li class="nav-item @if($children)dropdown @endif">
            @if($children)
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{trans('messages.'.$section['data']->label)}}<span class="sr-only">Toggle Dropdown</span>
                </a>
            @else
                <a class="nav-link" href="{{$section['data']->getUrl()}}">{{trans('messages.'.$section['data']->label)}}</a>
            @endif
            @if($children)
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @foreach($section['children'] as $child)
                        <div class="d-flex justify-content-between">
                            <a class="dropdown-item" href="{{$child['data']->getUrl()}}">{{trans('messages.'.$child['data']->label)}}</a>
                            @if(key_exists('children', $child) && count($child['children']) > 0)
                                <a class="plus" href="#">+</a>
                            @endif
                        </div>
                        @if(key_exists('children', $child) && count($child['children']) > 0)
                        <div class="subchild">
                            @foreach($child['children'] as $subchild)
                                <a class="dropdown-item" href="{{$subchild['data']->getUrl()}}">{{trans('messages.'.$subchild['data']->label)}}</a>
                            @endforeach
                            <div class="dropdown-divider"></div>
                        </div>
                        @endif
                    @endforeach
                    <a class="dropdown-item" href="{{$section['data']->getUrl()}}">{{trans('messages.all_f')}}</a>
                </div>
            @endif
        </li>
    @endif
@endforeach