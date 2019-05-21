<?php
namespace Module\Company\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Module\Application\Constant;
use Module\Application\Controllers\Logic\ActivityLogController;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\Traits\CustomCrudTrait;
use Module\Company\Traits\Admin\CompanyCrudTrait;
use Module\Company\Unit;
use Module\Company\Requests\UnitRequest as StoreRequest;
use Module\Company\Requests\UnitRequest as UpdateRequest;


class UnitCrudController extends CrudController
{
    use CompanyCrudTrait, CustomCrudTrait;

    public function setup()
    {
        $this->crud->setModel('Module\Company\Unit');
        $this->crud->setRouteName('company::crud.unit');
        $this->crud->setEntityNameStrings(
            __('company::unit.name'),
            __('company::unit.name_plural')
        );


        $this->setupCompanyCrud();
        $this->enableCustomSearchLogic();


        // COLUMNS
        $this->crud->addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => '#'
        ])->makeFirstColumn();

        $this->crud->addColumns([
            [
                'name' => 'status',
                'type' => 'constant',
                'label' => __('company::unit.field.status'),
                'orderable' => true,
            ],
            [
                'name' => 'type',
                'label' => __('company::unit.field.type'),
                'type' => 'constant',
                'orderable' => true,
            ],
            [
                'name' => 'symbol',
                'label' => __('company::unit.field.symbol'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'name',
                'label' => __('company::unit.field.name'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'plural_name',
                'label' => __('company::unit.field.plural_name'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'desc',
                'label' => __('company::unit.field.desc'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
        ]);


        // FIELDS
        $tab = $this->getTabs();
        $this->crud->addFields([
            [
                'name' => 'status_code',
                'label' => __('company::unit.field.status'),
                'type' => 'select_from_array',
                'options' => Constant::getOptions('UNIT_STATUS'),
                'attributes' => [
                    'placeholder' => __('company::unit.field.status')
                ],
            ],
            [
                'name' => 'type_code',
                'type' => 'hidden'
            ],
            [
                'name' => 'type_code_text',
                'label' => __('company::unit.field.type_code'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::unit.field.type_code'),
                    'readonly' => 'readonly'
                ],
            ],
            [
                'name' => 'symbol',
                'label' => __('company::unit.field.symbol'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::unit.field.symbol'),
                    'readonly' => 'readonly'
                ]
            ],
            [
                'name' => 'name',
                'label' => __('company::unit.field.name'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::unit.field.name')
                ]
            ],
            [
                'name' => 'plural_name',
                'label' => __('company::unit.field.plural_name'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::unit.field.plural_name')
                ]
            ],
            [
                'name' => 'desc',
                'label' => __('company::unit.field.desc'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::unit.field.desc')
                ]
            ],
        ]);


        // BUTTONS
        $this->crud->addButton('line', 'toggle_active_btn', 'view', 'company::admin.unit.buttons.toggle_active', 'beginning');


        // QUERY & FILTERS
        // Eager Loading for columns
        // $this->crud->with('');


        // ORDER
        $this->enableCustomOrder();
        if($this->crud->model->hasOrder){
            $this->crud->enableReorder('name', 1);
            $this->crud->allowAccess('reorder');
        }


        // ACCESS
        $this->crud->denyAccess(['create', 'delete']);


        // POPUP
        $this->enablePopup('create', 'update', 'reorder');



        $this->crud->disableResponsiveTable();

        // add asterisk for fields that are required in CustomerRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }



    public function index()
    {
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = ucfirst($this->crud->entity_name_plural);
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(Unit::class);

        $this->crud->setListView('application::crud.custom.list');
        // $this->crud->setListView('company::admin.unit.list');

        if(isset($this->crud->popup['list'])) $this->crud->trim = true;
        return view($this->crud->getListView(), $this->data);
    }



    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['title'] = trans('backpack::crud.preview', ['name' => $this->crud->entity_name]);
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(Unit::class, $id);

        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions columns
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        // $this->crud->setShowView('company::admin.unit.show');

        if(isset($this->crud->popup['show'])) $this->crud->trim = true;
        return view($this->crud->getShowView(), $this->data);
    }



    public function create()
    {
        $this->crud->hasAccessOrFail('create');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getCreateFields();
        $this->data['title'] = trans('backpack::crud.add', ['name' => $this->crud->entity_name]);
        $this->updateDataForCustomCreate();

        $this->crud->setCreateView('application::crud.custom.create');
        // $this->crud->setCreateView('company::admin.unit.create');

        if(isset($this->crud->popup['create'])) $this->crud->trim = true;
        return view($this->crud->getCreateView(), $this->data);
    }



    public function store(StoreRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        $data = $request->all();
        $controller = app('Module\Company\Controllers\Logic\UnitController');
        $company = $this->getCompanyFromRequest($data);

        $this->beginTransaction();
        $item = $controller->create($company, $data);
        $this->commitTransaction();

        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->setSaveAction();

        $redirectLocation =  $this->performSaveAction($item->getKey());
        if(isset($this->crud->popup['create'])){
            if($redirectLocation->getTargetUrl() == $this->crud->getRoute()){
                return view('application::crud.popup.close-and-refresh-parent');
            }
        }

        return $redirectLocation;
    }



    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit_item', ['name' => $this->crud->entity_name]);
        $this->data['id'] = $id;
        $this->updateDataForCustomEdit();

        $this->crud->setEditView('application::crud.custom.edit');
        // $this->crud->setEditView('company::admin.unit.edit');

        if(isset($this->crud->popup['update'])) $this->crud->trim = true;
        return view($this->crud->getEditView(), $this->data);
    }



    public function update(UpdateRequest $request)
    {
        $this->crud->hasAccessOrFail('update');

        $data = $request->all();
        $item = $this->crud->model->findOrFail($request->route('unit'));
        $controller = app('Module\Company\Controllers\Logic\UnitController');
        $changed = true;
        try {
            $this->beginTransaction();
            $item = $controller->update($item, $data);
            $this->commitTransaction();
        }
        catch(NotChangedException $e){
            $changed = false;
        }

        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        if($changed)
            Alert::success(trans('backpack::crud.update_success'))->flash();
        else
            Alert::info(trans('backpack::crud.update_unchanged'))->flash();

        // save the redirect choice for next time
        $this->setSaveAction();

        $redirectLocation =  $this->performSaveAction($item->getKey());
        if(isset($this->crud->popup['update'])){
            if($redirectLocation->getTargetUrl() == $this->crud->getRoute()){
                return view('application::crud.popup.close-and-refresh-parent');
            }
        }

        return $redirectLocation;
    }



    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        $item = $this->crud->model->findOrFail($id);

        $controller = app('Module\Company\Controllers\Logic\UnitController');

        $this->beginTransaction();
        $controller->delete($item);
        $this->commitTransaction();

        Alert::success(trans('backpack::crud.delete_success'))->flash();
    }



    public function getTabs()
    {
        return (object)[
            'main' => __('company::unit.tab.main')
        ];
    }


    public function activate(Unit $unit)
    {
        $this->crud->hasAccessOrFail('update');

        $item = $unit;
        $controller = app('Module\Company\Controllers\Logic\UnitController');
        $changed = true;
        try {
            $this->beginTransaction();
            $item = $controller->activate($unit);
            $this->commitTransaction();
        }
        catch(NotChangedException $e){
            $changed = false;
        }

        $this->data['entry'] = $this->crud->entry = $item;
        if($changed)
            Alert::success(trans('backpack::crud.update_success'))->flash();
        else
            Alert::info(trans('backpack::crud.update_unchanged'))->flash();

        return redirect()->route('company::crud.unit.index');
    }


    public function deactivate(Unit $unit)
    {
        $this->crud->hasAccessOrFail('update');

        $item = $unit;
        $controller = app('Module\Company\Controllers\Logic\UnitController');
        $changed = true;
        try {
            $this->beginTransaction();
            $item = $controller->deactivate($unit);
            $this->commitTransaction();
        }
        catch(NotChangedException $e){
            $changed = false;
        }

        $this->data['entry'] = $this->crud->entry = $item;
        if($changed)
            Alert::success(trans('backpack::crud.update_success'))->flash();
        else
            Alert::info(trans('backpack::crud.update_unchanged'))->flash();

        return redirect()->route('company::crud.unit.index');
    }





}
