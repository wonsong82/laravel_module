<?php
namespace Module\Company\Tests\Feature;

use Module\Company\Constants\UnitStatus;
use Module\Company\Constants\UnitType;
use Module\Company\Controllers\Logic\UnitController;
use Module\Company\Unit;
use Module\Application\Tests\FeatureTestCase;

class UnitCrudTest extends FeatureTestCase
{
    public $unit;

    public function testUnitCrud()
    {
        $this->listUnit();
        $this->createUnit();
        //$this->readUnit();
        $this->updateUnit();
        $this->deleteUnit();
    }


    public function listUnit()
    {
        // index page
        $res = $this->get(route('company::crud.unit.index'));
        $res->assertOk();

        // search ajax
        $res = $this->post(route('company::crud.unit.search'), [
            'search' => ['value' => 'ea']
        ]);
        $res->assertOk();
        $this->assertTrue(!!strstr($res->json()['data'][0][3], 'ea'));

        // sort

        // filter
    }


    public function createUnit()
    {
        // create page
        $res = $this->get(route('company::crud.unit.create'));
        $res->assertForbidden();

        // store
        $res = $this->post(route('company::crud.unit.store'), [
            'status_code' => UnitStatus::ACTIVE,
            'company_id' => $this->company->id,
            'type_code' => UnitType::AREA,
            'symbol' => 'string',
            'name' => 'string',
            'plural_name' => 'string'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertForbidden();

        // create
        app(UnitController::class)->create($this->company, [
            'status_code' => UnitStatus::ACTIVE,
            'company_id' => $this->company->id,
            'type_code' => UnitType::AREA,
            'symbol' => 'string',
            'name' => 'string',
            'plural_name' => 'string'
        ]);

        // exist
        $unit = Unit::where('symbol', 'string')->first();
        $this->assertNotNull($unit);
        $this->unit = $unit;
    }


    public function readUnit()
    {
        // show page
        $res = $this->get(route('company::crud.unit.show', ['unit' => $this->unit->id]));
        $res->assertOk();
    }


    public function updateUnit()
    {
        // edit page
        $res = $this->get(route('company::crud.unit.edit', ['unit' => $this->unit->id]));
        $res->assertOk();

        // update
        $res = $this->put(route('company::crud.unit.update', ['unit' => $this->unit->id]), [
            'company_id' => $this->company->id,
            'status_code' => UnitStatus::ACTIVE,
            'type_code' => UnitType::AREA,
            'symbol' => 'string2',
            'name' => 'string',
            'plural_name' => 'string'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // update check
        $unit = Unit::find($this->unit->id);
        $this->assertTrue($unit->symbol == 'string2');
    }


    public function deleteUnit()
    {
        // delete
        $res = $this->delete(route('company::crud.unit.destroy', ['unit' => $this->unit->id]));
        $res->assertForbidden();
        $res->assertSessionHasNoErrors();

        // delete check
        $unit = Unit::find($this->unit->id);
        $this->assertNotNull($unit);
    }
}
