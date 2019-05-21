@extends('application::crud.custom.show')


@section('content')
<div class="row">
    <div class="col-md-12">

        <div class="box">
            <div class="box-header with-border">
                @if($crud->hasAccess('update'))
                    <button href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-primary form-button-update pull-right" onclick="openSinglePopup(this)"><i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}</button>
                @endif
                <h3 class="box-title">{{ $crud->entity_name }}</h3>
            </div>

            <div class="box-body">

                <div class="row">
                    <div class="col-md-2">
                        <h5 class="box-title">@lang('company::company.show.company_info')</h5>
                    </div>
                    <div class="col-md-10">
                        <table class="table table-hover no-margin">
                            <tr>
                                <td>
                                    <span class="field-label">@lang('company::company.field.name')</span>
                                    {!! $entry->name ?? '&nbsp;' !!}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="field-label">@lang('company::company.field.legal_name')</span>
                                    {!! $entry->legal_name ?? '&nbsp;' !!}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="field-label">@lang('company::company.field.desc')</span>
                                    {!! $entry->desc ?? '&nbsp;' !!}
                                </td>
                            </tr>
                        </table>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-hover no-margin">
                                    <tr>
                                        <td>
                                            <span class="field-label">@lang('company::company.field.phone')</span>
                                            {!! $entry->phone ?? '&nbsp;' !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="field-label">@lang('company::company.field.email')</span>
                                            {!! $entry->email ?? '&nbsp;' !!}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-hover no-margin">
                                    <tr>
                                        <td>
                                            <span class="field-label">@lang('company::company.field.fax')</span>
                                            {!! $entry->fax ?? '&nbsp;' !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="field-label">@lang('company::company.field.website')</span>
                                            {!! $entry->website ?? '&nbsp;' !!}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <h5 class="box-title">@lang('company::company.show.address')</h5>
                    </div>
                    <div class="col-md-10">
                        <table class="table table-hover no-margin">
                            <tr>
                                <td>
                                    <span class="field-label">@lang('company::company.field.physical')</span>
                                    {!! $entry->physical_address_text ?? '&nbsp;' !!}
                                </td>
                            </tr>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-hover">
                                    <tr>
                                        <td>
                                            <span class="field-label">@lang('company::company.field.shipping')</span>
                                            {!! $entry->shipping_address_text ?? '&nbsp;' !!}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-hover">
                                    <tr>
                                        <td>
                                            <span class="field-label">@lang('company::company.field.billing')</span>
                                            {!! $entry->billing_address_text ?? '&nbsp;' !!}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2">
                                <h5 class="box-title">@lang('company::company.show.additional_info')</h5>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <table class="table table-hover no-margin">
                                            <tr>
                                                <td>
                                                    <span class="field-label">@lang('company::company.field.currency')</span>
                                                    {!! $entry->currency->text ?? '&nbsp;' !!}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table table-hover no-margin">
                                            <tr>
                                                <td>
                                                    <span class="field-label">@lang('company::company.field.locale')</span>
                                                    {!! $entry->locale->flag_html !!}
                                                    {!! $entry->locale->language_name ?? '&nbsp;' !!}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table table-hover no-margin">
                                            <tr>
                                                <td>
                                                    <span class="field-label">@lang('company::company.field.timezone')</span>
                                                    {!! $entry->timezone ?? '&nbsp;' !!}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <table class="table table-hover">
                                    <tr>
                                        <td>
                                            <span class="field-label">@lang('company::company.field.note')</span>
                                            {!! $entry->note ?? '&nbsp;<br>&nbsp;' !!}
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection
