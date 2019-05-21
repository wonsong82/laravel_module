<?php
namespace Module\Company\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Module\Application\Controllers\Logic\ActivityLogController;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\Permission;
use Module\Application\Traits\CustomCrudTrait;
use Module\Company\Controllers\Logic\RoleController;
use Module\Company\Traits\Admin\CompanyCrudTrait;
use Module\Company\Role;
use Module\Company\Requests\RoleRequest as StoreRequest;
use Module\Company\Requests\RoleRequest as UpdateRequest;


class RoleCrudController extends CrudController
{
    use CompanyCrudTrait, CustomCrudTrait;

    public function setup()
    {
        $this->crud->setModel('Module\Company\Role');
        $this->crud->setRouteName('company::crud.role');
        $this->crud->setEntityNameStrings(
            __('company::role.name'),
            __('company::role.name_plural')
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
                'name' => 'name',
                'label' => __('company::role.field.name'),
                'orderable' => true, // custom order
                'searchLogic' => $this->customSearchLogic // custom search
            ],
            [
                // n-n relationship (with pivot table)
                'label'     => __('company::role.field.permissions'),
                'type'      => 'select_multiple',
                'name'      => 'permissions',
                'entity'    => 'permissions',
                'attribute' => 'name',
                'model'     => Permission::class,
                'pivot'     => true,
            ],
        ]);


        // FIELDS
        $tab = $this->getTabs();
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => __('company::role.field.name'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::role.field.name')
                ]
            ],
            [
                'label'     => __('company::role.field.permissions'),
                'type'      => 'checklist',
                'name'      => 'permissions',
                'entity'    => 'permissions',
                'attribute' => 'name',
                'model'     => Permission::class,
                'pivot'     => true,
            ]
        ]);




        // QUERY & FILTERS
        // Eager Loading for columns
        // $this->crud->with('');


        // ORDER
        $this->enableCustomOrder();
        if($this->crud->model->hasOrder){
            $this->crud->enableReorder('code', 1);
            $this->crud->allowAccess('reorder');
        }


        // ACCESS
        $this->crud->allowAccess(['show']);



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
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(Role::class);

        $this->crud->setListView('application::crud.custom.list');
        // $this->crud->setListView('company::admin.role.list');

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
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(Role::class, $id);
        $this->data['permissions'] = app(RoleController::class)->getPermissions($this->data['entry']);



        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions columns
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        $this->crud->setShowView('company::admin.role.show');

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
        // $this->crud->setCreateView('company::admin.role.create');

        if(isset($this->crud->popup['create'])) $this->crud->trim = true;
        return view($this->crud->getCreateView(), $this->data);
    }



    public function store(StoreRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        $data = $request->all();
        $controller = app('Module\Company\Controllers\Logic\RoleController');
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
        $this->data['title'] = trans('backpack::crud.edit', ['name' => $this->crud->entity_name]);
        $this->data['id'] = $id;
        $this->updateDataForCustomEdit();

        $this->crud->setEditView('application::crud.custom.edit');
        // $this->crud->setEditView('company::admin.role.edit');

        if(isset($this->crud->popup['update'])) $this->crud->trim = true;
        return view($this->crud->getEditView(), $this->data);
    }



    public function update(UpdateRequest $request)
    {
        $this->crud->hasAccessOrFail('update');

        $data = $request->all();
        $item = $this->crud->model->findOrFail($request->route('role'));
        $controller = app('Module\Company\Controllers\Logic\RoleController');
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

        $controller = app('Module\Company\Controllers\Logic\RoleController');

        $this->beginTransaction();
        $controller->delete($item);
        $this->commitTransaction();

        Alert::success(trans('backpack::crud.delete_success'))->flash();
    }



    public function getTabs()
    {
        return (object)[
            'main' => __('company::role.tab.main')
        ];
    }


}
