@extends('application::crud.custom.show')


@section('content')

    @if ($crud->hasAccess('list'))
        <a href="{{ url($crud->route) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all', ['name' => $crud->entity_name_plural]) }}</a><br><br>
    @endif

    <div class="row">
        <div class="col-md-12">

            <div class="box">
                <div class="box-header with-border">
                    @if($crud->hasAccess('update'))
                        <button href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-primary form-button-update pull-right" onclick="openSinglePopup(this)"><i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}</button>
                    @endif
                    {!! $entry->status_label !!}
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <h5 class="box-title">@lang('company::company_user.show.user')</h5>
                        </div>
                        <div class="col-md-10">
                            <table class="table table-hover no-margin">
                                <tr>
                                    <td>
                                        <span class="field-label">@lang('company::company_user.field.name')</span>
                                        {!! $entry->name ?? '&nbsp;' !!}
                                    </td>
                                </tr>
                            </table>

                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-hover">
                                        <tr>
                                            <td>
                                                <span class="field-label">@lang('company::company_user.field.email')</span>
                                                {!! $entry->email ?? '&nbsp;' !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="field-label">@lang('company::company_user.field.locale')</span>
                                                {!! $entry->user->locale->flag_html !!}
                                                {!! $entry->user->locale->language_name ?? '&nbsp;' !!}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-hover">
                                        <tr>
                                            <td>
                                                <span class="field-label">@lang('company::company_user.field.code')</span>
                                                {!! $entry->code ?? '&nbsp;' !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="field-label">@lang('company::company_user.field.timezone')</span>
                                                {!! $entry->user->timezone ?? '&nbsp;' !!}
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
                        <div class="col-md-2">
                            <h5 class="box-title">@lang('company::company_user.show.roles_permissions')</h5>
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-hover table-striped">
                                        <tr>
                                            <th>@lang('company::company_user.show.roles')</th>
                                        </tr>
                                        @foreach($roles as $role)
                                            <tr>
                                                <td>{{ $role->name }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-hover table-striped">
                                        <tr>
                                            <th colspan="{{$permissions->maxCount+1}}">@lang('company::company_user.show.permissions')</th>
                                        </tr>
                                        @foreach($permissions as $catName => $perms)
                                            <tr>
                                                <td>{{ $catName }}</td>
                                                @foreach($perms as $perm)
                                                    <td>{{ $perm['name'] }}</td>
                                                @endforeach
                                                @for($i=0; $i<$permissions->maxCount - $perms->count; $i++)
                                                    <td>&nbsp;</td>
                                                @endfor
                                            </tr>
                                        @endforeach
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