<?php
namespace Module\Application\Database\Seeds\App;

use Illuminate\Database\Seeder;
use Module\Application\Locale;

class LocalesSeeder extends Seeder
{
    public function run()
    {
        $localeData = [
            [
                'code' => 'en',
                'locale' => 'en-US',
                'language_code' => 'en',
                'country_code' => 'US',
                'encoding' => 'UTF-8',
                'country_name' => 'United States',
                'language_name' => 'English (United States)',
            ],

            [
                'code' => 'ko',
                'locale' => 'ko-KR',
                'language_code' => 'ko',
                'country_code' => 'KR',
                'encoding' => 'UTF-8',
                'country_name' => 'Korea (the Republic of)',
                'language_name' => '한국어',
            ],

            [
                'code' => 'zh-cn',
                'locale' => 'zh-CN',
                'language_code' => 'zh',
                'country_code' => 'CN',
                'encoding' => 'UTF-8',
                'country_name' => 'China',
                'language_name' => '简体中文',
            ]
        ];

        foreach($localeData as $data){
            Locale::create($data);
        }

    }

}