@if ($crud->hasAccess('create'))
	@if(isset($crud->popup['create']))
		<button class="btn btn-primary ladda-button form-button-create" data-style="zoom-in" href="{{url($crud->route.'/create')}}" onclick="openSinglePopup(this)"><span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('backpack::crud.add', ['name' => $crud->entity_name]) }}</span></button>
	@else
		<a href="{{ url($crud->route.'/create') }}" class="btn btn-primary ladda-button form-button-create" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('backpack::crud.add', ['name' => $crud->entity_name]) }}</span></a>
	@endif

@endif