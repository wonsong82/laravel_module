<?php
namespace Module\Company\Tests\Feature;

use Module\Company\Constants\CompanyUserStatus;
use Module\Company\CompanyUser as User;
use Module\Application\Tests\FeatureTestCase;

class UserCrudTest extends FeatureTestCase
{
    public $userModel;

    public function testUserCrud()
    {
        $this->listUser();
        $this->createUser();
        $this->readUser();
        $this->updateUser();
        $this->deleteUser();
    }


    public function listUser()
    {
        // index page
        $res = $this->get(route('company::crud.user.index'));
        $res->assertOk();

        // search ajax
        $res = $this->post(route('company::crud.user.search'), [
            'search' => ['value' => $this->user->email]
        ]);
        $res->assertOk();
        $this->assertTrue(!!strstr($res->json()['data'][0][4], $this->user->email));

        // sort

        // filter
    }


    public function createUser()
    {
        // create page
        $res = $this->get(route('company::crud.user.create'));
        $res->assertOk();

        // store
        $res = $this->post(route('company::crud.user.store'), [
            'company_id' => $this->company->id,
            'status_code' => CompanyUserStatus::ACTIVE,
            'name' => 'string',
            'email' => 'string@emali.com',
            'password' => 'string',
            'password_confirmation' => 'string'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // exist
        $user = User::where('name', 'string')->first();
        $this->assertNotNull($user);
        $this->userModel = $user;
    }


    public function readUser()
    {
        // show page
        $res = $this->get(route('company::crud.user.show', ['user' => $this->userModel->id]));
        $res->assertOk();
    }


    public function updateUser()
    {
        // edit page
        $res = $this->get(route('company::crud.user.edit', ['user' => $this->userModel->id]));
        $res->assertOk();

        // update
        $res = $this->put(route('company::crud.user.update', ['user' => $this->userModel->id]), [
            'company_id' => $this->company->id,
            'status_code' => CompanyUserStatus::ACTIVE,
            'code' => 'dfsadfaefafe',
            'name' => 'string2',
            'email' => 'string@emali.com',
            'password' => 'string',
            'password_confirmation' => 'string'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // update check
        $user = User::find($this->userModel->id);
        $this->assertTrue($user->name == 'string2');
    }


    public function deleteUser()
    {
        // delete
        $res = $this->delete(route('company::crud.user.destroy', ['user' => $this->userModel->id]));
        $res->assertOk();
        $res->assertSessionHasNoErrors();

        // delete check
        $user = User::find($this->userModel->id);
        $this->assertNull($user);
    }
}
