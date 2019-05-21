<?php
namespace Module\Company\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Module\Application\Controllers\Logic\ActivityLogController;
use Prologue\Alerts\Facades\Alert;
use Module\Application\Exceptions\NotChangedException;
use Module\Application\Traits\CustomCrudTrait;
use Module\Company\Traits\Admin\CompanyCrudTrait;
use Module\Company\MarginRate;
use Module\Company\Requests\MarginRateRequest as StoreRequest;
use Module\Company\Requests\MarginRateRequest as UpdateRequest;


class MarginRateCrudController extends CrudController
{
    use CompanyCrudTrait, CustomCrudTrait;

    public function setup()
    {
        $this->crud->setModel('Module\Company\MarginRate');
        $this->crud->setRouteName('company::crud.margin-rate');
        $this->crud->setEntityNameStrings(
            __('company::margin_rate.name'),
            __('company::margin_rate.name_plural')
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
                'name' => 'rates',
                'label' => __('company::margin_rate.field.rates'),
                'orderable' => true, // custom order
                'searchLogic' => $this->customSearchLogic // custom search
            ],
        ]);


        // FIELDS
        $tab = $this->getTabs();
        $this->crud->addFields([
            [
                'name' => 'rates',
                'label' => __('company::margin_rate.field.rates'),
                'type' => 'table',
                'entity_singular' => __('company::margin_rate.field.rate'),
                'columns' => [
                    'rate' => __('company::margin_rate.field.rate') . ' (%)',
                ],
                'types' => [
                    'rate' => 'number'
                ]
            ],
        ]);


        // BUTTONS
        // $this->crud->addButton('line', 'name', 'view', 'company::admin.margin_rate.buttons.button', 'beginning');


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
        $id = $this->company->marginRate->id;
        return $this->show($id);
    }



    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['title'] = trans('backpack::crud.preview', ['name' => $this->crud->entity_name]);
        $this->data['logs'] = app(ActivityLogController::class)->getLogs(MarginRate::class, $id);

        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions columns
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        $this->crud->setShowView('company::admin.margin_rate.show');

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
        // $this->crud->setCreateView('company::admin.margin_rate.create');

        if(isset($this->crud->popup['create'])) $this->crud->trim = true;
        return view($this->crud->getCreateView(), $this->data);
    }



    public function store(StoreRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        $data = $request->all();
        $controller = app('Module\Company\Controllers\Logic\MarginRateController');
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

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit', ['name' => $this->crud->entity_name]);
        $this->data['id'] = $id;
        $this->updateDataForCustomEdit();

        $this->crud->setEditView('application::crud.custom.edit');
        // $this->crud->setEditView('company::admin.margin_rate.edit');

        if(isset($this->crud->popup['update'])) $this->crud->trim = true;
        return view($this->crud->getEditView(), $this->data);
    }



    public function update(UpdateRequest $request)
    {
        $this->crud->hasAccessOrFail('update');

        $data = $request->all();
        $data['rates'] = collect(json_decode($data['rates']))->filter(function($e){
            return isset($e->rate);
        });
        $company = $this->getCompanyFromRequest($data);

        $item = $this->crud->model->findOrFail($request->route('margin_rate'));

        $controller = app('Module\Company\Controllers\Logic\CompanyController');
        $changed = true;
        try {
            $this->beginTransaction();
            $item = $controller->updateMarginRates($company, $data['rates']);
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

        $controller = app('Module\Company\Controllers\Logic\MarginRateController');

        $this->beginTransaction();
        $controller->delete($item);
        $this->commitTransaction();

        Alert::success(trans('backpack::crud.delete_success'))->flash();
    }



    public function getTabs()
    {
        return (object)[
            'main' => __('company::margin_rate.tab.main')
        ];
    }


}
