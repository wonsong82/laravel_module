<?php
namespace Module\Company\Tests\Feature;

use Module\Company\Constants\CurrencyStatus;
use Module\Company\Controllers\Logic\CurrencyController;
use Module\Company\Currency;
use Module\Application\Tests\FeatureTestCase;

class CurrencyCrudTest extends FeatureTestCase
{
    public $currency;

    public function testCurrencyCrud()
    {
        $this->listCurrency();
        $this->createCurrency();
        //$this->readCurrency();
        $this->updateCurrency();
        $this->deleteCurrency();
    }


    public function listCurrency()
    {
        // index page
        $res = $this->get(route('company::crud.currency.index'));
        $res->assertOk();

        // search ajax
        $res = $this->post(route('company::crud.currency.search'), [
            'search' => ['value' => 'USD']
        ]);
        $res->assertOk();
        $this->assertTrue(!!strstr($res->json()['data'][0][2], 'USD'));

        // sort

        // filter
    }


    public function createCurrency()
    {
        // create page
        $res = $this->get(route('company::crud.currency.create'));
        $res->assertForbidden();

        // store
        $res = $this->post(route('company::crud.currency.store'), [
            'status_code' => CurrencyStatus::ACTIVE,
            'company_id' => $this->company->id,
            'code' => 'string',
            'name' => 'string',
            'symbol' => 'string'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertForbidden();

        // create
        app(CurrencyController::class)->create($this->company, [
            'status_code' => CurrencyStatus::ACTIVE,
            'code' => 'string',
            'name' => 'string',
            'symbol' => 'string'
        ]);

        // exist
        $currency = Currency::where('code', 'string')->first();
        $this->assertNotNull($currency);
        $this->currency = $currency;
    }


    public function readCurrency()
    {
        // show page
        $res = $this->get(route('company::crud.currency.show', ['currency' => $this->currency->id]));
        $res->assertOk();
    }


    public function updateCurrency()
    {
        // edit page
        $res = $this->get(route('company::crud.currency.edit', ['currency' => $this->currency->id]));
        $res->assertOk();

        // update
        $res = $this->put(route('company::crud.currency.update', ['currency' => $this->currency->id]), [
            'status_code' => CurrencyStatus::ACTIVE,
            'company_id' => $this->company->id,
            'code' => 'string',
            'name' => 'string2',
            'symbol' => 'string'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // update check
        $currency = Currency::find($this->currency->id);
        $this->assertTrue($currency->name == 'string2');
    }


    public function deleteCurrency()
    {
        // delete
        $res = $this->delete(route('company::crud.currency.destroy', ['currency' => $this->currency->id]));
        $res->assertForbidden();
        $res->assertSessionHasNoErrors();

        // delete check
        $currency = Currency::find($this->currency->id);
        $this->assertNotNull($currency);
    }
}
