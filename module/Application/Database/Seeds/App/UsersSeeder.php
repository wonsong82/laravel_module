<?php
namespace Module\Application\Database\Seeds\App;

use Illuminate\Database\Seeder;
use Module\Application\Locale;
use Module\Application\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // create super admin
        $admin = User::create([
            'email' => 'admin@app.com',
            'name' => 'Super Admin',
            'password' => bcrypt('30nPass!'),
            'locale_id' => Locale::findByLocale('en-US')->id,
            'timezone' => 'America/New_York'
        ]);

        auth()->login($admin);
    }
}
