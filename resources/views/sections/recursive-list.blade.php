@foreach($sections as $section)
    <li style="padding-left: {{$padding+30}}px">
        @if(key_exists('data', $section))
            {{$section['data']->nombre}} / <a href="#" target="_blank">{{$section['data']->label}}</a> /
            <span class="languages">
                @php $current  = $section['data']->languages @endphp
                [@foreach($languages as $lang)
                    @php $disabled = true;
                    foreach ($current as $c){
                        if($c->ididioma == $lang->ididioma){
                            $disabled = false;
                        }
                    }
                    @endphp
                    <a href="{{route('sections-languages-toggle', ['id'=>$section['data']->idseccion, 'lang'=>$lang->ididioma])}}" @if($disabled) class="disable" @endif>{{$lang->abrev}}</a> -
                @endforeach]
            </span>
            <span class="controls">
                <a href="{{route('sections-toggle', ['id'=>$section['data']->idseccion])}}"><i class="mdi @if($section['data']->habilitado) mdi-flag @else mdi-flag-outline @endif"></i></a>
                <a href="{{route('sections-edit', ['id'=>$section['data']->idseccion])}}"><i class="mdi mdi-grease-pencil"></i></a>
                <a href="{{route('sections-delete', ['id'=>$section['data']->idseccion])}}" data-nombre="{{$section['data']->nombre}}" class="confirm-delete"><i class="mdi mdi-delete"></i></a>
                <a href="{{route('sections-freeze', ['id'=>$section['data']->idseccion])}}"><i class="mdi @if($section['data']->freeze) mdi-bell-off @else mdi-bell @endif"></i></a>
            </span>
        @endif
    </li>
        @if(key_exists('children', $section) && count($section['children']) > 0)
            @include('sections.recursive-list', ['sections'=>$section['children'], 'padding'=>$padding+30])
        @endif
@endforeach