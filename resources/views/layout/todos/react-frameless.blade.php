<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ isset($title) ? $title.' :: '.config('backpack.base.project_name').' Admin' : config('backpack.base.project_name').' Admin' }}</title>

    <script>
        window.url=function(uri){var root='{{ rtrim(url('/'), '/') }}';return root+'/'+uri.replace(/^\/+/,'')};
    </script>

    {!! dist('vendor.css') !!}
    {!! dist($css) !!}
    @yield('after_styles')

    {{--HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries
    WARNING: Respond.js doesn't work if you view the page via file://--}}
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>




<body class="hold-transition {{ config('backpack.base.skin') }} sidebar-mini sidebar-collapse{{-- layout-top-nav--}}">



<div class="wrapper">
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div id="react-root"></div>
                </div>
            </div>
        </section>
    </div>
</div>

@yield('before_scripts')
{!! dist('vendor.js') !!}
@include('backpack::inc.alerts')
{!! dist($js) !!}


</body>
</html>
