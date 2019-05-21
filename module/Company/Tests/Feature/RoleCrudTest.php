<?php
namespace Module\Company\Tests\Feature;

use Module\Company\Role;
use Module\Application\Tests\FeatureTestCase;

class RoleCrudTest extends FeatureTestCase
{
    public $role;

    public function testRoleCrud()
    {
        $this->listRole();
        $this->createRole();
        //$this->readRole();
        $this->updateRole();
        $this->deleteRole();
    }


    public function listRole()
    {
        // index page
        $res = $this->get(route('company::crud.role.index'));
        $res->assertOk();

        // search ajax
        $res = $this->post(route('company::crud.role.search'), [
            'search' => ['value' => 'search']
        ]);
        $res->assertOk();
        //$this->assertTrue(!!strstr($res->json()['data'][0][1], 'search'));

        // sort

        // filter
    }


    public function createRole()
    {
        // create page
        $res = $this->get(route('company::crud.role.create'));
        $res->assertOk();

        // store
        $res = $this->post(route('company::crud.role.store'), [
            'company_id' => $this->company->id,
            'name' => 'string'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // exist
        $role = Role::where('name', 'string')->first();
        $this->assertNotNull($role);
        $this->role = $role;
    }


    public function readRole()
    {
        // show page
        $res = $this->get(route('company::crud.role.show', ['role' => $this->role->id]));
        $res->assertOk();
    }


    public function updateRole()
    {
        // edit page
        $res = $this->get(route('company::crud.role.edit', ['role' => $this->role->id]));
        $res->assertOk();

        // update
        $res = $this->put(route('company::crud.role.update', ['role' => $this->role->id]), [
            'company_id' => $this->company->id,
            'name' => 'string2'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // update check
        $role = Role::find($this->role->id);
        $this->assertTrue($role->name == 'string2');
    }


    public function deleteRole()
    {
        // delete
        $res = $this->delete(route('company::crud.role.destroy', ['role' => $this->role->id]));
        $res->assertOk();
        $res->assertSessionHasNoErrors();

        // delete check
        $role = Role::find($this->role->id);
        $this->assertNull($role);
    }
}
