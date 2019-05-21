<?php
namespace Module\Application\Database\Seeds\App;

use Illuminate\Database\Seeder;
use Module\Application\Controllers\Logic\PermissionController;
use Module\Application\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $controller = app(PermissionController::class);

        foreach($controller->permissions as $permission){
            $controller->create([
                'name' => $permission
            ]);
        }
    }
}
