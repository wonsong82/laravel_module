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
use Module\Company\Currency;
use Module\Company\Requests\CurrencyRequest as StoreRequest;
use Module\Company\Requests\CurrencyRequest as UpdateRequest;


class CurrencyCrudController extends CrudController
{
    use CompanyCrudTrait, CustomCrudTrait;

    public function setup()
    {
        $this->crud->setModel('Module\Company\Currency');
        $this->crud->setRouteName('company::crud.currency');
        $this->crud->setEntityNameStrings(
            __('company::currency.name'),
            __('company::currency.name_plural')
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
                'label' => __('company::currency.field.status'),
                'orderable' => true,
            ],
            [
                'name' => 'code',
                'label' => __('company::currency.field.code'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'name',
                'label' => __('company::currency.field.name'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'code_n',
                'label' => __('company::currency.field.code_n'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'symbol',
                'label' => __('company::currency.field.symbol'),
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'symbol_position',
                'label' => __('company::currency.field.symbol_position'),
                'type' => 'constant',
                'orderable' => false,
            ],
            [
                'name' => 'decimal_count',
                'label' => __('company::currency.field.decimal_count'),
                'orderable' => false,
            ],
            [
                'name' => 'decimal_separator',
                'label' => __('company::currency.field.decimal_separator'),
                'orderable' => false,
            ],
            [
                'name' => 'thousand_separator',
                'label' => __('company::currency.field.thousand_separator'),
                'orderable' => false,
            ],
        ]);


        // FIELDS
        $tab = $this->getTabs();
        $this->crud->addFields([
            [
                'name' => 'status_code',
                'label' => __('company::currency.field.status'),
                'type' => 'select_from_array',
                'options' => Constant::getOptions('CURRENCY_STATUS'),
                'attributes' => [
                    'placeholder' => __('company::currency.field.status')
                ],
            ],
            [
                'name' => 'code',
                'label' => __('company::currency.field.code'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::currency.field.code'),
                    'readonly' => 'readonly'
                ]
            ],
            [
                'name' => 'name',
                'label' => __('company::currency.field.name'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::currency.field.name')
                ]
            ],
            [
                'name' => 'code_n',
                'label' => __('company::currency.field.code_n'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::currency.field.code_n'),
                    'readonly' => 'readonly'
                ]
            ],
            [
                'name' => 'symbol',
                'label' => __('company::currency.field.symbol'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::currency.field.symbol'),
                    'readonly' => 'readonly'
                ]
            ],
            [
                'name' => 'symbol_position_code',
                'label' => __('company::currency.field.symbol_position'),
                'type' => 'select_from_array',
                'options' => Constant::getOptions('CURRENCY_SYMBOL_POSITION'),
                'attributes' => [
                    'placeholder' => __('company::currency.field.symbol_position'),
                    'readonly' => 'readonly'
                ]
            ],
            [
                'name' => 'decimal_count',
                'label' => __('company::currency.field.decimal_count'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::currency.field.decimal_count'),
                    'readonly' => 'readonly'
                ]
            ],
            [
                'name' => 'decimal_separator',
                'label' => __('company::currency.field.decimal_separator'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::currency.field.decimal_separator'),
                    'readonly' => 'readonly'
                ]
            ],
            [
                'name' => 'thousand_separator',
                'label' => __('company::currency.field.thousand_separator'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __('company::currency.field.thousand_separator'),
                    'readonly' => 'readonly'
                ]
            ],
        ]);


        // BUTTONS
        $this->crud->addButton('line', 'toggle_active_btn', 'view', 'company::admin.currency.buttons.toggle_active', 'beginning');


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
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(Currency::class);

        $this->crud->setListView('application::crud.custom.list');
        // $this->crud->setListView('company::admin.currency.list');

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
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(Currency::class, $id);

        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions columns
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        // $this->crud->setShowView('company::admin.currency.show');

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
        // $this->crud->setCreateView('company::admin.currency.create');

        if(isset($this->crud->popup['create'])) $this->crud->trim = true;
        return view($this->crud->getCreateView(), $this->data);
    }



    public function store(StoreRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        $data = $request->all();
        clear_null($data, ['decimal_count', 'decimal_separator', 'thousand_separator']);

        $controller = app('Module\Company\Controllers\Logic\CurrencyController');
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
        // $this->crud->setEditView('company::admin.currency.edit');

        if(isset($this->crud->popup['update'])) $this->crud->trim = true;
        return view($this->crud->getEditView(), $this->data);
    }



    public function update(UpdateRequest $request)
    {
        $this->crud->hasAccessOrFail('update');

        $data = $request->all();
        clear_null($data, ['decimal_count', 'decimal_separator', 'thousand_separator']);

        $item = $this->crud->model->findOrFail($request->route('currency'));
        $controller = app('Module\Company\Controllers\Logic\CurrencyController');
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

        $controller = app('Module\Company\Controllers\Logic\CurrencyController');

        $this->beginTransaction();
        $controller->delete($item);
        $this->commitTransaction();

        Alert::success(trans('backpack::crud.delete_success'))->flash();
    }



    public function getTabs()
    {
        return (object)[
            'main' => __('company::currency.tab.main')
        ];
    }


    public function activate(Currency $currency)
    {
        $this->crud->hasAccessOrFail('update');

        $item = $currency;
        $controller = app('Module\Company\Controllers\Logic\CurrencyController');
        $changed = true;
        try {
            $this->beginTransaction();
            $item = $controller->activate($currency);
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

        return redirect()->route('company::crud.currency.index');
    }


    public function deactivate(Currency $currency)
    {
        $this->crud->hasAccessOrFail('update');

        $item = $currency;
        $controller = app('Module\Company\Controllers\Logic\CurrencyController');
        $changed = true;
        try {
            $this->beginTransaction();
            $item = $controller->deactivate($currency);
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

        return redirect()->route('company::crud.currency.index');
    }


}
