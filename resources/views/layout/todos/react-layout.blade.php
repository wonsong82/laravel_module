@extends('backpack::layout')

@section('header')
    @yield('header')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div id="react-root"></div>

        </div>
    </div>
@endsection

@section('before_styles')
@endsection

@section('after_styles')
    @yield('css')
@endsection

@section('before_scripts')
@endsection

@section('after_scripts')
@endsection

@section('custom_scripts')
    @yield('js')
@endsection