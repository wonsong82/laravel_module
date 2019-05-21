<?php
namespace Module\Application\Tests\Feature;

use Module\Application\Permission;
use Module\Application\Tests\FeatureTestCase;

class PermissionCrudTest extends FeatureTestCase
{
    public $permission;

    public function testPermissionCrud()
    {
        $this->listPermission();
        $this->createPermission();
        //$this->readPermission();
        $this->updatePermission();
        $this->deletePermission();
    }


    public function listPermission()
    {
        // index page
        $res = $this->get(route('application::crud.permission.index'));
        $res->assertOk();

        // search ajax
        $res = $this->post(route('application::crud.permission.search'), [
            'search' => ['value' => 'filemanager.read']
        ]);
        $res->assertOk();
        $this->assertTrue(!!strstr($res->json()['data'][0][1], 'filemanager.read'));

        // sort

        // filter
    }


    public function createPermission()
    {
        // create page
        $res = $this->get(route('application::crud.permission.create'));
        $res->assertStatus(200);

        // store
        $res = $this->post(route('application::crud.permission.store'), [
            'name' => 'string'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // exist
        $permission = Permission::where('name', 'string')->first();
        $this->assertNotNull($permission);
        $this->permission = $permission;
    }


    public function readPermission()
    {
        // show page
        $res = $this->get(route('application::crud.permission.show', ['permission' => $this->permission->id]));
        $res->assertOk();
    }


    public function updatePermission()
    {
        // edit page
        $res = $this->get(route('application::crud.permission.edit', ['permission' => $this->permission->id]));
        $res->assertOk();

        // update
        $res = $this->put(route('application::crud.permission.update', ['permission' => $this->permission->id]), [
            'name' => 'string2'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // update check
        $permission = Permission::find($this->permission->id);
        $this->assertTrue($permission->name == 'string2');
    }


    public function deletePermission()
    {
        // delete
        $res = $this->delete(route('application::crud.permission.destroy', ['permission' => $this->permission->id]));
        $res->assertOk();
        $res->assertSessionHasNoErrors();

        // delete check
        $permission = Permission::find($this->permission->id);
        $this->assertNull($permission);
    }
}
