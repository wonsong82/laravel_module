@if ($crud->hasAccess('update'))
	@if (!$crud->model->translationEnabled())

	<!-- Single edit button -->

	@if (isset($crud->popup['update']))
		<button class="btn btn-xs btn-default form-button-update" href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" onclick="openSinglePopup(this)"><i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}</button>
	@else
		<a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-default form-button-update"><i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}</a>
	@endif



	@else

	<!-- Edit button group -->
	<div class="btn-group">
	  <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-default form-button-update"><i class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}</a>

	  <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    <span class="caret"></span>
	    <span class="sr-only">Toggle Dropdown</span>
	  </button>
	  <ul class="dropdown-menu dropdown-menu-right">
  	    <li class="dropdown-header">{{ trans('backpack::crud.edit_translations') }}:</li>
	  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
		  	<li><a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a></li>
	  	@endforeach
	  </ul>
	</div>

	@endif
@endif