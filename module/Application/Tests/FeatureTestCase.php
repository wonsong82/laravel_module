<?php
namespace Module\Application\Tests;



use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Module\Application\User;

abstract class FeatureTestCase extends TestCase
{
    public $user = null;
    public $company = null;


    public function setUp()
    {
        parent::setUp();

        // get user from env, if not get the first user as test user
        $user = User::where('email', env('TEST_USER', null))->first();
        if(!$user)
            $user = User::first();
        $this->actingAs($user);
        $this->user = $user;
        $this->company = $user->company ?? null;

        // open transaction and don't commit
        DB::beginTransaction();
    }



    public function tearDown()
    {
        // rollback the transaction
        DB::rollback();

        parent::tearDown();
    }


    /***
     * save test response to index.html
     * access it from  http://APP_URL/test_output
     *
     * @param $response
     */
    public function outputResponse($response)
    {
        $path = public_path('test_output/index.html');
        file_put_contents($path, $response->getContent());
    }

    /***
     * run this code to look at the session errors
     * access it from http://APP_URL/test_output/errors
     */
    public function outputSessionErrors()
    {
        $view = (string)view('application::test.dump', [
            'content' => session('errors')
        ]);

        $path = public_path('test_output/errors/index.html');
        file_put_contents($path, $view);
    }



}
