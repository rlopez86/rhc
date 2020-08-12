@extends('layouts.master')
@section('side1')
    @include('portal.live')
@endsection
@section('side2')
    @include('portal.propaganda', ['propaganda'=>$propaganda])
@endsection
@section('side4')
    @include('portal.counter', ['counters'=>$counters])
@endsection
@if($main_section->banner)
@section('banner', 'background: linear-gradient(rgba(90, 112, 159, 0.5), rgba(6, 28, 58, 0.7)), url("../'.$main_section->banner.'") no-repeat center;background-size:cover;')
@endif
@section('section-title')
    <h1>{{trans('messages.'.$main_section->label)}}</h1>
@endsection
@section('metas')
    <meta name="keywords" content="{{trans('messages.correspondence-keywords')}}"/>
    <meta name="description" content="{{trans('messages.correspondence-description')}}"/>
    <meta property="og:image" content="{{asset('images/logo-color.png')}}"/>
    <meta property="og:description" content="{{trans('messages.correspondence-description')}}"/>
    <meta property="og:locale" content="{{app()->getLocale()}}">
    <meta property="og:title" content="{{trans('messages.correspondence')}}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{URL::current()}}">
@stop
@section('title', trans('messages.rhc'.' | '.trans('messages.correspondence')))
@section('content')
    <div class="row">
        <div class="col-12 corresp">
            <div class="d-flex justify-content-between">
                <img src="{{asset('images/icons/letter.jpg')}}" class="d-none d-md-block" alt="letter icon">
                <div class="text-md-right">
                    <p class="text-muted">Lo invitamos a enviar sus opiniones y comentarios. La admnistración de este sitio se reserva el derecho de eliminar aquellos mensajes que empleen palabras soeces, irrespetuosas o lleven implìcita alguna forma de discriminación e inciten al odio y la violencia.</p>
                    <a href="#" id="show" class="btn btn-success">Enviar mensaje</a>
                </div>
            </div>
            @if(session()->get('sended'))
                <div class="alert alert-success my-3" role="alert">
                    {{trans('messages.mail_success')}}
                </div>
            @else
            <form class="form mt-4 p-3 border" style="display: none" action="{{route('mail-send')}}" method="post">
                {{csrf_field()}}
                <div class="form-row">
                    <div class="col-md-8 form-group">
                        <label for="name">{{trans('messages.name')}}<sup>*</sup></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="country">{{trans('messages.country')}}<sup>*</sup></label>
                        <input type="text" class="form-control" id="country" name="country" value="{{old('country')}}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="email">{{trans('messages.e-mail')}}<sup>*</sup></label>
                        <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 form-group">
                        <label for="text">{{trans('messages.messaje')}}<sup>*</sup></label>
                        <textarea id="text" class="form-control" name="text" required>{{old('text')}}</textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 form-group">
                        <label for="captcha">{{trans('messages.captcha_label')}}<sup>*</sup></label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <img src="{{route('render-captcha')}}" alt="captcha challenge">
                                </div>
                            </div>
                            <input type="text" id="captcha" name="captcha" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6 form-group text-center text-md-right pt-md-5">
                        <button type="submit" class="btn btn-primary">{{trans('messages.sent_message')}}</button>
                    </div>
                </div>
            </form>
            @endif
            <h1 class="mt-4 py-2">{{trans('messages.messages')}}</h1>
            <div class="messages">
                @foreach($mails as $mail)
                    <div class="message my-3 pb-3">
                        <div class="d-flex justify-content-between">
                            <h5 class="text-primary">{{$mail->autor}}: {{$mail->pais}}</h5>
                            <p class="text-primary">{{\Carbon\Carbon::createFromFormat(\Carbon\Carbon::DEFAULT_TO_STRING_FORMAT, $mail->fecha)->format(trans('messages.dateformat'))}}</p>
                        </div>

                        <div class="text">
                            {!! $mail->texto !!}
                        </div>
                    </div>
                @endforeach
            </div>
            {{$mails->links()}}
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('vendor/ckeditor/ckeditor.js')}}"></script>
    <script>
        $(function(){
            if($('form.form').length === 1){
                CKEDITOR.replace('text', {
                    toolbar: [
                        [ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
                        [ 'FontSize', 'TextColor', 'BGColor' ]
                    ]
                });
                $('#show').click(function(e){
                    e.preventDefault();
                    $('.form').slideToggle();
                });
            }
            else
                $('#show').hide();
        });
    </script>
@endsection
