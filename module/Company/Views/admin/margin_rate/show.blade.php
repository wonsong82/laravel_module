@extends('application::crud.custom.show')


@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="box">
                <div class="box-header with-border">
                    @if($crud->hasAccess('update'))
                        <button href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-primary form-button-update pull-right popup-sm" onclick="openSinglePopup(this)"><i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}</button>
                    @endif
                    <h3 class="box-title">{{ $crud->entity_name }}</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <h5 class="box-title">@lang('company::margin_rate.show.rate_table')</h5>
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6">

                                    <table class="table table-hover table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>@lang('company::margin_rate.field.rate') (%)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($entry->rates as $rate)
                                            <tr>
                                                <td>{{ $rate->rate }}%</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection