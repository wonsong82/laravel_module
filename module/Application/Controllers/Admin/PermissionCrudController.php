<?php
namespace Module\Application\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Module\Application\Controllers\Logic\ActivityLogController;
use Prologue\Alerts\Facades\Alert;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\Traits\CustomCrudTrait;
use Module\Company\Traits\Admin\CompanyCrudTrait;
use Module\Application\Permission;
use Module\Application\Requests\PermissionRequest as StoreRequest;
use Module\Application\Requests\PermissionRequest as UpdateRequest;


class PermissionCrudController extends CrudController
{
    use CustomCrudTrait;

    public function setup()
    {
        $this->crud->setModel('Module\Application\Permission');
        $this->crud->setRouteName('application::crud.permission');
        $this->crud->setEntityNameStrings(
            __('application::permission.name'),
            __('application::permission.name_plural')
        );


        $this->enableCustomSearchLogic();


        // COLUMNS
        $this->crud->addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => '#'
        ])->makeFirstColumn();

        $this->crud->addColumns([
            [
                'name' => 'name',
                'label' => __('application::permission.field.name'),
                'orderable' => true, // custom order
                'searchLogic' => $this->customSearchLogic // custom search
            ]
        ]);


        // FIELDS
        $tab = $this->getTabs();
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => __('application::permission.field.name'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('application::permission.field.name')
                ]
            ],
        ]);



        // QUERY

        // Eager Loading for columns
        // $this->crud->with('');




        $this->enableCustomOrder();


        if($this->crud->model->hasOrder){
            $this->crud->enableReorder('code', 1);
            $this->crud->allowAccess('reorder');
        }


        if (!config('backpack.permissionmanager.allow_permission_create')) {
            $this->crud->denyAccess('create');
        }
        if (!config('backpack.permissionmanager.allow_permission_update')) {
            $this->crud->denyAccess('update');
        }
        if (!config('backpack.permissionmanager.allow_permission_delete')) {
            $this->crud->denyAccess('delete');
        }


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
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(Permission::class);

        $this->crud->setListView('application::crud.custom.list');
        // $this->crud->setListView('application::admin.permission.list');

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
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(Permission::class, $id);

        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions columns
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        // $this->crud->setShowView('application::admin.permission.show');

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
        // $this->crud->setCreateView('application::admin.permission.create');

        if(isset($this->crud->popup['create'])) $this->crud->trim = true;
        return view($this->crud->getCreateView(), $this->data);
    }



    public function store(StoreRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        $data = $request->all();
        $controller = app('Module\Application\Controllers\Logic\PermissionController');

        $this->beginTransaction();
        $item = $controller->create($data);
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
        $this->data['title'] = trans('backpack::crud.edit', ['name' => $this->crud->entity_name]);
        $this->data['id'] = $id;
        $this->updateDataForCustomEdit();

        $this->crud->setEditView('application::crud.custom.edit');
        // $this->crud->setEditView('application::admin.permission.edit');

        if(isset($this->crud->popup['update'])) $this->crud->trim = true;
        return view($this->crud->getEditView(), $this->data);
    }



    public function update(UpdateRequest $request)
    {
        $this->crud->hasAccessOrFail('update');

        $data = $request->all();
        $item = $this->crud->model->findOrFail($request->route('permission'));
        $controller = app('Module\Application\Controllers\Logic\PermissionController');
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

        $controller = app('Module\Application\Controllers\Logic\PermissionController');

        $this->beginTransaction();
        $controller->delete($item);
        $this->commitTransaction();

        Alert::success(trans('backpack::crud.delete_success'))->flash();
    }



    public function getTabs()
    {
        return (object)[
            'main' => __('application::permission.tab.main')
        ];
    }


}
