<?php
namespace Module\Company\Tests\Feature;

use Module\Company\Constants\PaytermStatus;
use Module\Company\Controllers\Logic\PaytermController;
use Module\Company\Payterm;
use Module\Application\Tests\FeatureTestCase;

class PaytermCrudTest extends FeatureTestCase
{
    public $payterm;

    public function testPaytermCrud()
    {
        $this->listPayterm();
        $this->createPayterm();
        //$this->readPayterm();
        $this->updatePayterm();
        $this->deletePayterm();
    }


    public function listPayterm()
    {
        // index page
        $res = $this->get(route('company::crud.payterm.index'));
        $res->assertOk();

        // search ajax
        $res = $this->post(route('company::crud.payterm.search'), [
            'search' => ['value' => 'net30']
        ]);
        $res->assertOk();
        $this->assertTrue(!!strstr($res->json()['data'][0][2], 'NET30'));

        // sort

        // filter
    }


    public function createPayterm()
    {
        // create page
        $res = $this->get(route('company::crud.payterm.create'));
        $res->assertForbidden();

        // store
        $res = $this->post(route('company::crud.payterm.store'), [
            'status_code' => PaytermStatus::ACTIVE,
            'company_id' => $this->company->id,
            'code' => 'string',
            'name' => 'string'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertForbidden();

        // create
        app(PaytermController::class)->create($this->company, [
            'status_code' => PaytermStatus::ACTIVE,
            'code' => 'string',
            'name' => 'string'
        ]);

        // exist
        $payterm = Payterm::where('name', 'string')->first();
        $this->assertNotNull($payterm);
        $this->payterm = $payterm;
    }


    public function readPayterm()
    {
        // show page
        $res = $this->get(route('company::crud.payterm.show', ['payterm' => $this->payterm->id]));
        $res->assertOk();
    }


    public function updatePayterm()
    {
        // edit page
        $res = $this->get(route('company::crud.payterm.edit', ['payterm' => $this->payterm->id]));
        $res->assertOk();

        // update
        $res = $this->put(route('company::crud.payterm.update', ['payterm' => $this->payterm->id]), [
            'status_code' => PaytermStatus::ACTIVE,
            'company_id' => $this->company->id,
            'code' => 'string',
            'name' => 'string2'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // update check
        $payterm = Payterm::find($this->payterm->id);
        $this->assertTrue($payterm->name == 'string2');
    }


    public function deletePayterm()
    {
        // delete
        $res = $this->delete(route('company::crud.payterm.destroy', ['payterm' => $this->payterm->id]));
        $res->assertForbidden();
        $res->assertSessionHasNoErrors();

        // delete check
        $payterm = Payterm::find($this->payterm->id);
        $this->assertNotNull($payterm);
    }
}
