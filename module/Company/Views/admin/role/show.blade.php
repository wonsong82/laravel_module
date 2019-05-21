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
                    <h3 class="box-title">{{ $crud->entity_name }}: <b>{{ $entry->name }}</b></h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <h5 class="box-title">@lang('company::role.show.role')</h5>
                        </div>
                        <div class="col-md-10">
                            <table class="table table-hover">
                                <tr>
                                    <td>
                                        <span class="field-label">@lang('company::role.field.name')</span>
                                        {!! $entry->name ?? '&nbsp;' !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <h5 class="box-title">@lang('company::role.show.permissions')</h5>
                        </div>
                        <div class="col-md-10">
                            <table class="table table-hover table-striped">
                                @foreach($permissions as $catName => $perms)
                                    <tr>
                                        <th>{{ $catName }}</th>
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
@endsection