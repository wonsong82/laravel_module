@extends('backpack::layout')

@section('header')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1>{{ config('app.name') }}</h1>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <ul class="main-menu">
                {{--@include('backpack::inc.sidebar_content')--}}
            </ul>
        </div>
    </div>
@endsection


@section('after_styles')
<style>
    h1 {
        text-align: center;
        text-transform: uppercase;
    }

    .main-menu {
        display: none;
        list-style: none;
        margin: 20px 0;
        padding: 0;
        text-align: center;

    }

    .main-menu .header {
        padding: 20px 16px;
        display: inline-block;
        position: relative;
    }

    .main-menu .header + .header:before {
        content: '';
        display: block;
        position: absolute;
        width: 2px;
        height: 14px;
        background: #ddd;
        left: -2px;
        top: 27px;
    }

    .main-menu .header .header-text {
        font-size: 20px;
        font-weight: 600;
        cursor: pointer;
        color: #666;
        -webkit-transition: all 0.3s ease;
        transition: all 0.3s ease;
    }
    .main-menu .header:hover .header-text {
        color: #2B5C7D;
    }

    .main-menu .header .sub-menu {
        position: absolute;
        top: 60px;
        left: 0;
        padding: 0;
        text-align: left;
        background: #fafafa;
        display: none;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        box-shadow: 0 2px 2px 0 rgba(0,0,0,.05), 0 3px 1px -2px rgba(0,0,0,.05), 0 1px 5px 0 rgba(0,0,0,.05);
        z-index: 999;

    }
    .main-menu .header:hover .sub-menu {
        display: block;
    }



    .main-menu .header .sub-menu li{
        padding: 12px 16px;
        width: 240px;
    }
    .main-menu .header .sub-menu li a{
        color: #666;
        font-weight: 600;
    }
    .main-menu .header .sub-menu li:hover a {
        color: #2B5C7D;
    }


    .main-menu .header .sub-menu li + li {
        border-top: 1px solid #eeeeee;
    }

    .main-menu .header .sub-menu li.treeview {
        cursor: pointer;
        position: relative;
        display: block;
    }


    .main-menu .header .sub-menu li.treeview:hover .treeview-wrapper {
        display: block;
    }

    .main-menu .header .sub-menu li.treeview .treeview-wrapper {
        position: absolute;
        display: none;
        top: 0;
        left: 230px;
    }

    .main-menu .header .sub-menu .treeview .treeview-wrapper .treeview-menu {
        margin-left: 15px;
        padding: 0;
        text-align: left;
        background: #fafafa;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        display: block;
        box-shadow: 0 2px 2px 0 rgba(0,0,0,.05), 0 3px 1px -2px rgba(0,0,0,.05), 0 1px 5px 0 rgba(0,0,0,.05);
    }

    .main-menu .header .sub-menu .treeview .treeview-wrapper .treeview-menu a {
        padding: 0;
    }





    .main-menu .pull-right-container  {
        /*display: none;*/
    }
    .main-menu .menu-icon {
        display: none;
    }



</style>
@endsection

@push('after_scripts')
<script>
    $(function(){
        var $menu = $('ul.main-menu');

        var $header = null;
        $('>li', $menu).each(function(i, e){
            var $li = $(e);
            if($li.hasClass('header')){
                var $text = $('<span class="header-text"></span>').html($li.html());
                $li.empty().append($text);
                $header = $li;
            }
            else {
                if(!$('.sub-menu', $header).length){
                    $('<div class="sub-menu"></div>').appendTo($header);
                }

                $('.sub-menu', $header).append($li);
            }
        });


        $('.treeview-menu', $menu).each(function(i, e){
            $(e).wrap('<div class="treeview-wrapper"></div>');
            $(e).closest('.treeview-wrapper').css({
                top: $(e).closest('.treeview').position().top
            })

        });

        $('a', $menu).each(function(i, e){
            if($(e).attr('href') == '#'){
                $(e).click(function(){
                    return false;
                })
            }
        });

        $menu.css('display', 'block');
    });
</script>
@endpush