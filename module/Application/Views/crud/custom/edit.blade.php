@extends('backpack::layout')



@if(!View::hasSection('header'))

@section('header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
            <small>{{ trans('backpack::crud.edit_item', ['name' => $crud->entity_name]) }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url(config('backpack.base.route_prefix'),'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
            <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
            <li class="active">{{ trans('backpack::crud.edit') }}</li>
        </ol>
    </section>
@endsection

@endif





@if(!View::hasSection('content'))

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            @if ($crud->hasAccess('list') && !($crud->popup['update'] ?? null))
                <a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all', ['name' => $crud->entity_name_plural]) }}</a><br><br>
            @endif

            @include('crud::inc.grouped_errors')

            <form method="post"
                  action="{{ url($crud->route.'/'.$entry->getKey()) }}"
                  @if ($crud->hasUploadFields('update', $entry->getKey()))
                  enctype="multipart/form-data"
                    @endif
            >
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}

                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans('backpack::crud.edit_item', ['name' => $crud->entity_name]) }}</h3>
                    </div>

                    <div class="box-body row display-flex-wrap" style="display: flex;flex-wrap: wrap;">

                        {{-- TAB --}}
                        @if ($crud->tabsEnabled())

                            @php
                                $horizontalTabs = $crud->getTabsType()=='horizontal' ? true : false;
                            @endphp

                            @push('crud_fields_styles')
                            <style>
                                .nav-tabs-custom {
                                    box-shadow: none;
                                }
                                .nav-tabs-custom > .nav-tabs.nav-stacked > li {
                                    margin-right: 0;
                                }

                                .tab-pane .form-group h1:first-child,
                                .tab-pane .form-group h2:first-child,
                                .tab-pane .form-group h3:first-child {
                                    margin-top: 0;
                                }
                            </style>
                            @endpush

                            @include('crud::inc.show_fields', ['fields' => $crud->getFieldsWithoutATab()])

                            <div class="tab-container {{ $horizontalTabs ? 'col-xs-12' : 'col-xs-3 m-t-10' }}">

                                <div class="nav-tabs-custom" id="form_tabs">
                                    <ul class="nav {{ $horizontalTabs ? 'nav-tabs' : 'nav-stacked nav-pills'}}" role="tablist">
                                        @foreach ($crud->getTabs() as $k => $tab)
                                            <li role="presentation" class="{{$k == 0 ? 'active' : ''}}">
                                                <a href="#tab_{{ str_slug_int($tab, "") }}" aria-controls="tab_{{ str_slug_int($tab, "") }}" role="tab" tab_name="{{ str_slug_int($tab, "") }}" data-toggle="tab" class="tab_toggler">{{ $tab }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                            </div>

                            <div class="tab-content {{$horizontalTabs ? 'col-md-12' : 'col-md-9 m-t-10'}}">

                                @foreach ($crud->getTabs() as $k => $tab)
                                    <div role="tabpanel" class="tab-pane{{$k == 0 ? ' active' : ''}}" id="tab_{{ str_slug_int($tab, "") }}">

                                        @include('crud::inc.show_fields', ['fields' => $crud->getTabFields($tab)])

                                    </div>
                                @endforeach

                            </div>

                            <input type="hidden" name="current_tab" value="{{ str_slug_int($crud->getTabs()[0], "") }}" />

                            {{-- END TAB --}}



                            {{-- NON TAB --}}

                        @else

                            @foreach($fields as $field)
                                @include($field['view'], $field['data'])
                            @endforeach

                        @endif

                    </div>

                    <div class="box-footer">
                        @include('crud::inc.form_save_buttons')
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@endif





@section('after_styles')
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/'.$action.'.css') }}">
    <!-- CRUD FORM CONTENT - crud_fields_styles stack -->
    @stack('crud_fields_styles')

@endsection

@section('after_scripts')
    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/'.$action.'.js') }}"></script>

    <!-- CRUD FORM CONTENT - crud_fields_scripts stack -->
    @stack('crud_fields_scripts')

    <script>
        jQuery('document').ready(function($){

            // Save button has multiple actions: save and exit, save and edit, save and new
            var saveActions = $('#saveActions'),
                crudForm        = saveActions.parents('form'),
                saveActionField = $('[name="save_action"]');

            saveActions.on('click', '.dropdown-menu a', function(){
                var saveAction = $(this).data('value');
                saveActionField.val( saveAction );
                crudForm.submit();
            });

            // Ctrl+S and Cmd+S trigger Save button click
            $(document).keydown(function(e) {
                if ((e.which == '115' || e.which == '83' ) && (e.ctrlKey || e.metaKey))
                {
                    e.preventDefault();
                    // alert("Ctrl-s pressed");
                    $("button[type=submit]").trigger('click');
                    return false;
                }
                return true;
            });

            // prevent duplicate entries on double-clicking the submit form
            crudForm.submit(function (event) {
                $("button[type=submit]").prop('disabled', true);
            });

            // Place the focus on the first element in the form
            @if( $crud->autoFocusOnFirstField )
                    @php
                        $focusField = array_first($fields, function($field) {
                            return isset($field['auto_focus']) && $field['auto_focus'] == true;
                        });
                    @endphp

                    @if ($focusField)
                    @php
                        $focusFieldName = !is_iterable($focusField['value']) ? $focusField['name'] : ($focusField['name'] . '[]');
                    @endphp
                window.focusField = $('[name="{{ $focusFieldName }}"]').eq(0),
                    @else
            var focusField = $('form').find('input, textarea, select').not('[type="hidden"]').eq(0),
                    @endif

                    fieldOffset = focusField.offset().top,
                scrollTolerance = $(window).height() / 2;

            focusField.trigger('focus');

            if( fieldOffset > scrollTolerance ){
                $('html, body').animate({scrollTop: (fieldOffset - 30)});
            }
            @endif

            // Add inline errors to the DOM
            @if ($crud->inlineErrorsEnabled() && $errors->any())

                window.errors = {!! json_encode($errors->messages()) !!};
            // console.error(window.errors);

            $.each(errors, function(property, messages){

                var normalizedProperty = property.split('.').map(function(item, index){
                    return index === 0 ? item : '['+item+']';
                }).join('');

                var field = $('[name="' + normalizedProperty + '[]"]').length ?
                        $('[name="' + normalizedProperty + '[]"]') :
                        $('[name="' + normalizedProperty + '"]'),
                    container = field.parents('.form-group');

                container.addClass('has-error');

                $.each(messages, function(key, msg){
                    // highlight the input that errored
                    var row = $('<div class="help-block">' + msg + '</div>');
                    row.appendTo(container);

                    // highlight its parent tab
                            @if ($crud->tabsEnabled())
                    var tab_id = $(container).parent().attr('id');
                    $("#form_tabs [aria-controls="+tab_id+"]").addClass('text-red');
                    @endif
                });
            });

            @endif

$("a[data-toggle='tab']").click(function(){
                currentTabName = $(this).attr('tab_name');
                $("input[name='current_tab']").val(currentTabName);
            });

        });
    </script>
@endsection
