<?php
namespace Module\Company\Database\Seeds\App;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Module\Account\Constants\UserStatus;
use Module\Account\User;
use Module\Application\Constants\AddressType;
use Module\Company\Company;
use Module\Company\Constants\CompanyStatus;
use Module\Company\Constants\MemberStatus;
use Module\Company\Controllers\Logic\OffdayController;

class OffdaySeeder extends Seeder
{
    public function run()
    {
        $ctl = new OffdayController();
        $ctl->generateOffdays();
    }
}
