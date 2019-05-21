<?php
namespace Module\Company\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use DateTimeZone;
use Module\Application\Constant;
use Module\Application\Constants\Timezone;
use Module\Application\Controllers\Logic\ActivityLogController;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\Facades\Alert;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\Locale;
use Module\Application\Traits\AddressCrudTrait;
use Module\Application\Traits\CustomCrudTrait;
use Module\Company\Controllers\Logic\CurrencyController;
use Module\Company\Traits\Admin\CompanyCrudTrait;
use Module\Company\Company;
use Module\Company\Requests\CompanyRequest as StoreRequest;
use Module\Company\Requests\CompanyRequest as UpdateRequest;


class CompanyCrudController extends CrudController
{
    use CustomCrudTrait, AddressCrudTrait;

    public function setup()
    {
        $this->crud->setModel('Module\Company\Company');
        $this->crud->setRouteName('company::crud.company');
        $this->crud->setEntityNameStrings(
            __('company::company.name'),
            __('company::company.name_plural')
        );


        $this->enableCustomSearchLogic();

        $company = auth()->user()->company;


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
                'label' => __('company::company.field.status'),
                'orderable' => true
            ],
            [
                'name' => 'name',
                'label' => __('company::company.field.name'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'phone',
                'label' => __('company::company.field.phone'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'physical_address_text',
                'label' => __('company::company.field.address'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ]
        ]);


        // FIELDS
        $tab = $this->getTabs();

        if(!$company) {
            $this->crud->addField([
                'name' => 'status_code',
                'label' => __('company::company.field.status'),
                'type' => 'select_from_array',
                'options' => Constant::getOptions('COMPANY_STATUS'),
                'attributes' => [
                    'placeholder' => __('company::company.field.status')
                ],
                'tab' => $tab->main
            ]);
        }
        else {
            $this->crud->addField([
                'name' => 'status_code',
                'type' => 'hidden'
            ]);
        }

        $this->crud->addField([
            'name' => 'name',
            'label' => __('company::company.field.name'),
            'attributes' => [
                'placeholder' => __('company::company.field.name')
            ],
            'tab' => $tab->main
        ]);

        $this->crud->addField([
            'name' => 'legal_name',
            'label' => __('company::company.field.legal_name'),
            'attributes' => [
                'placeholder' => __('company::company.field.legal_name')
            ],
            'tab' => $tab->main
        ]);

        $this->crud->addField([
            'name' => 'desc',
            'label' => __('company::company.field.desc'),
            'attributes' => [
                'placeholder' => __('company::company.field.desc')
            ],
            'tab' => $tab->main
        ]);

        $this->crud->addField([
            'name' => 'phone',
            'label' => __('company::company.field.phone'),
            'attributes' => [
                'placeholder' => __('company::company.field.phone')
            ],
            'tab' => $tab->main,
        ]);

        $this->crud->addField([
            'name' => 'fax',
            'label' => __('company::company.field.fax'),
            'attributes' => [
                'placeholder' => __('company::company.field.fax')
            ],
            'tab' => $tab->main,
        ]);

        $this->crud->addField([
            'name' => 'email',
            'label' => __('company::company.field.email'),
            'attributes' => [
                'placeholder' => __('company::company.field.email')
            ],
            'tab' => $tab->main,
        ]);

        $this->crud->addField([
            'name' => 'website',
            'label' => __('company::company.field.website'),
            'attributes' => [
                'placeholder' => __('company::company.field.website')
            ],
            'tab' => $tab->main,
        ]);

        $this->crud->addField([
            'name' => 'currency_code',
            'label' => __('company::company.field.currency'),
            'type' => 'select2_from_array',
            'options' => $this->getDefaultCurrencyList(),
            'attributes' => [
                'placeholder' => __('company::company.field.currency')
            ],
            'tab' => $tab->main,
        ], 'create');

        if($company){
            $this->crud->addField([
                'name' => 'currency_id',
                'label' => __('company::company.field.currency'),
                'type' => 'select2_from_array',
                'options' => $company->currencies->pluck('text', 'id'),
                'attributes' => [
                    'placeholder' => __('company::company.field.currency')
                ],
                'tab' => $tab->main,
            ], 'update');
        }
        else {
            $company = Company::find(request()->route('company'));

            if($company){
                $this->crud->addField([
                    'name' => 'currency_id',
                    'label' => __('company::company.field.currency'),
                    'type' => 'select2_from_array',
                    'options' => $company->currencies->pluck('text', 'id'),
                    'attributes' => [
                        'placeholder' => __('company::company.field.currency')
                    ],
                    'tab' => $tab->main,
                ], 'update');
            }
        }

        $this->crud->addField([
            'name' => 'timezone',
            'label' => __('company::company.field.timezone'),
            'type' => 'select2_from_array',
            'options' => Timezone::getTimezoneOptions(),
            'default' => 'America/New_York',
            'attributes' => [
                'placeholder' => __('company::company.field.timezone')
            ],
            'tab' => $tab->main,
        ], 'create');

        $this->crud->addField([
            'name' => 'timezone',
            'label' => __('company::company.field.timezone'),
            'type' => 'select2_from_array',
            'options' => Timezone::getTimezoneOptions(),
            'attributes' => [
                'placeholder' => __('company::company.field.timezone')
            ],
            'tab' => $tab->main,
        ], 'update');

        $this->crud->addField([
            'name' => 'locale_id',
            'label' => __('company::company.field.locale'),
            'type' => 'select2',
            'entity' => 'locale',
            'attribute' => 'language_name',
            'model' => Locale::class,
            'default' => Locale::findByLocale('en-US')->id,
            'attributes' => [
                'placeholder' => __('company::company.field.locale')
            ],
            'tab' => $tab->main,
        ], 'create');

        $this->crud->addField([
            'name' => 'locale_id',
            'label' => __('company::company.field.locale'),
            'type' => 'select2',
            'entity' => 'locale',
            'attribute' => 'language_name',
            'model' => Locale::class,
            'attributes' => [
                'placeholder' => __('company::company.field.locale')
            ],
            'tab' => $tab->main,
        ], 'update');

        $this->crud->addField([
            'name' => 'note',
            'label' => __('company::company.field.note'),
            'attributes' => [
                'rows' => 5,
                'placeholder' => __('company::company.field.note')
            ],
            'type' => 'textarea',
            'tab' => $tab->main,
        ]);

        $this->addAddressFields($tab);


        // QUERY

        // Eager Loading for columns
        // $this->crud->with('');



        $this->enableCustomOrder();


        if($this->crud->model->hasOrder){
            $this->crud->enableReorder('code', 1);
            $this->crud->allowAccess('reorder');
        }

        $this->crud->allowAccess('show');


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
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(Company::class);

        // 유저 컴페니있으면 컴페니 보여줌
        if($company = auth()->user()->company){
            $id = $company->id;
            return $this->show($id);
        }


        // 아니면 전체 리스트
        $this->crud->setListView('application::crud.custom.list');
        // $this->crud->setListView('company::admin.company.list');


        if(isset($this->crud->popup['list'])) $this->crud->trim = true;
        return view($this->crud->getListView(), $this->data);
    }



    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        //$id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['title'] = trans('backpack::crud.preview', ['name' => $this->crud->entity_name]);
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(Company::class, $id);

        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions columns
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        $this->crud->setShowView('company::admin.company.show');

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
        // $this->crud->setCreateView('company::admin.company.create');

        if(isset($this->crud->popup['create'])) $this->crud->trim = true;
        return view($this->crud->getCreateView(), $this->data);
    }



    public function store(StoreRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        $data = $request->all();
        $controller = app('Module\Company\Controllers\Logic\CompanyController');

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
        //$id = $this->crud->getCurrentEntryId() ?? $id;

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit_item', ['name' => $this->crud->entity_name]);
        $this->data['id'] = $id;
        $this->updateDataForCustomEdit();

        $this->crud->setEditView('application::crud.custom.edit');
        // $this->crud->setEditView('company::admin.company.edit');

        if(isset($this->crud->popup['update'])) $this->crud->trim = true;
        return view($this->crud->getEditView(), $this->data);
    }



    public function update(UpdateRequest $request)
    {
        $this->crud->hasAccessOrFail('update');

        $data = $request->all();
        $item = $this->crud->model->findOrFail($request->route('company'));

        $controller = app('Module\Company\Controllers\Logic\CompanyController');
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

        $controller = app('Module\Company\Controllers\Logic\CompanyController');

        $this->beginTransaction();
        $controller->delete($item);
        $this->commitTransaction();

        Alert::success(trans('backpack::crud.delete_success'))->flash();
    }



    public function getTabs()
    {
        return (object)[
            'main' => __('company::company.tab.main'),
            'physical_address' => __('company::company.tab.physical_address'),
            'shipping_address' => __('company::company.tab.shipping_address'),
            'billing_address' => __('company::company.tab.billing_address')
        ];
    }



    protected function getTimezone()
    {
        $timezones = [];
        foreach(DateTimeZone::listIdentifiers(DateTimeZone::ALL) as $timezone){
            $timezones[$timezone] = $timezone;
        }

        return $timezones;
    }



    protected function getDefaultCurrencyList()
    {
        $list = app(CurrencyController::class)->getDefaultList();
        $array = [];

        foreach($list as $li){
            $array[$li['code']] = $li['name'];
        }

        return $array;
    }


}
