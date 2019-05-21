<?php
namespace Module\Application\Tests\Feature;

use Module\Application\Constants\UserStatus;
use Module\Application\Locale;
use Module\Application\User;
use Module\Application\Tests\FeatureTestCase;

class UserCrudTest extends FeatureTestCase
{
    public $userModel;

    public function testUserCrud()
    {
        $this->listUser();
        $this->createUser();
        //$this->readUser();
        $this->updateUser();
        $this->deleteUser();
    }


    public function listUser()
    {
        // index page
        $res = $this->get(route('application::crud.user.index'));
        $res->assertOk();

        // search ajax
        $res = $this->post(route('application::crud.user.search'), [
            'search' => ['value' => 'admin@app.com']
        ]);
        $res->assertOk();
        $this->assertTrue(!!strstr($res->json()['data'][0][2], 'admin@app.com'));

        // sort

        // filter
    }


    public function createUser()
    {
        // create page
        $res = $this->get(route('application::crud.user.create'));
        $res->assertOk();

        // store
        $res = $this->post(route('application::crud.user.store'), [
            'status_code' => UserStatus::ACTIVE,
            'email' => 'unittest@email.com',
            'name' => 'unittestname',
            'password' => '123123',
            'password_confirmation' => '123123',
            'locale_id' => Locale::findByLocale('en-US')->id,
            'timezone' => 'America/New_York'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // duplicate
        $res = $this->post(route('application::crud.user.store'), [
            'status_code' => UserStatus::ACTIVE,
            'email' => 'unittest@email.com',
            'name' => 'unittestname',
            'password' => '123123',
            'password_confirmation' => '123123',
            'locale_id' => Locale::findByLocale('en-US')->id,
            'timezone' => 'America/New_York'
        ]);
        $res->assertSessionHasErrors();
        $res->assertRedirect();

        // exist
        $user = User::where('name', 'unittestname')->first();
        $this->assertNotNull($user);
        $this->userModel = $user;
    }


    public function readUser()
    {
        // show page
        $res = $this->get(route('application::crud.user.show', ['user' => $this->userModel->id]));
        $res->assertOk();
    }


    public function updateUser()
    {
        // edit page
        $res = $this->get(route('application::crud.user.edit', ['user' => $this->userModel->id]));
        $res->assertOk();

        // update
        $res = $this->put(route('application::crud.user.update', ['user' => $this->userModel->id]), [
            'status_code' => UserStatus::ACTIVE,
            'email' => 'unittesttest@email.com',
            'name' => 'unittestname2',
            'password' => '123123',
            'password_confirmation' => '123123',
            'locale_id' => Locale::findByLocale('en-US')->id,
            'timezone' => 'America/New_York'
        ]);
        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // update check
        $user = User::find($this->userModel->id);
        $this->assertTrue($user->name == 'unittestname2');
    }


    public function deleteUser()
    {
        // delete
        $res = $this->delete(route('application::crud.user.destroy', ['user' => $this->userModel->id]));
        $res->assertOk();
        $res->assertSessionHasNoErrors();

        // delete check
        $user = User::find($this->userModel->id);
        $this->assertNull($user);
    }
}
