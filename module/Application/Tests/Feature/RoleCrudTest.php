<?php
namespace Module\Application\Tests\Feature;

use Module\Application\Role;
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
        $res = $this->get(route('application::crud.role.index'));
        $res->assertOk();

        // search ajax
        $res = $this->post(route('application::crud.role.search'), [
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
        $res = $this->get(route('application::crud.role.create'));
        $res->assertOk();

        // store
        $res = $this->post(route('application::crud.role.store'), [
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
        $res = $this->get(route('application::crud.role.show', ['role' => $this->role->id]));
        $res->assertOk();
    }


    public function updateRole()
    {
        // edit page
        $res = $this->get(route('application::crud.role.edit', ['role' => $this->role->id]));
        $res->assertOk();

        // update
        $res = $this->put(route('application::crud.role.update', ['role' => $this->role->id]), [
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
        $res = $this->delete(route('application::crud.role.destroy', ['role' => $this->role->id]));
        $res->assertOk();
        $res->assertSessionHasNoErrors();

        // delete check
        $role = Role::find($this->role->id);
        $this->assertNull($role);
    }
}
