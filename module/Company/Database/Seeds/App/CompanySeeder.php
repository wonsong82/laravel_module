<?php
namespace Module\Company\Database\Seeds\App;

use Illuminate\Database\Seeder;
use Module\Application\Locale;
use Module\Company\Controllers\Logic\CompanyController;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'ThreeOn',
                'legal_name' => null,
                'desc' => null,
                'phone' => '551-313-0031',
                'fax' => null,
                'email' => null,
                'website' => null,
                'currency_code' => 'USD',
                'locale_id' => Locale::findByLocale('en-US')->id,
                'timezone' => 'America/New_York',
                'note' => null,
                'physical_address_attention' => 'ThreeOn',
                'physical_address_line1' => '115 River Road.',
                'physical_address_line2' => '#172',
                'physical_address_line3' => null,
                'physical_address_line4' => null,
                'physical_address_city' => 'Edgewater',
                'physical_address_state' => 'NJ',
                'physical_address_zip' => '07020',
                'physical_address_country' => 'United States',
                'shipping_address_attention' => 'ThreeOn',
                'shipping_address_line1' => '115 River Road.',
                'shipping_address_line2' => '#172',
                'shipping_address_line3' => null,
                'shipping_address_line4' => null,
                'shipping_address_city' => 'Edgewater',
                'shipping_address_state' => 'NJ',
                'shipping_address_zip' => '07020',
                'shipping_address_country' => 'United States',
                'billing_address_attention' => 'ThreeOn',
                'billing_address_line1' => '115 River Road.',
                'billing_address_line2' => '#172',
                'billing_address_line3' => null,
                'billing_address_line4' => null,
                'billing_address_city' => 'Edgewater',
                'billing_address_state' => 'NJ',
                'billing_address_zip' => '07020',
                'billing_address_country' => 'United States',

                'users' => [
                    [
                        'name' => 'Admin',
                        'email' => 'admin@module.io',
                        'password' => '30nPass!'
                    ]
                ]
            ],

            [
                'name' => '아이오엔코',
                'legal_name' => null,
                'desc' => null,
                'phone' => null,
                'fax' => null,
                'email' => null,
                'website' => null,
                'currency_code' => 'KRW',
                'locale_id' => Locale::findByLocale('ko-KR')->id,
                'timezone' => 'Asia/Seoul',
                'note' => null,
                'physical_address_attention' => null,
                'physical_address_line1' => null,
                'physical_address_line2' => null,
                'physical_address_line3' => null,
                'physical_address_line4' => null,
                'physical_address_city' => null,
                'physical_address_state' => null,
                'physical_address_zip' => null,
                'physical_address_country' => 'Korea',
                'shipping_address_attention' => null,
                'shipping_address_line1' => null,
                'shipping_address_line2' => null,
                'shipping_address_line3' => null,
                'shipping_address_line4' => null,
                'shipping_address_city' => null,
                'shipping_address_state' => null,
                'shipping_address_zip' => null,
                'shipping_address_country' => 'Korea',
                'billing_address_attention' => null,
                'billing_address_line1' => null,
                'billing_address_line2' => null,
                'billing_address_line3' => null,
                'billing_address_line4' => null,
                'billing_address_city' => null,
                'billing_address_state' => null,
                'billing_address_zip' => null,
                'billing_address_country' => 'Korea',

                'users' => [
                    [
                        'name' => 'Admin',
                        'email' => 'admin@aio.com',
                        'password' => 'Aio1234!'
                    ]
                ]
            ]


        ];




        $controller = app(CompanyController::class);

        foreach($data as $companyData){
            $company = $controller->create($companyData);

            foreach($companyData['users'] as $userData){
                $controller->createUser($company, $userData);
            }
        }



    }
}
