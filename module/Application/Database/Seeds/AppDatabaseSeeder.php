<?php
namespace Module\Application\Database\Seeds;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Module\Application\Controllers\Logic\DBController;

class AppDatabaseSeeder extends Seeder {

    public function run()
    {
        $dbController = app(DBController::class);
        $dbController->truncateAllTables();

        Model::reguard();
        $this->call($dbController->getSeeders());
    }

}