<?php

namespace Module\Company\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use Illuminate\Support\Facades\DB;
use Module\Company\CurrencyRate;
use Module\Company\Requests\Admin\CurrencyRateRequest as StoreRequest;
use Module\Company\Requests\Admin\CurrencyRateRequest as UpdateRequest;

/**
 * Class CurrencyRateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CurrencyPairCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('Module\Company\CurrencyRate');
        $this->crud->setRouteName('business::crud.currency-pair');
        $this->crud->setEntityNameStrings('currency pair', 'currency pairs');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            [
                'name' => 'base_currency_code',
                'label' => 'Base Currency'
            ],
            [
                'name' => 'quote_currency_code',
                'label' => 'Quote Currency'
            ],
            [
                'name' => 'pair',
                'label' => 'Pair'
            ],
            [
                'name' => 'latest_rate',
                'label' => 'Rate'
            ]
        ]);
        $this->crud->addButtonFromModelFunction('line', 'btn_view_rate_history', 'btnViewRateHistory', 'end');


        $this->crud->query
            ->select('base_currency_id', 'quote_currency_id', 'pair')
            ->groupBy('base_currency_id', 'quote_currency_id', 'pair');


        if($this->request->has('base_currency')){
            $this->crud->query->whereHas('base_currency', function($q){
                $q->where('currencies.code', $this->request->get('base_currency'));
            });
        }


        $this->crud->allowAccess('list');
        $this->crud->denyAccess(['create', 'delete', 'update']);


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
