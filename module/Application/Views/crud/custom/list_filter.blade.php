@extends('backpack::layout')


@section('content')
    <!-- Default box -->
    <div class="row">

        <!-- THE ACTUAL CONTENT -->
        <div class="col-md-12">
            <div class="box">
                <div class="box-header hidden-print ">
                    <div id="datatable_button_stack" class="pull-right text-right hidden-xs"></div>
                </div>

                <div class="box-body overflow-hidden">

                    <table id="crudTable" class="table table-striped table-hover display responsive nowrap" cellspacing="0">
                        <tr>
                            <th>Warehouse</th>
                            <th>In At</th>
                            <th>Out At</th>
                            <th>Memo</th>
                        </tr>

                        @foreach($data as $history)
                            <tr>
                                <td>{{$history['warehouse_id']}}</td>
                                <td>{{$history['in_at']}}</td>
                                <td>{{$history['out_at']}}</td>
                                <td>{{$history['memo']}}</td>
                            </tr>
                        @endforeach

                    </table>

                </div><!-- /.box-body -->

            </div><!-- /.box -->
        </div>

    </div>

@endsection


@section('after_styles')
    <!-- DATA TABLES -->
    <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">


    <style>
        .main-header,
        .main-sidebar,
        .main-footer,
        .content-header{
            display: none;
        }
        .content-wrapper {
            margin-left: 0;
        }
        html {
            background: #ecf0f5;
        }
    </style>

    <!-- CRUD LIST CONTENT - crud_list_styles stack -->
    {{--@stack('crud_list_styles')--}}
@endsection

@section('after_scripts')
{{--    @include('crud::inc.datatables_logic')--}}

    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>


    <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
    {{--@stack('crud_list_scripts')--}}



@endsection
