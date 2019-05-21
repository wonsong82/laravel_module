<?php
namespace Module\Company\Tests\Unit;


use Illuminate\Support\Facades\DB;
use Module\Application\Tests\FeatureTestCase;
use Module\Company\Constants\CustomizationType;
use Module\Company\Constants\UnitType;


class CompanyUnitTest extends FeatureTestCase
{

    public function testHasCustomization()
    {

        $company = $this->company;

        $res = $company->hasCustomization(CustomizationType::REPORT);
        $this->assertNotNull($res);

        $res = $company->hasCustomization(999999111);
        $this->assertFalse($res);

        DB::table('customizations')->insert([
            'company_id' => $company->id,
            'type_code' => 999999111
        ]);

        $res = $company->hasCustomization(999999111);
        $this->assertTrue($res);
    }


    public function testMarginRate()
    {
        $company = $this->company;

        $company->marginRate->fill([
            'rates' => [
                4.56, 5.24, 6.0
            ]
        ])->save();

        $this->assertCount(3, $company->marginRate->rates);
    }


    public function testGetUnitOptions()
    {
        $company = $this->company;

        // single
        $options = $company->getUnitOptions(UnitType::COUNT);

        $this->assertTrue($options->count() != 0);


        // multiple
        $optionsMultiple = $company->getUnitOptions(UnitType::COUNT, UnitType::LENGTH);

        $this->assertTrue(($options->count() != $optionsMultiple->count()) && ($optionsMultiple->count() != 0));
    }


}