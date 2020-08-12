<div id="counter">
    <h1>{{trans('messages.visits')}}</h1>
    <div class="d-flex justify-content-between">
        <span>{{trans('messages.total')}}</span><span>{{$counters['total']}}</span>
    </div>
    <div class="d-flex justify-content-between">
        <span>{{trans('messages.maxima')}}</span><span>{{$counters['maxima']}}</span>
    </div>
    <div class="d-flex justify-content-between">
        <span>{{trans('messages.yesterday')}}</span><span>{{$counters['yesterday']}}</span>
    </div>
    <div class="d-flex justify-content-between">
        <span>{{trans('messages.today')}}</span><span>{{$counters['today']}}</span>
    </div>
    <div class="d-flex justify-content-between">
        <span>{{trans('messages.online')}}</span><span>{{$counters['online']}}</span>
    </div>
</div>