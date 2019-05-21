@if ( $crud->buttons->where('stack', 'top')->count() ||  $crud->exportButtons())
    <div class="box-header hidden-print {{ $crud->hasAccess('create')?'with-border':'' }}">

        @include('crud::inc.button_stack', ['stack' => 'top'])

        <div id="datatable_button_stack" class="pull-right text-right hidden-xs"></div>
    </div>
@endif

<div class="box-body overflow-hidden">

    {{-- Backpack List Filters --}}
    @if ($crud->filtersEnabled())
        @include('crud::inc.filters_navbar')
    @endif

    <table class="crudAjaxTable table table-striped table-hover display responsive nowrap" cellspacing="0" data-left-freeze="{{ $leftFreeze }}" data-right-freeze="{{ $rightFreeze }}" data-search-url="{!! $searchUrl !!}">
        <thead>
        <tr>
            {{-- Table columns --}}
            @foreach ($crud->columns as $column)
                <th
                        data-orderable="{{ var_export($column['orderable'], true) }}"
                        data-priority="{{ $column['priority'] }}"
                        data-visible-in-modal="{{ (isset($column['visibleInModal']) && $column['visibleInModal'] == false) ? 'false' : 'true' }}"
                >
                    {!! $column['label'] !!}
                </th>
            @endforeach

            @if ( $crud->buttons->where('stack', 'line')->count() )
                <th data-orderable="false" data-priority="{{ $crud->getActionsColumnPriority() }}">{{ trans('backpack::crud.actions') }}</th>
            @endif
        </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
        <tr>
            {{-- Table columns --}}
            @foreach ($crud->columns as $column)
                <th>{{--{!! $column['label'] !!}--}}</th>
            @endforeach

            @if ( $crud->buttons->where('stack', 'line')->count() )
                <th>{{--{{ trans('backpack::crud.actions') }}--}}</th>
            @endif
        </tr>
        </tfoot>
    </table>

</div><!-- /.box-body -->

@if ( $crud->buttons->where('stack', 'bottom')->count() )
    <div class="box-footer hidden-print">
        @include('crud::inc.button_stack', ['stack' => 'bottom'])
    </div>
@endif
