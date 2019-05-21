@extends('backpack::layout')


@section('header')

@endsection





@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-wrapper">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th class="text-right">Title</th>
                                <td>{{$entry->title}}</td>
                            </tr>

                            <tr>
                                <th class="text-right">By</th>
                                <td>{{$entry->user->name ?? 'Unknown'}}</td>
                            </tr>

                            <tr>
                                <th class="text-right">Time</th>
                                <td>{{$entry->created_at->format('m/d/y h:i:s A')}}</td>
                            </tr>


                            <tr>
                                <th class="text-right">Log</th>
                                <td>{{$entry->text}}</td>
                            </tr>

                            <tr>
                                <th class="text-right">Log details</th>
                                <td class="no-padding">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td>
                                                {!! nl2br($entry->detail) !!}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="form-group">
                        <button onclick="window.parent.closePopup()" class="btn btn-default form-button-cancel pull-right"><span class="fa fa-close"></span> &nbsp;Close</button>
                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection




@section('after_styles')
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
    <!-- CRUD FORM CONTENT - crud_fields_styles stack -->
    {!! dist('application.css') !!}
    @stack('crud_fields_styles')
@endsection

@section('after_scripts')
    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>

    {!! dist('application.js') !!}

    <!-- CRUD FORM CONTENT - crud_fields_scripts stack -->
    @stack('crud_fields_scripts')

    <script>
        jQuery('document').ready(function($){



        });
    </script>
@endsection
