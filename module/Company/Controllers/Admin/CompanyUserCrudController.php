<?php
namespace Module\Company\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Module\Application\Constant;
use Module\Application\Controllers\Logic\ActivityLogController;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\Permission;
use Module\Application\Traits\CustomCrudTrait;
use Module\Company\Company;
use Module\Company\CompanyUser;
use Module\Company\Constants\CompanyUserStatus;
use Module\Company\Controllers\Logic\CompanyController;
use Module\Company\Requests\CompanyUserRequest as StoreRequest;
use Module\Company\Requests\CompanyUserUpdateRequest as UpdateRequest;
use Module\Company\Role;


class CompanyUserCrudController extends CrudController
{
    use CustomCrudTrait;


    protected $company;

    public function setup()
    {
        $this->crud->setModel('Module\Company\CompanyUser');

        // if came from company list
        if(request()->route('company')){
            $this->company = Company::findOrFail(request()->route('company'));
            $this->crud->setRouteName('company::crud.{company}/user', ['company' => $this->company->id]);
        }

        // else
        else {
            $this->company = auth()->user()->company ?? null;
            $this->crud->setRouteName('company::crud.user');
        }

        $this->crud->setEntityNameStrings(
            __('company::company_user.name'),
            __('company::company_user.name_plural')
        );



        $this->enableCustomSearchLogic();



        // COLUMNS
        $this->crud->addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => '#'
        ])->makeFirstColumn();

        if(!$this->company){
            $this->crud->addColumn([
                'name' => 'company_id',
                'label' => __('company::company_user.field.company'),
                'type' => 'select',
                'model' => Company::class,
                'entity' => 'company',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ]);
        }

        $this->crud->addColumns([
            [
                'name' => 'status',
                'label' => __('company::company_user.field.status'),
                'type' => 'constant',
                'orderable' => true,
            ],
            [
                'name' => 'code',
                'label' => __('company::company_user.field.code'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'name',
                'label' => __('company::company_user.field.name'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'email',
                'label' => __('company::company_user.field.email'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'label'     => __('company::company_user.field.roles'),
                'type'      => 'select_multiple',
                'name'      => 'roles',
                'entity'    => 'roles',
                'attribute' => 'name',
                'model'     => Role::class,
            ],
            [
                'label'     => __('company::company_user.field.extra_permissions'),
                'type'      => 'select_multiple',
                'name'      => 'permissions',
                'entity'    => 'permissions',
                'attribute' => 'name',
                'model'     => Permission::class,
            ],
        ]);




        // FIELDS
        $tab = $this->getTabs();

        if($this->company){
            $this->crud->addField([
                'name' => 'company_id',
                'type' => 'hidden',
                'value' => $this->company->id
            ]);
        }

        else {
            $this->crud->addField([
                'name' => 'company_id',
                'label' => __('company::company_user.field.company'),
                'type' => 'select2',
                'model' => Company::class,
                'entity' => 'company',
                'attribute' => 'name',
                'tab' => $tab->main
            ], 'create');

            $this->crud->addField([
                'name' => 'company_id',
                'label' => __('company::company_user.field.company'),
                'type' => 'select',
                'model' => Company::class,
                'entity' => 'company',
                'attribute' => 'name',
                'attributes' => [
                    'disabled' => 'disabled'
                ],
                'tab' => $tab->main
            ], 'update');
        }


        $this->crud->addField([
            'name' => 'status_code',
            'type' => 'hidden',
            'value' => CompanyUserStatus::ACTIVE
        ], 'create');

        $this->crud->addField([
            'name' => 'status_code',
            'label' => __('company::company_user.field.status'),
            'type' => 'select_from_array',
            'options' => Constant::getOptions('COMPANY_USER_STATUS'),
            'attributes' => [
                'placeholder' => __('company::company_user.field.status')
            ],
            'tab' => $tab->main,
        ], 'update');

        $this->crud->addField([
            'name' => 'code',
            'label' => __('company::company_user.field.code'),
            'attributes' => [
                'placeholder' => __('company::company_user.field.code')
            ],
            'hint' => __('company::company_user.field.code_hint'),
            'tab' => $tab->main,
        ], 'create');

        $this->crud->addField([
            'name' => 'code',
            'label' => __('company::company_user.field.code'),
            'attributes' => [
                'placeholder' => __('company::company_user.field.code')
            ],
            'tab' => $tab->main,
        ], 'update');

        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => __('company::company_user.field.name'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::company_user.field.name')
                ],
                'tab' => $tab->main
            ],
            [
                'name' => 'email',
                'label' => __('company::company_user.field.email'),
                'type' => 'text',
                'attributes' => [
                    'autocomplete' => 'new-email', // to prevent auto complete
                    'placeholder' => __('company::company_user.field.email')
                ],
                'tab' => $tab->main
            ],
            [
                'name' => 'password',
                'label' => __('company::company_user.field.password'),
                'type' => 'password',
                'attributes' => [
                    'autocomplete' => 'new-password', // to prevent auto complete
                    'placeholder' => __('company::company_user.field.password')
                ],
                'tab' => $tab->main
            ],
            [
                'name' => 'password_confirmation',
                'label' => __('company::company_user.field.password_confirmation'),
                'type' => 'password',
                'attributes' => [
                    'autocomplete' => 'new-password-confirm', // to prevent auto complete
                    'placeholder' => __('company::company_user.field.password_confirmation')
                ],
                'tab' => $tab->main
            ],
            [
                'name' => 'roles_and_permissions',
                'label' => __('company::company_user.field.roles_and_permissions'),
                'type' => 'checklist_dependency',
                'field_unique_name' => 'user_role_permission',
                'subfields' => [
                    'primary' => [
                        'label'            => __('company::company_user.field.roles'),
                        'name'             => 'roles',
                        'entity'           => 'roles',
                        'entity_secondary' => 'permissions',
                        'attribute'        => 'name',
                        'model'            => Role::class,
                        'scope'            => function($q){
                            if($this->company)
                                $q->where('company_id', $this->company->id);
                            else {
                                $q->where('id', '<', 1); // no role selection for super admin
                            }
                        },
                        'pivot'            => true,
                        'number_columns'   => 3,
                    ],
                    'secondary' => [
                        'label'          => __('company::company_user.field.permissions'),
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
            ],
        ]);





        // QUERY

        // Eager Loading for columns
        $this->crud->with('user'); // auth user

        if($this->company){
            $this->crud->query->where('company_id', $this->company->id);
        }



        $this->enableCustomOrder();


        if($this->crud->model->hasOrder){
            $this->crud->enableReorder('code', 1);
            $this->crud->allowAccess('reorder');
        }



        $this->crud->allowAccess(['show']);


        $this->enablePopup('create', 'update', 'reorder'); // list, create, update, show



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
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(CompanyUser::class);

        $this->crud->setListView('application::crud.custom.list');
        // $this->crud->setListView('company::admin.company_user.list');

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
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(CompanyUser::class, $id);

        $this->data['roles'] = $this->data['entry']->roles;
        $this->data['permissions'] = app(CompanyController::class)->getUserPermissions($this->data['entry']);

        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions columns
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        $this->crud->setShowView('company::admin.company_user.show');

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
        // $this->crud->setCreateView('company::admin.company_user.create');

        if(isset($this->crud->popup['create'])) $this->crud->trim = true;
        return view($this->crud->getCreateView(), $this->data);
    }



    public function store(StoreRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        $data = $request->all();
        $controller = app('Module\Company\Controllers\Logic\CompanyController');
        $company = Company::findOrFail($data['company_id']);

        $this->beginTransaction();
        $item = $controller->createUser($company, $data);
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
        // $this->crud->setEditView('company::admin.company_user.edit');


        if(isset($this->crud->popup['update'])) $this->crud->trim = true;
        return view($this->crud->getEditView(), $this->data);
    }



    public function update(UpdateRequest $request)
    {
        $this->crud->hasAccessOrFail('update');

        $data = $request->all();
        $item = $this->crud->model->findOrFail($request->route('user'));
        $controller = app('Module\Company\Controllers\Logic\CompanyController');
        $changed = true;
        try {
            $this->beginTransaction();
            $item = $controller->updateUser($item, $data);
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

        $controller = app('Module\Company\Controllers\Logic\CompanyController');

        $this->beginTransaction();
        $controller->deleteUser($item);
        $this->commitTransaction();

        Alert::success(trans('backpack::crud.delete_success'))->flash();
    }



    public function getTabs()
    {
        return (object)[
            'main' => __('company::company_user.tab.main'),
            'role' => __('company::company_user.tab.role')
        ];
    }


}
