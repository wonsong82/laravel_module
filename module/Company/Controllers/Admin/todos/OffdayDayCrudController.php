<?php
namespace Module\Company\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Module\Application\Traits\CustomCrudTrait;
use Module\Company\Events\OffDayCreated;
use Module\Company\Events\OffDayDeleted;
use Module\Company\Offday;
use Module\Application\Traits\LogCrudTrait;
use Module\Company\OffdayMonth;
use Module\Company\Requests\Admin\OffdayDayRequest as StoreRequest;
use Module\Company\Requests\Admin\OffdayDayRequest as UpdateRequest;
use Module\Company\Traits\Admin\CompanyCrudTrait;


class OffdayDayCrudController extends CrudController
{
    use CustomCrudTrait, CompanyCrudTrait,LogCrudTrait;

    public function setup()
    {
        $this->crud->setModel('Module\Company\Offday');
        $this->crud->setRouteName('company::crud.offday/{month}/days', ['month' => request()->route('month')]);
        $this->crud->setEntityNameStrings('Off-Day', 'Off-Days');

        $offdayMonth = request()->route('month');
        $year = substr($offdayMonth, 0, 4);
        $month = substr($offdayMonth, 4, 2);


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
                'name' => 'date_text',
                'label' => 'Date',
                'orderable' => true,
                'searchLogic' => $this->customSearchLogic
            ],
            [
                'name' => 'note',
                'label' => 'Note',
            ]
        ]);



        // FIELDS
        $this->crud->addFields([
            [
                'name' => 'day',
                'type' => 'select2_from_array',
                'options' => Offday::getDayOption($year, $month),
                'allows_null' => true,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'hint' => 'Add a Off-Day for ' . $year.'-'.$month . '.',
            ],
            [
                'name' => 'note',
                'type' => 'textarea',
                'attributes' => [
                    'placeholder' => 'Reason for Off'
                ]
            ],
            [
                'name' => 'year',
                'type' => 'hidden',
                'value' => $year
            ],
            [
                'name' => 'month',
                'type' => 'hidden',
                'value' => $month
            ]
        ]);





        // QUERY

        // Eager Loading for columns
        $this->crud->query->where('year', $year)->where('month', $month);
        $this->crud->query->orderBy('day');



        $this->enableCustomOrder();


        if($this->crud->model->hasOrder){
            $this->crud->enableReorder('code', 1);
            $this->crud->allowAccess('reorder');
        }



        $this->crud->removeButton('update');
        $this->crud->denyAccess('update');


        // add asterisk for fields that are required in CustomerRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }




    public function index()
    {
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = ucfirst($this->crud->entity_name_plural);
        $this->data['logs'] = $this->getLogs(Offday::class);
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
        $this->data['logs'] = $this->getLogs(Offday::class);
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
        $data['date'] = sprintf('%s-%s-%s', $data['year'], $data['month'], sprintf('%02d', $data['day']));

        $item = $this->crud->model->create($data);
        event(new OffDayCreated($item));
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
        $item = $this->crud->model->findOrFail($id);
        event(new OffDayDeleted($item));
        return $this->crud->delete($id);
    }


}
