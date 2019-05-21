<?php
namespace Module\Company\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Module\Application\Traits\CustomCrudTrait;
use Module\Company\Traits\Admin\CompanyCrudTrait;
use Module\Company\Requests\Admin\CompanyRequest as StoreRequest;
use Module\Company\Requests\Admin\CompanyRequest as UpdateRequest;


class OffdayMonthCrudController extends CrudController
{
    use CompanyCrudTrait, CustomCrudTrait;

    public function setup()
    {
        $this->crud->setModel('Module\Company\Offday');
        $this->crud->setRouteName('company::crud.offday');
        $this->crud->setEntityNameStrings('Offday', 'Offdays');


        $company = $this->setupCompanyCrud();
        $this->enableCustomSearchLogic();


        // COLUMNS
        $this->crud->addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => '#'
        ])->makeFirstColumn();

        $this->crud->addColumns([
            [
                'name' => 'month_text',
                'label' => 'Offday Month',
                'orderable' => true, // custom order
                'searchLogic' => $this->customSearchLogic // custom search
            ],
            [
                'name' => 'count',
                'label' => '# of Off-days',
                'type' => 'closure',
                'function' => function($item){
                    return $item->count . ' days';
                }
            ]
        ]);



        // FIELDS
        //$this->crud->addFields([]);



        // QUERY

        // Eager Loading for columns
        // $this->crud->with('');
        $this->crud->query
            ->groupBy('year', 'month')
            ->select('year', 'month', \DB::raw('count(*) as count'));


        $this->enableCustomOrder();


        if($this->crud->model->hasOrder){
            $this->crud->enableReorder('code', 1);
            $this->crud->allowAccess('reorder');
        }


        $this->crud->removeButton('create');
        $this->crud->removeButton('delete');
        $this->crud->removeButton('update');
        $this->crud->addButtonFromModelFunction('line', 'btn_days', 'btnDays', 'beginning');


        // add asterisk for fields that are required in CustomerRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }




    public function index()
    {
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = ucfirst($this->crud->entity_name_plural);

        // $this->crud->setListView('company::crud.company.list');
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
        $this->data['title'] = trans('backpack::crud.preview').' '.$this->crud->entity_name;

        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions colums
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        // $this->crud->setShowView('company::crud.company.show');
        return view($this->crud->getShowView(), $this->data);
    }





    public function create()
    {
        $this->crud->hasAccessOrFail('create');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();
        $this->data['fields'] = $this->crud->getCreateFields();
        $this->data['title'] = trans('backpack::crud.add').' '.$this->crud->entity_name;

        // $this->updateDataForCustomCreate();
        // $this->crud->setCreateView('company::crud.company.create');
        return view($this->crud->getCreateView(), $this->data);
    }


    public function store(StoreRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        // fallback to global request instance
        if (is_null($request)) {
            $request = \Request::instance();
        }

        // replace empty values with NULL, so that it will work with MySQL strict mode on
        foreach ($request->input() as $key => $value) {
            if (empty($value) && $value !== '0') {
                $request->request->set($key, null);
            }
        }


        // BEGIN CUSTOMIZE
        \DB::beginTransaction();

        $data = $request->all();

        $item = $this->crud->model->create($data);

        \DB::commit();
        // END CUSTOMIZE


        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->setSaveAction();

        return $this->performSaveAction($item->getKey());

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
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // $this->updateDataForCustomEdit();
        // $this->crud->setEditView('company::crud.company.edit');

        return view($this->crud->getEditView(), $this->data);
    }


    public function update(UpdateRequest $request)
    {
        $this->crud->hasAccessOrFail('update');

        // fallback to global request instance
        if (is_null($request)) {
            $request = \Request::instance();
        }

        // replace empty values with NULL, so that it will work with MySQL strict mode on
        foreach ($request->input() as $key => $value) {
            if (empty($value) && $value !== '0') {
                $request->request->set($key, null);
            }
        }

        // BEGIN CUSTOMIZE
        \DB::beginTransaction();

        $data = $request->all();

        $item = $this->crud->model->findOrFail($request->get('id'));
        $item->fill($data)->save();

        \DB::commit();
        // END CUSTOMIZE

        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->setSaveAction();

        return $this->performSaveAction($item->getKey());
    }





    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        return $this->crud->delete($id);
    }


}
