<?php

namespace Module\Company\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use Module\Company\Requests\Admin\CurrencyRateRequest as StoreRequest;
use Module\Company\Requests\Admin\CurrencyRateRequest as UpdateRequest;

/**
 * Class CurrencyRateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CurrencyRateCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('Module\Company\CurrencyRate');
        $this->crud->setRouteName('business::crud.currency-rate');
        $this->crud->setEntityNameStrings('currency rate', 'currency rates');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            [
                'name' => 'pair',
                'label' => 'Pair'
            ],
            [
                'name' => 'rate',
                'label' => 'Rate'
            ],
            [
                'name' => 'date',
                'label' => 'Date'
            ]
        ]);



        if($this->request->has('base_currency') && $this->request->has('quote_currency')){
            $this->crud->query
                ->whereHas('base_currency', function($q){
                    $q->where('currencies.code', $this->request->get('base_currency'));
                })
                ->whereHas('quote_currency', function($q){
                    $q->where('currencies.code', $this->request->get('quote_currency'));
                });;
        }


        if(!$this->request->has('order'))
            $this->crud->query->orderBy('id', 'desc');



        $this->crud->allowAccess('list');
        $this->crud->denyAccess(['create', 'delete', 'update']);
        $this->crud->removeAllButtonsFromStack('line');


        // add asterisk for fields that are required in CurrencyRateRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
