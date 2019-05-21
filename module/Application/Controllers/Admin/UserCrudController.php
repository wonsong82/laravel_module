<?php
namespace Module\Application\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Module\Application\Constant;
use Module\Application\Constants\Timezone;
use Module\Application\Controllers\Logic\ActivityLogController;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\Locale;
use Module\Application\Permission;
use Module\Application\Role;
use Module\Application\Traits\CustomCrudTrait;
use Module\Application\User;
use Module\Application\Requests\UserRequest as StoreRequest;
use Module\Application\Requests\UserUpdateRequest as UpdateRequest;


class UserCrudController extends CrudController
{
    use CustomCrudTrait;

    public function setup()
    {
        $this->crud->setModel('Module\Application\User');
        $this->crud->setRouteName('application::crud.user');
        $this->crud->setEntityNameStrings(
            __('application::user.name'),
            __('application::user.name_plural')
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
                'name' => 'status',
                'label' => __('application::user.field.status'),
                'type' => 'constant',
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic,
            ],
            [
                'name' => 'email',
                'label' => __('application::user.field.email'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'name',
                'label' => __('application::user.field.name'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'timezone',
                'label' => __('application::user.field.timezone'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'locale',
                'label' => __('application::user.field.locale'),
                'type' => 'select',
                'entity' => 'locale',
                'model' => Locale::class,
                'attribute' => 'locale',
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'language',
                'label' => __('application::user.field.language'),
                'type' => 'select',
                'entity' => 'locale',
                'model' => Locale::class,
                'attribute' => 'language_name',
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'label'     => __('application::user.field.roles'),
                'type'      => 'select_multiple',
                'name'      => 'roles',
                'entity'    => 'roles',
                'attribute' => 'name',
                'model'     => Role::class,
            ],
            [
                'label'     => __('application::user.field.extra_permissions'),
                'type'      => 'select_multiple',
                'name'      => 'permissions',
                'entity'    => 'permissions',
                'attribute' => 'name',
                'model'     => Permission::class,
            ],
        ]);


        // FIELDS
        $tab = $this->getTabs();
        $this->crud->addFields([
            [
                'name' => 'status_code',
                'label' => __('application::user.field.status_code'),
                'type' => 'select_from_array',
                'options' => Constant::getOptions('USER_STATUS'),
                'attributes' => [
                    'placeholder' => __('application::user.field.status_code')
                ],
                'tab' => $tab->main
            ],
            [
                'name' => 'email',
                'label' => __('application::user.field.email'),
                'type' => 'text',
                'attributes' => [
                    'autocomplete' => 'new-email',
                    'placeholder' => __('application::user.field.email')
                ],
                'tab' => $tab->main
            ],
            [
                'name' => 'name',
                'label' => __('application::user.field.name'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('application::user.field.name')
                ],
                'tab' => $tab->main
            ],
            [
                'name' => 'password',
                'label' => __('application::user.field.password'),
                'type' => 'password',
                'attributes' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => __('application::user.field.password')
                ],
                'tab' => $tab->main
            ],
            [
                'name' => 'password_confirmation',
                'label' => __('application::user.field.password_confirmation'),
                'type' => 'password',
                'attributes' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => __('application::user.field.password_confirmation')
                ],
                'tab' => $tab->main
            ]
        ]);

        $this->crud->addFields([
            [
                'name' => 'timezone',
                'label' => __('application::user.field.timezone'),
                'type' => 'select2_from_array',
                'default' => 'America/New_York',
                'options' => Timezone::getTimezoneOptions(),
                'attributes' => [
                    'placeholder' => __('application::user.field.timezone')
                ],
                'tab' => $tab->main
            ],
            [
                'name' => 'locale_id',
                'label' => __('application::user.field.language'),
                'type' => 'select2',
                'entity' => 'locale',
                'attribute' => 'language_name',
                'model' => Locale::class,
                'default' => Locale::findByLocale('en-US')->id,
                'attributes' => [
                    'placeholder' => __('application::user.field.language')
                ],
                'tab' => $tab->main
            ],
        ], 'create');

        $this->crud->addFields([
            [
                'name' => 'timezone',
                'label' => __('application::user.field.timezone'),
                'type' => 'select2_from_array',
                'options' => Timezone::getTimezoneOptions(),
                'attributes' => [
                    'placeholder' => __('application::user.field.timezone')
                ],
                'tab' => $tab->main
            ],
            [
                'name' => 'locale_id',
                'label' => __('application::user.field.language'),
                'type' => 'select2',
                'entity' => 'locale',
                'attribute' => 'language_name',
                'model' => Locale::class,
                'attributes' => [
                    'placeholder' => __('application::user.field.language')
                ],
                'tab' => $tab->main
            ],
        ], 'update');

        $this->crud->addField([
            'name' => 'roles_and_permissions',
            'label' => __('application::user.field.roles_and_permissions'),
            'type' => 'checklist_dependency',
            'field_unique_name' => 'user_role_permission',
            'subfields' => [
                'primary' => [
                    'label'            => __('application::user.field.roles'),
                    'name'             => 'roles',
                    'entity'           => 'roles',
                    'entity_secondary' => 'permissions',
                    'attribute'        => 'name',
                    'model'            => Role::class,
                    'pivot'            => true,
                    'number_columns'   => 3,
                ],
                'secondary' => [
                    'label'          => __('application::user.field.permissions'),
                    'name'           => 'permissions',
                    'entity'         => 'permissions',
                    'entity_primary' => 'roles',
                    'attribute'      => 'name',
                    'model'          => Permission::class,
                    'pivot'          => true,
                    'number_columns' => 3,
                ],
            ],
            'tab' => $tab->role
        ]);





        // QUERY

        // Eager Loading for columns
        // $this->crud->with('');



        $this->enableCustomOrder();


        if($this->crud->model->hasOrder){
            $this->crud->enableReorder('code', 1);
            $this->crud->allowAccess('reorder');
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
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(User::class);

        $this->crud->setListView('application::crud.custom.list');
        // $this->crud->setListView('application::admin.user.list');

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
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(User::class, $id);

        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions columns
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        // $this->crud->setShowView('application::admin.user.show');

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
        // $this->crud->setCreateView('application::admin.user.create');

        if(isset($this->crud->popup['create'])) $this->crud->trim = true;
        return view($this->crud->getCreateView(), $this->data);
    }



    public function store(StoreRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        $data = $request->all();
        $controller = app('Module\Application\Controllers\Logic\UserController');

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
        // $this->crud->setEditView('application::admin.user.edit');

        if(isset($this->crud->popup['update'])) $this->crud->trim = true;
        return view($this->crud->getEditView(), $this->data);
    }



    public function update(UpdateRequest $request)
    {
        $this->crud->hasAccessOrFail('update');

        $data = $request->all();
        $item = $this->crud->model->findOrFail($request->route('user'));
        $controller = app('Module\Application\Controllers\Logic\UserController');
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

        $controller = app('Module\Application\Controllers\Logic\UserController');

        $this->beginTransaction();
        $controller->delete($item);
        $this->commitTransaction();

        Alert::success(trans('backpack::crud.delete_success'))->flash();
    }



    public function getTabs()
    {
        return (object)[
            'main' => __('application::user.tab.main'),
            'role' => __('application::user.tab.role'),
        ];
    }


}
