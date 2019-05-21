{{-- This button is deprecated and will be removed in CRUD 3.5 --}}

@if ($crud->hasAccess('show'))
	@if(isset($crud->popup['show']))
		<button class="btn btn-xs btn-default form-button-show" href="{{ url($crud->route.'/'.$entry->getKey()) }}" onclick="openSinglePopup(this)"><i class="fa fa-eye"></i> {{ trans('backpack::crud.preview') }}</button>
	@else
		<a href="{{ url($crud->route.'/'.$entry->getKey()) }}" class="btn btn-xs btn-default form-button-show"><i class="fa fa-eye"></i> {{ trans('backpack::crud.preview') }}</a>
	@endif
@endif