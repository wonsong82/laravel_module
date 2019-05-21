<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ isset($title)? $title : '' }}</title>
    <script>window.url=function(a){if(a==='socket'){var c={{ env('BROADCAST', false)?'true':'false' }};return c?'{{ env('BROADCAST_ADDRESS') }}':false;}else{var b='{{ rtrim(url('/'), '/') }}';return b+'/'+a.replace(/^\/+/,'');}}</script>

    {!! dist($css) !!}
    @yield('after_styles')
</head>
<body>


<div id="react-root"></div>


{!! dist($js) !!}
@yield('after_scripts')
</body>
</html>