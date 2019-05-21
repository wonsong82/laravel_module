@extends('backpack::layout')



@if(!View::hasSection('header'))

@section('header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">{{ $crud->entity_name }}</span>
            <small>{{ ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name }}.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
            <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
            <li class="active">{{ trans('backpack::crud.preview') }}</li>
        </ol>
    </section>
@endsection

@endif





@if(!View::hasSection('content'))

@section('content')
    @if ($crud->hasAccess('list'))
        <a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all', ['name' => $crud->entity_name_plural]) }}</a><br><br>
    @endif

    <div class="row">
        <div class="col-md-12">

            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    @if($crud->hasAccess('update'))
                        <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-primary form-button-update pull-right popup-btn"><i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}</a>
                    @endif
                    <h3 class="box-title">{{ $crud->entity_name }}</h3>
                </div>
                <div class="box-body no-padding">
                    <table class="table table-striped table-bordered">
                        <tbody>
                        @foreach ($crud->columns as $column)
                            @if($column['name'] != 'row_number')
                                <tr>
                                    <td>
                                        <strong>{{ $column['label'] }}</strong>
                                    </td>
                                    <td>
                                        @if (!isset($column['type']))
                                            @include('crud::columns.text')
                                        @else
                                            @if(view()->exists('vendor.backpack.crud.columns.'.$column['type']))
                                                @include('vendor.backpack.crud.columns.'.$column['type'])
                                            @else
                                                @if(view()->exists('crud::columns.'.$column['type']))
                                                    @include('crud::columns.'.$column['type'])
                                                @else
                                                    @include('crud::columns.text')
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        @if ($crud->buttons->where('stack', 'line')->count())
                            <tr>
                                <td><strong>{{ trans('backpack::crud.actions') }}</strong></td>
                                <td>
                                    @include('crud::inc.button_stack', ['stack' => 'line'])
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->

        </div>
    </div>
@endsection

@endif


@section('after_styles')
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/show.css') }}">
    @include('application::crud.custom.ajax-datatable-css')
    @stack('crud_fields_styles')
@endsection

@section('after_scripts')
    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/show.js') }}"></script>
    @include('application::crud.custom.ajax-datatable-js')
    @stack('crud_fields_scripts')
@endsection
