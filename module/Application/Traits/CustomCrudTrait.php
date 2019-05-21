<?php
namespace Module\Application\Traits;


use Illuminate\Support\Facades\DB;

trait CustomCrudTrait {


    protected $customSearchLogic;


    /***
     * add this before addColumns
     */
    protected function enableCustomSearchLogic()
    {
        $this->customSearchLogic = function($query, $column, $term){
            $query->search($column['name'], $term);
        };
    }


    /**
     * add this after columns
     */
    protected function enableCustomOrder()
    {
        if($this->request->has('order')){
            foreach($this->request['order'] as $order){
                $name = array_values($this->crud->columns)[(int)$order['column']]['name'];
                $dir = $order['dir'];
                $this->crud->query->order($name, $dir);
            }
        }
        else {
            $this->crud->query->order('default', null);
        }
    }


    protected function updateFieldsForCustomView($fields)
    {
        return array_map(function($field){
            $field['view'] = ($field['view_namespace'] ?? 'crud::fields') . '.' . $field['type'];
            $field['data'] = ['field' => $field];
            return $field;

        }, $fields);
    }


    protected function updateDataForCustomCreate()
    {
        $this->data['action'] = 'create';
        $this->data['fields'] = $this->updateFieldsForCustomView($this->data['fields']);
    }

    protected function updateDataForCustomEdit()
    {
        $this->data['action'] = 'edit';
        $this->data['fields'] = $this->updateFieldsForCustomView($this->data['fields']);
    }


    protected function enablePopup(...$scopes)
    {
        $popup = [];

        foreach($scopes as $_scopes){
            if(!is_array($_scopes)) $_scopes = [$_scopes];

            foreach($_scopes as $scope){
                $popup[$scope] = true;
            }
        }

        $this->crud->popup = $popup;
    }


    protected function beginTransaction()
    {
        $env = env('APP_ENV');
        if($env != 'testing'){
            DB::beginTransaction();
        }
    }

    protected function commitTransaction()
    {
        $env = env('APP_ENV');
        if($env != 'testing'){
            DB::commit();
        }
    }

    protected function rollbackTransaction()
    {
        $env = env('APP_ENV');
        if($env != 'testing'){
            DB::rollback();
        }
    }


    public function reorder()
    {
        $this->crud->hasAccessOrFail('reorder');

        if (! $this->crud->isReorderEnabled()) {
            abort(403, 'Reorder is disabled.');
        }

        // get all results for that entity
        $this->data['entries'] = $this->crud->getEntries();
        $this->data['crud'] = $this->crud;
        $this->data['title'] = trans('backpack::crud.reorder').' '.$this->crud->entity_name;

        $this->crud->setReorderView('application::crud.custom.reorder');

        if(isset($this->crud->popup['reorder'])) $this->crud->trim = true;
        return view($this->crud->getReorderView(), $this->data);
    }


    public function ajaxList()
    {
        $this->crud->hasAccessOrFail('list');
        $this->data['crud'] = $this->crud;

        $hasActions = !!$this->crud->buttons->filter(function($e){
            return $e->stack == 'line';
        })->count();

        $this->data['leftFreeze'] = 0;
        $this->data['rightFreeze'] = $hasActions ? 1 : 0;
        $this->data['searchUrl'] = url($this->crud->route.'/search').'?'. request()->getQueryString();


        return view('application::crud.custom.ajax-list', $this->data);
    }





}
