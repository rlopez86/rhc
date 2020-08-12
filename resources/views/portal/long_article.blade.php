<div class="col article-long"data-title="{{$art->nombre}}">
    <div class="art d-sm-flex @if(!$right) flex-sm-row @else flex-sm-row-reverse @endif">
        @if($picture)
            <div class="image flex-sm-shrink-0">
                <img src="{{$art->getPortadaLocation()}}" alt="{{ $art->imagenalt }}" title="{{ $art->imagenalt }}" onerror="this.src='{{asset('images/relleno_square.png')}}'">
            </div>
       
        @endif
        @if(($audio || $video) && $picture)
            <div class="controls d-flex">
            @if($audio)
                <div class="trigger-audio-in-sections mr-1" style=" @if($right) right: 0 @else left:0 @endif;">
                    <a href="#"><img src="{{asset('/images/icons/audio.png')}}" ></a>
                </div>
            @endif
            @if($video)
                <div class="trigger-video-in-sections mr-1" style=" @if($right) right: 0 @else left:0 @endif;">
                    <a data-location="{{$art->video}}"href="#" data-toggle="modal" data-target="#videoModal"><img src="{{asset('/images/icons/video.png')}}"></a>
                </div>
            @endif
        </div>
            
        @endif
        @if($audio && $picture)
            <div class="control audio sections d-none position-absolute image flex-sm-shrink-0">
                <div class="media-wrapper article-player player-audio">
                    <audio preload="none" controls style="max-width:100%;">
                        <source src="{{asset($audio)}}" type="audio/mp3">
                    </audio>
                </div>
            </div>
        @endif
            <div class="content px-sm-2">
                <div class="header">
                    <h1><a href="{{$art->getUrl($section)}}">{{$art->nombre}}</a></h1>
                    <label>{{\Carbon\Carbon::createFromFormat(\Carbon\Carbon::DEFAULT_TO_STRING_FORMAT, $art->fecha)->format(trans('messages.dateformat'))}}</label>
                </div>
                <div class="intro pb-2">
                    <p>{{$art->intro}}... <span class="readmore portal"><a href="{{$art->getUrl($section)}}">{{trans('messages.more')}}</a></span>
                        
                    </p>
                    
                </div>
                
            </div>
    </div>
</div>