<?php
namespace Module\Application\Tests\Feature;

use Module\Application\Locale;
use Module\Application\Tests\FeatureTestCase;

class LocaleCrudTest extends FeatureTestCase
{
    public $locale;

    public function testLocaleCrud()
    {
        $this->listLocale();
        $this->createLocale();
        //$this->readLocale();
        //$this->updateLocale();
        //$this->deleteLocale();
    }


    public function listLocale()
    {
        // index page
        $res = $this->get(route('application::crud.locale.index'));
        $res->assertOk();

        // search ajax
        $res = $this->post(route('application::crud.locale.search'), [
            'search' => ['value' => 'search']
        ]);
        $res->assertOk();
        //$this->assertTrue(!!strstr($res->json()['data'][0][1], 'search'));

        // sort

        // filter
    }


    public function createLocale()
    {
        // create page
        $res = $this->get(route('application::crud.locale.create'));
        $res->assertOk();

        // store
        $res = $this->post(route('application::crud.locale.store'), [
            'code' => 'string',
            'locale' => 'string',
            'country_code' => 'string',
            'language_code' => 'string',
            'country_name' => 'string',
            'language_name' => 'string',
            'encoding' => 'string'
        ]);

        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // exist
        $locale = Locale::where('code', 'string')->first();
        $this->assertNotNull($locale);
        $this->locale = $locale;
    }


    public function readLocale()
    {
        // show page
        $res = $this->get(route('application::crud.locale.show', ['locale' => $this->locale->id]));
        $res->assertOk();
    }


    public function updateLocale()
    {
        // edit page
        $res = $this->get(route('application::crud.locale.edit', ['locale' => $this->locale->id]));
        $res->assertOk();

        // update
        $res = $this->put(route('application::crud.locale.update', ['locale' => $this->locale->id]), [
            'code' => 'string2',
            'locale' => 'string',
            'country_code' => 'string',
            'language_code' => 'string',
            'country_name' => 'string',
            'language_name' => 'string',
            'encoding' => 'string'
        ]);

        $res->assertSessionHasNoErrors();
        $res->assertOk();


        // update check
        $locale = Locale::find($this->locale->id);
        $this->assertTrue($locale->code == 'string2');
    }


    public function deleteLocale()
    {
        // delete
        $res = $this->delete(route('application::crud.locale.destroy', ['locale' => $this->locale->id]));
        $res->assertOk();
        $res->assertSessionHasNoErrors();

        // delete check
        $locale = Locale::find($this->locale->id);
        $this->assertNull($locale);
    }
}
