<?php
namespace Module\Company\Tests\Feature;

use Module\Application\Locale;
use Module\Company\Company;
use Module\Application\Tests\FeatureTestCase;
use Module\Company\Constants\CompanyStatus;
use Module\Company\Controllers\Logic\CompanyController;

class CompanyCrudTest extends FeatureTestCase
{
    public $company;

    public function testCompanyCrud()
    {
        //$this->listCompany();
        //$this->createCompany();
        $this->readCompany();
        $this->updateCompany();
        $this->deleteCompany();
    }


    public function listCompany()
    {
        // index page
        $res = $this->get(route('company::crud.company.index'));
        $res->assertOk();

        // search ajax
        $res = $this->post(route('company::crud.company.search'), [
            'search' => ['value' => 'search']
        ]);
        $res->assertOk();
        //$this->assertTrue(!!strstr($res->json()['data'][0][1], 'search'));

        // sort

        // filter
    }


    public function createCompany()
    {
        app(CompanyController::class)->create([
            'status_code' => CompanyStatus::ACTIVE,
            'name' => 'string',
            'locale_id' => Locale::findByLocale('en-US')->id,
            'timezone' => 'America/New_York',
            'shipping_address_line1' => 'string',
            'currency_code' => 'USD'
        ]);

        // exist
        $company = Company::where('name', 'string')->first();
        $this->assertNotNull($company);
        $this->company = $company;
    }


    public function readCompany()
    {
        // show page
        $res = $this->get(route('company::crud.company.show', ['company' => $this->company->id]));
        $res->assertOk();
    }


    public function updateCompany()
    {
        // edit page
        $res = $this->get(route('company::crud.company.index'));
        $res->assertOk();

        // update
        $res = $this->put(route('company::crud.company.update', ['company' => $this->company->id]), [
            'status_code' => CompanyStatus::ACTIVE,
            'code' => 'TestCode',
            'name' => 'string',
            'locale_id' => Locale::findByLocale('en-US')->id,
            'timezone' => 'America/New_York',
            'shipping_address_line1' => 'string2',
            'currency_code_id' => 1
        ]);

        $res->assertSessionHasNoErrors();
        $res->assertOk();

        // update check
        $company = Company::find($this->company->id);
        $this->assertTrue($company->shipping_address->line1 == 'string2');
    }


    public function deleteCompany()
    {
        // delete
        $res = $this->delete(route('company::crud.company.destroy', ['company' => $this->company->id]));
        $res->assertOk();
        $res->assertSessionHasNoErrors();

        // delete check
        $company = Company::find($this->company->id);
        $this->assertNull($company);
    }
}
