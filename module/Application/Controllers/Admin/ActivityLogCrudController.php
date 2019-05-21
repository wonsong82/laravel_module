<?php
namespace Module\Application\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Module\Application\ActivityLog;
use Module\Application\Traits\CustomCrudTrait;
use Module\Company\Traits\Admin\CompanyCrudTrait;
use Illuminate\Http\Request as StoreRequest;
use Illuminate\Http\Request as UpdateRequest;


class ActivityLogCrudController extends CrudController
{
    use CompanyCrudTrait, CustomCrudTrait;

    public function setup()
    {
        $this->crud->setModel('Module\Application\ActivityLog');
        $this->crud->setRouteName('application::crud.log');
        $this->crud->setEntityNameStrings('Log', 'Logs');


        //$company = $this->setupCompanyCrud();
        $this->enableCustomSearchLogic();


        // COLUMNS
        $this->crud->addColumn([
            'name' => 'row_number',
            'type' => 'row_number',
            'label' => '#'
        ])->makeFirstColumn();

        $this->crud->addColumns([
            [
                'name' => 'type',
                'label' => 'Log lvl',
                'type' => 'constant'
            ],
            [
                'name' => 'model_name',
                'label' => 'Type',
            ],
            [
                'name' => 'title',
                'orderable' => false
            ],
            [
                'name' => 'text',
                'label' => 'Log',
                'orderable' => false
            ],
            [
                'name' => 'detail',
                'label' => 'Detail',
                'type' => 'closure',
                'function' => function($entry){
                    if($entry->detail){
                        return '<button class="btn btn-primary btn-xs popup-btn popup-md" href="' . route('application::crud.log.show', ['log' => $entry->id]). '" onclick="openSinglePopup(this)">View changes</button>';
                    }

                    return '';
                }
            ],
            [
                'name' => 'by',
                'label' => 'By',
                'type' => 'closure',
                'function' => function($entity){
                    return $entity->user->name ?? '-';
                },
                'orderable' => true, // custom order
                'searchLogic' => $this->customSearchLogic // custom search
            ],
            [
                'name' => 'created_at',
                'type' => 'datetime',
                'format' => 'Y-m-d h:i A'
            ]
        ]);



        // FIELDS
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => 'Label',
            ],
        ]);



        // QUERY

        // Eager Loading for columns
        $this->crud->with('user');


        $this->enableCustomOrder();


        if($this->crud->model->hasOrder){
            $this->crud->enableReorder('code', 1);
            $this->crud->allowAccess('reorder');
        }



        // Filters

        $types = ActivityLog::select('loggable_type')->distinct()->orderBy('loggable_type')->get();

        if(count($types)){
            $this->crud->addFilter([
                'name' => 'type',
                'type' => 'select2',
                'label' => 'Type'
            ], function() use ($types){
                $options = [];
                foreach($types as $type){
                    $value = class_basename($type->loggable_type);
                    $options[$value] = $value;
                }
                return $options;
            }, function($value){
                $this->crud->query->where('loggable_type', 'like', "%\\{$value}");

                if(request()->get('id')){
                    $this->crud->query->where('loggable_id', request()->get('id'));
                }
            });
        }




        $this->enablePopup('show');



        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('update');
        $this->crud->denyAccess('create');
        $this->crud->removeAllButtons();
        $this->crud->allowAccess('show');



        $this->crud->disableResponsiveTable();

        // add asterisk for fields that are required in CustomerRequest
        //$this->crud->setRequiredFields(StoreRequest::class, 'create');
        //$this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }




    public function index()
    {
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = ucfirst($this->crud->entity_name_plural);

        $this->crud->setListView('application::crud.custom.list');
        // $this->crud->setListView('application::crud.application.list');

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
        $this->data['title'] = trans('backpack::crud.preview').' '.$this->crud->entity_name;

        // remove preview button from stack:line
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete');

        // remove bulk actions colums
        $this->crud->removeColumns(['blank_first_column', 'bulk_actions']);

        if(isset($this->crud->popup['show']))
            $this->crud->trim = true;

        $this->crud->setShowView('application::crud.activity_log.show');

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
        $this->data['title'] = trans('backpack::crud.add').' '.$this->crud->entity_name;
        $this->updateDataForCustomCreate();

        $this->crud->setCreateView('application::crud.custom.create');
        // $this->crud->setCreateView('application::crud.application.create');

        if(isset($this->crud->popup['create'])) $this->crud->trim = true;
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


        $data = $request->all();

        $this->beginTransaction();
        $item = $this->crud->model->create($data);
        $this->commitTransaction();
        // END CUSTOMIZE


        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

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
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;
        $this->data['id'] = $id;
        $this->updateDataForCustomEdit();

        $this->crud->setEditView('application::crud.custom.edit');
        // $this->crud->setEditView('application::crud.application.edit');

        if(isset($this->crud->popup['update'])) $this->crud->trim = true;
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
        $this->beginTransaction();

        $data = $request->all();

        $item = $this->crud->model->findOrFail($request->route('log'));
        $item->fill($data)->save();

        $this->commitTransaction();
        // END CUSTOMIZE

        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

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

        Alert::success(trans('backpack::crud.delete_success'))->flash();

        return $this->crud->delete($id);
    }


}
