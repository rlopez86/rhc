@extends('layouts.big_master')
@section('columns')
    <div class="col-lg-9" id="news">
        @yield('content')
    </div>
    <div class="col-lg-3" id="side">
        @yield('side1')
        @yield('side2')
        @yield('side3')
        @yield('side4')
        @yield('side5')
    </div>
@endsection