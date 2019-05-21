<?php
namespace Module\Company\Tests\Feature;

use Module\Company\MarginRate;
use Module\Application\Tests\FeatureTestCase;

class MarginRateCrudTest extends FeatureTestCase
{
    public $marginRate;

    public function testMarginRateCrud()
    {
        $this->listMarginRate();
        $this->createMarginRate();
        //$this->readMarginRate();
        $this->updateMarginRate();
        $this->deleteMarginRate();
    }


    public function listMarginRate()
    {
        // index page
        $res = $this->get(route('company::crud.margin-rate.index'));
        $res->assertOk();

        // search ajax
        $res = $this->post(route('company::crud.margin-rate.search'), [
            'search' => ['value' => 'search']
        ]);
        $res->assertOk();
        //$this->assertTrue(!!strstr($res->json()['data'][0][1], 'search'));

        // sort

        // filter
    }


    public function createMarginRate()
    {
        // create page
        $res = $this->get(route('company::crud.margin-rate.create'));
        $res->assertOk();

        // store
        $res = $this->post(route('company::crud.margin-rate.store'), [
            'name' => 'string'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk(); // for popup
        // $res->assertRedirect(); // for non popup

        // exist
        $marginRate = MarginRate::where('name', 'string')->first();
        $this->assertNotNull($marginRate);
        $this->marginRate = $marginRate;
    }


    public function readMarginRate()
    {
        // show page
        $res = $this->get(route('company::crud.margin-rate.show', ['margin_rate' => $this->marginRate->id]));
        $res->assertOk();
    }


    public function updateMarginRate()
    {
        // edit page
        $res = $this->get(route('company::crud.margin-rate.edit', ['margin_rate' => $this->marginRate->id]));
        $res->assertOk();

        // update
        $res = $this->put(route('company::crud.margin-rate.update', ['margin_rate' => $this->marginRate->id]), [
            'name' => 'string2'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk(); // for popup
        // $res->assertRedirect(); // for non popup

        // update check
        $marginRate = MarginRate::find($this->marginRate->id);
        $this->assertTrue($marginRate->name == 'string2');
    }


    public function deleteMarginRate()
    {
        // delete
        $res = $this->delete(route('company::crud.margin-rate.destroy', ['margin_rate' => $this->marginRate->id]));
        $res->assertOk();
        $res->assertSessionHasNoErrors();

        // delete check
        $marginRate = MarginRate::find($this->marginRate->id);
        $this->assertNull($marginRate);
    }
}
