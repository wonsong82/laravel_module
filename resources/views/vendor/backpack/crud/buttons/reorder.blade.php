@if ($crud->reorder)
    @if ($crud->hasAccess('reorder'))

        @if(isset($crud->popup['reorder']))
            <button class="btn btn-default ladda-button" data-style="zoom-in" href="{{ url($crud->route.'/reorder') }}" onclick="openSinglePopup(this)"><span class="ladda-label"><i class="fa fa-arrows"></i> {{ trans('backpack::crud.reorder') }} {{ $crud->entity_name_plural }}</span></button>
        @else
            <a href="{{ url($crud->route.'/reorder') }}" class="btn btn-default ladda-button" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-arrows"></i> {{ trans('backpack::crud.reorder') }} {{ $crud->entity_name_plural }}</span></a>
        @endif

    @endif
@endif